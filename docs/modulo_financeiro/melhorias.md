# Melhorias Sugeridas - Modulo Financeiro

**Data:** 2026-01-23
**Baseado em:** Analise completa do codigo existente

---

## Classificacao de Prioridades

| Prioridade | Descricao | Prazo Sugerido |
|------------|-----------|----------------|
| CRITICA | Seguranca - risco imediato | Imediato |
| ALTA | Funcionalidade comprometida | 1-2 semanas |
| MEDIA | Melhorias importantes | 1 mes |
| BAIXA | Nice to have | Backlog |

---

## 1. MELHORIAS CRITICAS (Seguranca)

### 1.1 Adicionar verificar.php nas paginas
**Arquivos:** dizimos.php, ofertas.php, vendas.php, fechamentos.php
**Esforco:** 5 minutos cada
**Impacto:** Previne acesso nao autenticado

```php
// ADICIONAR no inicio de cada arquivo:
<?php
require_once("verificar.php");
$pag = 'dizimos';
```

### 1.2 Converter queries para prepared statements
**Arquivos:** receber/salvar.php, pagar/salvar.php
**Esforco:** 30 minutos cada
**Impacto:** Previne SQL Injection

```php
// ANTES:
$query = $pdo->query("SELECT * FROM membros where id = '$membro'");

// DEPOIS:
$query = $pdo->prepare("SELECT * FROM membros WHERE id = ? AND igreja = ?");
$query->execute([$membro, $_SESSION['id_igreja']]);
```

### 1.3 Adicionar filtro de igreja em queries de validacao
**Arquivos:** salvar.php de todos os modulos
**Esforco:** 15 minutos cada
**Impacto:** Garante isolamento multi-tenant

---

## 2. MELHORIAS ALTAS (Funcionalidade)

### 2.1 Padronizar listar.php com prepared statements
**Arquivos:** Todos os listar.php do modulo financeiro
**Esforco:** 2-3 horas total
**Impacto:** Seguranca + Performance

**Sugestao de refatoracao:**
```php
// Criar funcao helper no inicio do arquivo
function buscarContas(PDO $pdo, int $igreja, array $filtros = []): array {
    $sql = "SELECT * FROM receber WHERE igreja = ?";
    $params = [$igreja];

    if (!empty($filtros['pago'])) {
        $sql .= " AND pago = ?";
        $params[] = $filtros['pago'];
    }

    if (!empty($filtros['data_ini']) && !empty($filtros['data_fim'])) {
        $sql .= " AND vencimento BETWEEN ? AND ?";
        $params[] = $filtros['data_ini'];
        $params[] = $filtros['data_fim'];
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
```

### 2.2 Resolver N+1 Query Problem em dizimos.php
**Arquivo:** dizimos.php, ofertas.php
**Esforco:** 30 minutos
**Impacto:** Performance significativa

```php
// ANTES: Query para cada membro (N+1)
foreach($membros as $m){
    $query = $pdo->query("SELECT * FROM cargos where id = '{$m['cargo']}'");
}

// DEPOIS: JOIN na query principal
$query = $pdo->prepare("
    SELECT m.*, c.nome as nome_cargo
    FROM membros m
    LEFT JOIN cargos c ON m.cargo = c.id
    WHERE m.igreja = ?
    ORDER BY m.nome
");
$query->execute([$id_igreja]);
```

### 2.3 Validacao MIME type em uploads
**Arquivos:** salvar.php que aceitam upload
**Esforco:** 15 minutos
**Impacto:** Previne upload de arquivos maliciosos

```php
// Adicionar apos validacao de extensao:
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $imagem_temp);
finfo_close($finfo);

$allowed_mimes = [
    'image/jpeg', 'image/png', 'image/gif', 'image/webp',
    'application/pdf', 'application/zip', 'application/x-rar-compressed',
    'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
];

if (!in_array($mime, $allowed_mimes)) {
    echo 'Tipo de arquivo nao permitido!';
    exit();
}
```

---

## 3. MELHORIAS MEDIAS (Codigo/UX)

### 3.1 Extrair CSS duplicado para arquivo separado
**Arquivos:** receber.php, pagar.php, financeiro_geral.php
**Esforco:** 1 hora
**Impacto:** Manutencao mais facil

```css
/* Criar: sistema/painel-igreja/css/financeiro.css */
.fin-cards { display:flex; flex-wrap:wrap; gap:10px; }
.fin-cards .card { flex:1 1 180px; min-width:160px; max-width:calc(33.33% - 10px); }
/* ... resto do CSS compartilhado */
```

### 3.2 Extrair JavaScript duplicado para arquivo separado
**Arquivos:** receber.php, pagar.php
**Esforco:** 2 horas
**Impacto:** Reducao de codigo duplicado

```javascript
// Criar: sistema/painel-igreja/js/financeiro.js
// Mover funcoes: totalizar(), calcularTaxa(), carregarImg(), etc.
```

### 3.3 Criar componente reutilizavel para cards
**Impacto:** Padronizacao visual, menos duplicacao

```php
// Criar: sistema/painel-igreja/componentes/card_financeiro.php
function renderCard($titulo, $valor, $cor, $onclick = '') {
    $valorF = 'R$ ' . number_format($valor, 2, ',', '.');
    return <<<HTML
    <div class="card text-center mb-3 fin-card">
        <a href="#" onclick="$onclick">
            <div class="card-header border-light text-white" style="background:$cor">$titulo</div>
            <div class="card-body"><h4><span>$valorF</span></h4></div>
        </a>
    </div>
    HTML;
}
```

### 3.4 Adicionar tratamento de erros mais robusto
**Esforco:** 1-2 horas
**Impacto:** Melhor experiencia do usuario

```php
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
} catch (PDOException $e) {
    error_log("Erro SQL: " . $e->getMessage());
    echo json_encode(['erro' => 'Ocorreu um erro. Tente novamente.']);
    exit();
}
```

---

## 4. MELHORIAS BAIXAS (Nice to Have)

### 4.1 Implementar soft delete
**Impacto:** Permitir recuperacao de dados excluidos

```sql
ALTER TABLE receber ADD COLUMN deleted_at DATETIME NULL;
ALTER TABLE pagar ADD COLUMN deleted_at DATETIME NULL;
```

### 4.2 Adicionar auditoria de alteracoes
**Impacto:** Rastreabilidade completa

```sql
CREATE TABLE auditoria_financeiro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tabela VARCHAR(50),
    registro_id INT,
    acao ENUM('INSERT', 'UPDATE', 'DELETE'),
    dados_antes JSON,
    dados_depois JSON,
    usuario INT,
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### 4.3 Implementar cache para totalizadores
**Impacto:** Performance em igrejas com muitos registros

### 4.4 Adicionar graficos no dashboard
**Impacto:** Melhor visualizacao de dados

### 4.5 Exportacao para Excel
**Impacto:** Facilitar analises externas

---

## 5. ORDEM SUGERIDA DE IMPLEMENTACAO

### Sprint 1 (Seguranca Critica) - CONCLUIDA em 2026-01-23 [Update_v002]
- [x] 1.1 Adicionar verificar.php (dizimos, ofertas, vendas, fechamentos)
- [x] 1.2 Prepared statements em salvar.php (receber, pagar)
- [x] 1.3 Filtro de igreja em validacoes (receber, pagar)
- [x] BONUS: Resolvido N+1 query em dizimos.php e ofertas.php

### Sprint 2 (Seguranca Alta) - CONCLUIDA em 2026-01-23 [Update_v003]
- [x] 2.1 Padronizar listar.php com prepared statements
- [x] 2.2 Validacao MIME upload em receber/salvar.php e pagar/salvar.php
- [x] BONUS: Otimizacao de queries de totalizadores com SUM()
- [x] BONUS: Validacao whitelist para tipo_data

### Sprint 3 (Refatoracao) - CONCLUIDA em 2026-01-23 [Update_v004]
- [x] 3.1 Extrair CSS para financeiro.css
- [x] 3.2 Extrair JavaScript para financeiro.js
- [x] 3.3 Atualizar receber.php, pagar.php e financeiro_geral.php
- [x] 3.4 Corrigir charset UTF-8 nas conexoes PDO (sistema + API)
- [x] 3.5 Adicionar notificacoes no Dashboard (contas vencidas/hoje)
- [x] BONUS: Reducao de ~700 linhas de codigo duplicado

### Backlog
- [ ] 4.1 Soft delete
- [ ] 4.2 Auditoria
- [ ] 4.3 Cache
- [ ] 4.4 Graficos
- [ ] 4.5 Export Excel

---

## 6. ESTIMATIVA DE ESFORCO TOTAL

| Categoria | Horas Estimadas |
|-----------|-----------------|
| Criticas | 2h |
| Altas | 4h |
| Medias | 5h |
| Baixas | 10h+ |
| **TOTAL** | **21h+** |

---

## 7. ARQUIVOS MAIS CRITICOS PARA CORRECAO

1. ~~**receber/salvar.php** - SQL Injection + validacao igreja~~ CORRIGIDO [Update_v002]
2. ~~**pagar/salvar.php** - SQL Injection + validacao igreja~~ CORRIGIDO [Update_v002]
3. ~~**receber/listar.php** - 14 queries vulneraveis~~ CORRIGIDO [Update_v003]
4. ~~**pagar/listar.php** - 15 queries vulneraveis~~ CORRIGIDO [Update_v003]
5. ~~**dizimos.php** - Falta verificar.php + N+1 query~~ CORRIGIDO [Update_v002]
6. ~~**ofertas.php** - Falta verificar.php + N+1 query~~ CORRIGIDO [Update_v002]

---

## 8. HISTORICO DE UPDATES

| Update | Sprint | Data | Arquivos |
|--------|--------|------|----------|
| Update_v001 | - | 2026-01-23 | financeiro_geral/listar.php (cards duplicados) |
| Update_v002 | Sprint 1 | 2026-01-23 | dizimos.php, ofertas.php, vendas.php, fechamentos.php, receber/salvar.php, pagar/salvar.php |
| Update_v003 | Sprint 2 | 2026-01-23 | receber/listar.php, pagar/listar.php, receber/salvar.php (MIME), pagar/salvar.php (MIME) |
| Update_v004 | Sprint 3 | 2026-01-23 | css/financeiro.css, js/financeiro.js, receber.php, pagar.php, financeiro_geral.php, conexao.php (charset), apiIgreja/conexao.php, home.php (notificacoes) |

---

*Melhorias priorizadas em 2026-01-23*
*Sprint 1, 2 e 3 concluidas - Proximo: Backlog (Soft delete, Auditoria, Cache, Graficos, Export Excel)*
