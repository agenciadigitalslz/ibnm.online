# Analise de Seguranca - Modulo Financeiro

**Data:** 2026-01-23
**Criticidade Geral:** ALTA

---

## Resumo de Vulnerabilidades

| Tipo | Quantidade | Criticidade |
|------|------------|-------------|
| SQL Injection | 50+ ocorrencias | CRITICA |
| Falta de verificar.php | 2 paginas | ALTA |
| Filtro igreja ausente | 5+ queries | ALTA |
| Prepared statements inconsistentes | 30+ arquivos | MEDIA |

---

## 1. SQL INJECTION (CRITICA)

### receber.php (linha 22)
```php
// VULNERAVEL - Concatenacao direta
$qU = $pdo->query("SELECT id, nome FROM usuarios WHERE igreja = '$id_igreja' AND nivel IN ('Tesoureiro','Pastor','Secretário','Administrador') ORDER BY nome");

// CORRETO - Prepared Statement
$qU = $pdo->prepare("SELECT id, nome FROM usuarios WHERE igreja = ? AND nivel IN ('Tesoureiro','Pastor','Secretário','Administrador') ORDER BY nome");
$qU->execute([$id_igreja]);
```

### receber/salvar.php (linhas 51, 66, 122)
```php
// VULNERAVEL
$query2 = $pdo->query("SELECT * FROM membros where id = '$membro'");
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$query1 = $pdo->query("SELECT * from caixas where operador = '$id_usuario' and data_fechamento is null");

// CORRETO
$query2 = $pdo->prepare("SELECT * FROM membros WHERE id = ?");
$query2->execute([$membro]);
```

### receber/listar.php (multiplas linhas)
```php
// VULNERAVEL - Linhas 89, 101, 113, 126, 137, 149, 166, 178-191
$query = $pdo->query("SELECT * from $tabela where igreja = '$id_igreja' and pago = 'Não' $sql_usuario_lanc");

// NOTA: Existem 50+ queries vulneraveis somente neste arquivo
```

### pagar/salvar.php (linhas 55, 116, 133)
```php
// VULNERAVEL
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$query = $pdo->query("SELECT * FROM $tab where id = '$id_pessoa'");
```

### dizimos.php e ofertas.php (linhas 79, 91)
```php
// VULNERAVEL - N+1 Query + SQL Injection
$query = $pdo->query("SELECT * FROM membros where igreja = '$id_igreja' order by id asc");
// Dentro do loop:
$query_con = $pdo->query("SELECT * FROM cargos where id = '$cargo'");
```

---

## 2. FALTA DE VERIFICAR.PHP (ALTA)

### Arquivos sem autenticacao:
```
dizimos.php      - NAO inclui verificar.php
ofertas.php      - NAO inclui verificar.php
vendas.php       - NAO inclui verificar.php
fechamentos.php  - NAO inclui verificar.php
```

**Impacto:** Usuarios nao autenticados podem acessar estas paginas (dependendo da configuracao do index.php pai).

**Correcao:**
```php
<?php
require_once("verificar.php");  // ADICIONAR ESTA LINHA
$pag = 'dizimos';
```

---

## 3. FILTRO DE IGREJA AUSENTE (ALTA)

### Queries sem filtro WHERE igreja = ?

**receber/salvar.php linha 51:**
```php
// VULNERAVEL - Pode acessar membro de outra igreja
$query2 = $pdo->query("SELECT * FROM membros where id = '$membro'");

// CORRETO
$query2 = $pdo->prepare("SELECT * FROM membros WHERE id = ? AND igreja = ?");
$query2->execute([$membro, $id_igreja]);
```

**receber/listar.php linhas 281, 290, 299, 307, 318:**
```php
// VULNERAVEL - Busca usuarios/frequencias/formas_pgto sem filtro
$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_lanc'");
$query2 = $pdo->query("SELECT * FROM frequencias where dias = '$frequencia'");
$query2 = $pdo->query("SELECT * FROM formas_pgto where id = '$forma_pgto'");
```

**NOTA:** Tabelas como `frequencias` e `formas_pgto` parecem ser globais (sem coluna igreja), o que esta correto. Mas `usuarios` e `membros` DEVEM ter validacao de igreja.

---

## 4. PREPARED STATEMENTS INCONSISTENTES (MEDIA)

### Padrao Misto no mesmo arquivo

**receber/salvar.php:**
```php
// Usa prepare para INSERT (BOM)
$query = $pdo->prepare("INSERT INTO $tabela SET descricao = :descricao...");
$query->bindValue(":descricao", "$descricao");

// Mas concatena outras variaveis na mesma query (RUIM)
"...vencimento = '$vencimento' $pgto, forma_pgto = '$forma_pgto'..."
```

---

## 5. OUTRAS VULNERABILIDADES

### XSS Potencial
```php
// receber.php linha 24 - Sanitiza saida (BOM)
echo '<option value="'.$u['id'].'">'.htmlspecialchars($u['nome']).'</option>';

// Porem em listar.php, alguns outputs nao sao sanitizados
echo "<td>{$descricao}</td>";  // Se descricao tiver HTML malicioso...
```

### Upload de Arquivos
```php
// receber/salvar.php - Validacao de extensao existe (BOM)
if($ext == 'png' or $ext == 'jpg' or ... or $ext == 'xml'){
    // Permite upload
}

// POREM: Nao valida MIME type real do arquivo
// Atacante pode renomear .php para .jpg e fazer upload
```

---

## 6. MATRIZ DE RISCO

| Arquivo | SQL Injection | Auth | Igreja Filter | Prioridade |
|---------|---------------|------|---------------|------------|
| receber.php | SIM | OK | OK | ALTA |
| receber/salvar.php | SIM | OK | PARCIAL | CRITICA |
| receber/listar.php | SIM | OK | OK | ALTA |
| pagar.php | SIM | OK | OK | ALTA |
| pagar/salvar.php | SIM | OK | PARCIAL | CRITICA |
| dizimos.php | SIM | FALTA | OK | CRITICA |
| ofertas.php | SIM | FALTA | OK | CRITICA |
| vendas.php | MENOR | FALTA | OK | ALTA |
| fechamentos.php | MENOR | FALTA | OK | ALTA |
| financeiro_geral.php | OK | OK | OK | BAIXA |

---

## 7. PLANO DE CORRECAO SUGERIDO

### Fase 1 - Critica (Imediato)
1. Adicionar `require_once("verificar.php")` em dizimos.php, ofertas.php, vendas.php, fechamentos.php
2. Converter queries de salvar.php para prepared statements
3. Adicionar filtro de igreja em queries de membros

### Fase 2 - Alta (1-2 semanas)
4. Converter todas as queries de listar.php para prepared statements
5. Adicionar validacao MIME type em uploads
6. Sanitizar todas as saidas HTML

### Fase 3 - Media (Manutencao continua)
7. Padronizar uso de prepared statements em todo o modulo
8. Criar funcoes helper para queries comuns
9. Implementar auditoria de acessos

---

## 8. EXEMPLOS DE CORRECAO

### Query Segura Padrao
```php
// Antes (vulneravel)
$query = $pdo->query("SELECT * FROM receber WHERE igreja = '$id_igreja' AND pago = '$pago'");

// Depois (seguro)
$query = $pdo->prepare("SELECT * FROM receber WHERE igreja = ? AND pago = ?");
$query->execute([$id_igreja, $pago]);
$res = $query->fetchAll(PDO::FETCH_ASSOC);
```

### Funcao Helper Sugerida
```php
function querySegura(PDO $pdo, string $sql, array $params = []): array {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Uso:
$membros = querySegura($pdo, "SELECT * FROM membros WHERE igreja = ? ORDER BY nome", [$id_igreja]);
```

---

*Analise de seguranca realizada em 2026-01-23*
*Recomenda-se correcao imediata das vulnerabilidades criticas*
