# Fluxos e Processos - Modulo Financeiro

## 1. Fluxo Geral do Sistema

```
Usuario Logado
     |
     v
[Index.php - Painel Igreja]
     |
     +---> [Financeiro Geral] --> Dashboard com totais
     |
     +---> [Receber] --> Contas a Receber (Entradas)
     |         |
     |         +---> Cadastrar --> Modal Form --> salvar.php
     |         +---> Listar --> AJAX listar.php
     |         +---> Baixar --> Modal Baixar --> baixar.php
     |         +---> Parcelar --> Modal Parcelar --> parcelar.php
     |         +---> Relatorio --> PDF DOMPDF
     |
     +---> [Pagar] --> Contas a Pagar (Saidas)
     |         |
     |         +---> (mesmo fluxo de Receber)
     |
     +---> [Dizimos] --> Registro de dizimos
     +---> [Ofertas] --> Registro de ofertas
     +---> [Vendas] --> Registro de vendas
     +---> [Fechamentos] --> Fechamento mensal
```

---

## 2. Fluxo de Contas a Receber

### 2.1 Cadastro de Nova Conta
```
1. Usuario clica em "Adicionar"
2. Modal abre com formulario vazio
3. Preenche: Descricao, Valor, Membro, Vencimento, etc.
4. Opcional: Upload de arquivo/comprovante
5. Clica em "Salvar"
6. AJAX envia para receber/salvar.php
7. PHP:
   - Valida campos obrigatorios
   - Processa upload de arquivo (se houver)
   - Verifica caixa aberto do usuario
   - INSERT na tabela receber
   - Se WhatsApp ativo: agenda cobranca
8. Retorna "Salvo com Sucesso"
9. JavaScript atualiza listagem via AJAX
```

### 2.2 Baixa de Conta (Recebimento)
```
1. Usuario clica no icone "Baixar" (check verde)
2. Modal abre com:
   - Valor da conta (editavel para baixa parcial)
   - Forma de pagamento
   - Multa, Juros, Desconto
   - Taxa calculada automaticamente
   - Data da baixa
   - Subtotal calculado
3. Clica em "Baixar"
4. AJAX envia para receber/baixar.php
5. PHP:
   - Calcula subtotal final
   - UPDATE na tabela: pago='Sim', data_pgto, usuario_pgto
   - Se baixa parcial: cria residuo
6. Retorna "Baixado com Sucesso"
7. Atualiza listagem e cards
```

### 2.3 Baixa em Lote
```
1. Usuario marca checkboxes das contas
2. Aparece botao "Baixar"
3. Clica em "Baixar" -> mostra total
4. Confirma baixa
5. Loop: baixar_multiplas.php para cada conta
6. Atualiza listagem
```

### 2.4 Parcelamento
```
1. Usuario clica no icone "Parcelar" (calendario)
2. Modal abre com:
   - Valor total (readonly)
   - Quantidade de parcelas
   - Frequencia (Mensal, Quinzenal, etc.)
3. Clica em "Parcelar"
4. AJAX envia para receber/parcelar.php
5. PHP:
   - Calcula valor de cada parcela
   - Calcula datas de vencimento
   - INSERT de N parcelas
   - Opcional: DELETE ou marca conta original
6. Atualiza listagem
```

---

## 3. Fluxo do Financeiro Geral (Dashboard)

### 3.1 Carregamento da Pagina
```
1. Carrega financeiro_geral.php
2. PHP calcula todos os totais:
   - Recebimentos: pendentes, vencidas, hoje, amanha, recebidas, total
   - Pagamentos: pendentes, vencidas, hoje, amanha, pagas, total
   - Balanco: entradas - saidas
3. Renderiza cards com totais
4. JavaScript dispara AJAX para listar movimentacoes
5. listar.php retorna tabela com UNION de receber + pagar
```

### 3.2 Filtros
```
1. Usuario altera data inicial/final ou tesoureiro
2. JavaScript dispara buscar() com debounce de 100ms
3. AJAX recarrega cards (via PHP com GET params)
4. AJAX recarrega tabela (via listar.php com POST)
```

### 3.3 Problema Corrigido (Cards Duplicados)
```
ANTES:
- financeiro_geral.php tinha cards
- listar.php TAMBEM tinha cards
- Resultado: cards apareciam 2x

DEPOIS (Update_v001):
- financeiro_geral.php mantem cards
- listar.php apenas tabela
- Resultado: cards aparecem 1x apenas
```

---

## 4. Fluxo de Dizimos e Ofertas

### 4.1 Cadastro Simplificado
```
1. Usuario clica em "Adicionar"
2. Modal com: Valor, Data, Membro (select2)
3. Salva -> salvar.php
4. INSERT simples na tabela dizimos/ofertas
5. Atualiza listagem
```

### 4.2 Diferenca para Receber/Pagar
```
Dizimos/Ofertas:
- Campos simples (valor, data, membro)
- Sem sistema de baixa
- Sem parcelamento
- Sem arquivos
- Apenas registro para relatorios

Receber/Pagar:
- Sistema completo de contas
- Baixa total ou parcial
- Parcelamento
- Anexos
- Multa, juros, desconto, taxa
- Integracao WhatsApp
```

---

## 5. Fluxo de Fechamentos

### 5.1 Criar Fechamento Mensal
```
1. Usuario seleciona data (qualquer dia do mes)
2. Clica em "Salvar"
3. PHP:
   - Extrai mes/ano da data
   - Calcula totais do mes:
     * Total recebido
     * Total pago
     * Saldo
   - INSERT na tabela fechamentos
4. Fechamento fica registrado
5. Serve como "foto" do mes para relatorios
```

---

## 6. Fluxo de Relatorios PDF

### 6.1 Geracao de Relatorio
```
1. Usuario clica no botao PDF
2. Form POST para rel/*_class.php
3. PHP:
   - Recebe filtros (datas, etc.)
   - Busca dados no banco
   - Monta HTML do relatorio
   - DOMPDF converte para PDF
4. Browser exibe/baixa PDF
```

### 6.2 Tipos de Relatorio
```
- Receber: Lista de contas a receber filtradas
- Pagar: Lista de contas a pagar filtradas
- Financeiro: Resumo consolidado
- Sintetico: Visao geral simplificada
- Balanco Anual: Resumo do ano
- Recibo: Comprovante individual de pagamento
```

---

## 7. Fluxo da API Mobile

### 7.1 Autenticacao
```
1. App envia login para apiIgreja/login/login.php
2. Valida usuario/senha
3. Retorna dados do usuario + id_igreja
4. App armazena para usar nas proximas requests
```

### 7.2 Operacoes CRUD
```
GET  /apiIgreja/receber/listar.php?igreja=X
POST /apiIgreja/receber/salvar.php
POST /apiIgreja/receber/baixar.php
POST /apiIgreja/receber/excluir.php

Todas as requests devem incluir id_igreja para filtro
```

---

## 8. Variaveis de Sessao Importantes

```php
$_SESSION['id']          // ID do usuario logado
$_SESSION['id_igreja']   // ID da igreja (multi-tenant)
$_SESSION['nivel']       // Nivel: Pastor, Tesoureiro, etc.
$_SESSION['registros']   // 'Sim' ou 'Nao' - ver apenas proprios registros
```

### Regra de Visibilidade
```php
if($mostrar_registros == 'Não'){
    // Usuario ve apenas contas que ele lancou
    $sql .= " AND usuario_lanc = '$id_usuario'";
}else{
    // Usuario ve todas as contas da igreja
}
```

---

## 9. Integracao WhatsApp

### 9.1 Agendamento de Cobranca
```
1. Ao salvar conta a receber com membro
2. Se $api_whatsapp != 'Não' e membro tem telefone
3. Monta mensagem de cobranca
4. Agenda para data de vencimento as 08:00
5. Chama apis/agendar.php
6. Salva hash na conta para rastreamento
```

### 9.2 Cobranca Manual
```
1. Usuario clica icone WhatsApp na conta
2. AJAX chama receber/cobrar.php
3. Envia mensagem imediatamente
```

---

*Fluxos documentados em 2026-01-23*
