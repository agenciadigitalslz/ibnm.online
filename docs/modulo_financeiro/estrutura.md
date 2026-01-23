# Estrutura de Arquivos - Modulo Financeiro

## Painel Igreja (sistema/painel-igreja/paginas/)

### 1. Financeiro Geral (Dashboard)
```
financeiro_geral.php          # Pagina principal com cards de resumo
financeiro_geral/
  listar.php                  # Tabela de movimentacoes via AJAX
```

**Funcionalidades:**
- Cards: Pendentes, Vence Hoje, Vence Amanha, Recebidas, Total, Saldo
- Tabela unificada de recebimentos e despesas
- Filtro por periodo e tesoureiro
- Calculo de balanco (entradas - saidas)

---

### 2. Contas a Receber (Entradas)
```
receber.php                   # Pagina principal (1130 linhas)
receber/
  listar.php                  # Listagem com totalizadores (743 linhas)
  salvar.php                  # Insert/Update de contas
  excluir.php                 # Delete de contas
  baixar.php                  # Baixa individual de conta
  baixar_multiplas.php        # Baixa em lote
  mudar-status.php            # Toggle de status pago/pendente
  parcelar.php                # Criar parcelas de uma conta
  valor_baixar.php            # Calcular total para baixa em lote
  calcular_taxa.php           # Calcular taxa da forma de pagamento
  cobrar.php                  # Enviar cobranca via WhatsApp
  arquivos.php                # Upload de arquivo anexo
  listar-arquivos.php         # Listar arquivos da conta
  excluir-arquivo.php         # Excluir arquivo anexo
  listar-residuos.php         # Listar residuos/parciais de conta
```

**Campos da tabela `receber`:**
- id, descricao, membro, valor, vencimento, data_pgto, data_lanc
- forma_pgto, frequencia, obs, arquivo, pago, referencia, id_ref
- multa, juros, desconto, taxa, subtotal
- usuario_lanc, usuario_pgto, caixa, hora, igreja, hash, residuo

---

### 3. Contas a Pagar (Saidas)
```
pagar.php                     # Pagina principal (1136 linhas)
pagar/
  listar.php                  # Listagem com totalizadores
  salvar.php                  # Insert/Update de contas
  excluir.php                 # Delete de contas
  baixar.php                  # Baixa individual de conta
  baixar_multiplas.php        # Baixa em lote
  mudar-status.php            # Toggle de status
  parcelar.php                # Criar parcelas
  valor_baixar.php            # Calcular total para baixa
  calcular_taxa.php           # Calcular taxa
  arquivos.php                # Upload de arquivo
  listar-arquivos.php         # Listar arquivos
  excluir-arquivo.php         # Excluir arquivo
  listar-residuos.php         # Listar residuos
```

**Campos da tabela `pagar`:**
- id, descricao, fornecedor, valor, vencimento, data_pgto, data_lanc
- forma_pgto, frequencia, obs, arquivo, pago, referencia, id_ref
- multa, juros, desconto, taxa, subtotal
- usuario_lanc, usuario_pgto, caixa, hora, igreja, hash, residuo

---

### 4. Dizimos
```
dizimos.php                   # Pagina principal (145 linhas)
dizimos/
  listar.php                  # Listagem de dizimos
  salvar.php                  # Insert/Update
  excluir.php                 # Delete
```

**Campos da tabela `dizimos`:**
- id, membro, valor, data1, igreja

---

### 5. Ofertas
```
ofertas.php                   # Pagina principal (145 linhas)
ofertas/
  listar.php                  # Listagem de ofertas
  salvar.php                  # Insert/Update
  excluir.php                 # Delete
```

**Campos da tabela `ofertas`:**
- id, membro, valor, data1, igreja

---

### 6. Vendas
```
vendas.php                    # Pagina principal (109 linhas)
vendas/
  listar.php                  # Listagem de vendas
  salvar.php                  # Insert/Update
  excluir.php                 # Delete
```

**Campos da tabela `vendas`:**
- id, descricao, valor, data1, igreja

---

### 7. Fechamentos
```
fechamentos.php               # Pagina principal (93 linhas)
fechamentos/
  listar.php                  # Listagem de fechamentos
  salvar.php                  # Criar fechamento mensal
  excluir.php                 # Delete
```

**Campos da tabela `fechamentos`:**
- id, data_fec, igreja, (totais calculados)

---

## Relatorios PDF (sistema/painel-igreja/rel/)

### Relatorios Financeiros
```
financeiro.php                # Gatilho do relatorio financeiro
financeiro_class.php          # Classe DOMPDF financeiro

receber.php                   # Gatilho relatorio receber
receber_class.php             # Classe DOMPDF receber

pagar.php                     # Gatilho relatorio pagar
pagar_class.php               # Classe DOMPDF pagar

sintetico.php                 # Relatorio sintetico geral
sintetico_class.php           # Classe DOMPDF sintetico

sintetico_recebimentos.php    # Sintetico apenas recebimentos
sintetico_recebimentos_class.php

balanco_anual.php             # Balanco anual
balanco_anual_class.php

caixa.php                     # Relatorio de caixa
caixas_class.php

recibo_conta.php              # Recibo de conta individual
recibo_conta_class.php

imp_recibo.php                # Impressao de recibo 80mm
imp_recibo_pagar.php          # Impressao recibo pagar 80mm

itens.php                     # Relatorio de itens
itens_class.php
```

---

## API Mobile (apiIgreja/)

### Endpoints Financeiros
```
dizimos/
  listar.php                  # GET lista dizimos
  listar_id.php               # GET dizimo por ID
  listar_membros.php          # GET membros para select
  salvar.php                  # POST criar/atualizar
  excluir.php                 # DELETE dizimo

doacoes/
  listar.php                  # GET lista doacoes
  listar_id.php               # GET doacao por ID
  listar_membros.php          # GET membros
  salvar.php                  # POST criar/atualizar
  excluir.php                 # DELETE doacao

ofertas/
  listar.php                  # GET lista ofertas
  listar_id.php               # GET oferta por ID
  listar_membros.php          # GET membros
  salvar.php                  # POST criar/atualizar
  excluir.php                 # DELETE oferta

pagar/
  listar.php                  # GET lista contas a pagar
  listar_id.php               # GET conta por ID
  listar_freq.php             # GET frequencias
  listar_forn.php             # GET fornecedores
  salvar.php                  # POST criar/atualizar
  excluir.php                 # DELETE conta
  baixar.php                  # POST baixar conta
  upload.php                  # POST upload arquivo

receber/
  listar.php                  # GET lista contas a receber
  listar_id.php               # GET conta por ID
  listar_forn.php             # GET fornecedores/membros
  salvar.php                  # POST criar/atualizar
  excluir.php                 # DELETE conta
  baixar.php                  # POST baixar conta

vendas/
  listar.php                  # GET lista vendas
  listar_id.php               # GET venda por ID
  salvar.php                  # POST criar/atualizar
  excluir.php                 # DELETE venda

mov/
  listar.php                  # GET movimentacoes

fornecedores/
  listar.php                  # GET lista fornecedores
  listar_id.php               # GET fornecedor por ID
  buscar.php                  # GET buscar por nome
  salvar.php                  # POST criar/atualizar
  excluir.php                 # DELETE fornecedor
```

---

## Tabelas Auxiliares

```
formas_pgto       # Formas de pagamento (Dinheiro, PIX, Cartao...)
  - id, nome, taxa

frequencias       # Frequencias de recorrencia
  - id, frequencia, dias

fornecedores      # Cadastro de fornecedores
  - id, nome, telefone, endereco, igreja

caixas            # Controle de caixa
  - id, operador, data_abertura, data_fechamento, valor_inicial...
```

---

## Fluxo de Dados

```
[Pagina Principal] -> [AJAX listar.php] -> [Banco de Dados]
                  |
                  +-> [Modal Form] -> [salvar.php] -> [Banco]
                  |
                  +-> [Modal Baixar] -> [baixar.php] -> [Banco]
                  |
                  +-> [Botao PDF] -> [rel/*_class.php] -> [DOMPDF]
```

---

*Estrutura mapeada em 2026-01-23*
