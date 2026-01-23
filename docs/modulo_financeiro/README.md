# Modulo Financeiro - SaaS Igrejas

**Data da Analise:** 2026-01-23
**Versao do Sistema:** v6.05
**Autor:** Analise Automatizada

---

## Sumario Executivo

O modulo financeiro do SaaS Igrejas e responsavel pela gestao completa das financas das igrejas, incluindo contas a receber, contas a pagar, dizimos, ofertas, vendas e fechamentos de caixa.

### Componentes Principais

| Componente | Descricao | Arquivos |
|------------|-----------|----------|
| **Receber** | Contas a receber (entradas) | 14 arquivos |
| **Pagar** | Contas a pagar (saidas) | 13 arquivos |
| **Dizimos** | Registro de dizimos dos membros | 3 arquivos |
| **Ofertas** | Registro de ofertas | 3 arquivos |
| **Vendas** | Registro de vendas | 3 arquivos |
| **Fechamentos** | Fechamento mensal de caixa | 3 arquivos |
| **Financeiro Geral** | Dashboard consolidado | 2 arquivos |
| **Relatorios** | Geracao de PDFs | 12+ arquivos |
| **API** | Endpoints REST para mobile | 30+ arquivos |

### Metricas do Modulo

- **Total de arquivos PHP:** 80+
- **Tabelas do banco:** receber, pagar, dizimos, ofertas, vendas, fechamentos, movimentacoes, formas_pgto, frequencias
- **Funcionalidades:** CRUD completo, baixa de contas, parcelamento, residuos, arquivos anexos, relatorios PDF

---

## Documentacao Disponivel

1. [Estrutura de Arquivos](estrutura.md) - Mapeamento completo de arquivos e pastas
2. [Fluxos e Processos](fluxos.md) - Como o sistema funciona
3. [Analise de Seguranca](seguranca.md) - Vulnerabilidades e riscos identificados
4. [Melhorias Sugeridas](melhorias.md) - Lista de melhorias priorizadas

---

## Status Geral

### Funcionando Corretamente
- CRUD basico de todas as entidades
- Sistema de cards com totalizadores
- Filtros por data e usuario
- Baixa de contas individual e em lote
- Parcelamento de contas
- Upload de arquivos/comprovantes
- Geracao de relatorios PDF
- Integracao WhatsApp (agendamento)

### Problemas Identificados
- Vulnerabilidades de SQL Injection em multiplos arquivos
- Inconsistencia no uso de prepared statements
- Falta de verificar.php em algumas paginas
- Queries sem filtro de igreja em alguns casos
- Cards duplicados (corrigido em financeiro_geral)

### Prioridade de Correcao
1. **CRITICA:** SQL Injection em arquivos de salvamento
2. **ALTA:** Filtros de igreja ausentes
3. **MEDIA:** Padronizar prepared statements
4. **BAIXA:** Melhorias de UX e performance

---

*Documentacao gerada automaticamente para analise do modulo financeiro*
