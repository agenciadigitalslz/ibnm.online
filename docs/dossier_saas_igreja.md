# DOSSIER COMPLETO - SaaS Igreja

**Sistema de Gestao Eclesiastica Multi-Igreja**

---

| Informacao | Detalhe |
|------------|---------|
| **Nome do Sistema** | SaaS Igreja |
| **Versao Atual** | v6.12 |
| **Data do Dossier** | 2026-01-23 |
| **Agencia Desenvolvedora** | Agencia Digital SLZ |
| **Responsavel Tecnico** | Andre Lopes |
| **Contato** | contato@agenciadigitalslz.com.br |
| **Telefone/WhatsApp** | +55 98 9 8741 7250 |
| **Ambiente** | PHP 7.4+ / MySQL / Apache (XAMPP) |

---

## SUMARIO EXECUTIVO

O **SaaS Igreja** e uma plataforma completa de gestao eclesiastica desenvolvida para atender igrejas de todos os portes, desde congregacoes locais ate redes de multiplas igrejas (matriz/filiais). O sistema oferece controle total sobre membros, financas, secretaria, eventos e comunicacao, com paineis diferenciados para administracao central (Sede/Bispo) e operacional (Igrejas locais).

### Diferenciais Competitivos

1. **Multi-Tenancy Nativo** - Isolamento completo de dados por igreja
2. **Paineis Especializados** - Admin (Sede) e Operacional (Igreja local)
3. **5 Niveis de Acesso** - Bispo, Pastor, Tesoureiro, Secretario, Midia
4. **API Mobile** - Integracao com aplicativos externos
5. **Relatorios PDF** - 15+ tipos de relatorios gerenciais
6. **Integracao WhatsApp** - Notificacoes automatizadas (opcional)
7. **Site Publico** - Portal institucional integrado

---

## 1. ARQUITETURA DO SISTEMA

### 1.1 Visao Geral

```
+------------------+     +------------------+     +------------------+
|   SITE PUBLICO   |     |   PAINEL ADMIN   |     |  PAINEL IGREJA   |
|   (Visitantes)   |     |  (Bispo/Sede)    |     | (Pastor/Equipe)  |
+--------+---------+     +--------+---------+     +--------+---------+
         |                        |                        |
         +------------------------+------------------------+
                                  |
                    +-------------+-------------+
                    |      CORE SYSTEM          |
                    |   (conexao.php, auth)     |
                    +-------------+-------------+
                                  |
         +------------------------+------------------------+
         |                        |                        |
+--------+---------+   +----------+----------+   +---------+--------+
|   API MOBILE     |   |   BANCO DE DADOS    |   |   RELATORIOS     |
|  (apiIgreja/)    |   |   (MySQL/MariaDB)   |   |   (DOMPDF)       |
+------------------+   +---------------------+   +------------------+
```

### 1.2 Stack Tecnologico

| Camada | Tecnologia | Versao | Proposito |
|--------|------------|--------|-----------|
| **Backend** | PHP | 7.4+ | Logica de negocio |
| **Banco de Dados** | MySQL/MariaDB | 5.7+ | Persistencia de dados |
| **Acesso BD** | PDO | Nativo | Abstracacao de banco |
| **Frontend Site** | Tailwind CSS | CDN | Estilizacao responsiva |
| **Animacoes** | GSAP | 3.12.2 | Efeitos visuais |
| **Icones** | Font Awesome | 6.4.0 | Biblioteca de icones |
| **JavaScript** | jQuery | 3.6.0 | Manipulacao DOM |
| **Mascaras** | jQuery Mask | 1.14.16 | Formatacao de inputs |
| **Modais** | SweetAlert 2 | Latest | Confirmacoes/alertas |
| **PDF** | DOMPDF | Embedded | Geracao de relatorios |
| **Paineis** | Bootstrap 5 | Implicit | UI administrativa |
| **Tabelas** | DataTables | Integrated | Tabelas interativas |
| **Fontes** | Google Fonts | Poppins | Tipografia |

### 1.3 Estrutura de Diretorios

```
ibnm.online/
|
+-- index.php                    # Redirect para /sede
+-- igreja.php                   # Pagina de detalhe da igreja
+-- igrejas.php                  # Listagem de igrejas
+-- eventos.php                  # Calendario de eventos
+-- cadastrar.php                # Cadastro de membros
+-- membro.php                   # Perfil do membro (stepper 4 etapas)
|
+-- sistema/                     # CORE DO SISTEMA
|   +-- conexao.php              # Conexao PDO + configuracoes globais
|   +-- autenticar.php           # Processo de login
|   +-- logout.php               # Encerramento de sessao
|   +-- logs.php                 # Sistema de auditoria
|   +-- index.php                # Tela de login
|   +-- recuperar-senha.php      # Recuperacao de senha
|   +-- alterar-senha.php        # Alteracao de senha
|   |
|   +-- painel-admin/            # PAINEL ADMINISTRATIVO (SEDE)
|   |   +-- index.php            # Dashboard admin
|   |   +-- verificar.php        # Gate de autenticacao
|   |   +-- verificar_permissoes.php
|   |   +-- paginas/             # 18 modulos administrativos
|   |   +-- rel/                 # Classes de relatorios
|   |   +-- dompdf/              # Biblioteca PDF
|   |   +-- apis/                # APIs administrativas
|   |   +-- css/, js/            # Assets do painel
|   |
|   +-- painel-igreja/           # PAINEL OPERACIONAL (IGREJAS)
|       +-- index.php            # Dashboard igreja
|       +-- verificar.php        # Gate de autenticacao
|       +-- verificar_permissoes.php
|       +-- paginas/             # 45+ modulos operacionais
|       +-- rel/                 # Classes de relatorios
|       +-- dompdf/              # Biblioteca PDF
|       +-- css/, js/            # Assets do painel
|
+-- apiIgreja/                   # API MOBILE/EXTERNA
|   +-- conexao.php              # Conexao da API
|   +-- health.php               # Health check
|   +-- listar_igrejas.php       # Lista igrejas
|   +-- login/                   # Autenticacao
|   +-- dashboard/               # Dados agregados
|   +-- membros/                 # CRUD membros
|   +-- pastores/                # CRUD pastores
|   +-- visitantes/              # CRUD visitantes
|   +-- dizimos/                 # CRUD dizimos
|   +-- ofertas/                 # CRUD ofertas
|   +-- doacoes/                 # CRUD doacoes
|   +-- pagar/                   # Contas a pagar
|   +-- receber/                 # Contas a receber
|   +-- vendas/                  # Vendas
|   +-- pat/                     # Patrimonios
|   +-- tarefas/                 # Tarefas
|   +-- secretaria/              # Celulas e grupos
|   +-- notific/                 # Notificacoes
|   +-- mov/                     # Movimentacoes
|   +-- usuarios/                # Usuarios
|   +-- fornecedores/            # Fornecedores
|
+-- docs/                        # DOCUMENTACAO
|   +-- updates.md               # Changelog (v6.01+)
|   +-- progresso_projeto.md     # Progresso diario
|   +-- planejamentos.md         # Backlog e sprints
|   +-- README.md                # Funcionalidades
|   +-- notificacoes_whatsapp.md # Integracao WhatsApp
|   +-- modulo_financeiro/       # Docs do financeiro
|
+-- backup/                      # Backups SQL
+-- css/                         # Estilos do site publico
+-- Update_v001/ a v004/         # Pacotes de deploy
+-- contexto.md                  # Documentacao tecnica
+-- CLAUDE.md                    # Instrucoes AI
+-- README.md                    # Visao geral
```

---

## 2. SISTEMA DE MULTI-TENANCY

### 2.1 Conceito

O sistema implementa **isolamento completo de dados por igreja** atraves da coluna `igreja` presente em todas as tabelas de negocio. Cada operacao e filtrada pelo `$_SESSION['id_igreja']` do usuario logado.

### 2.2 Implementacao

```php
// Exemplo de query multi-tenant segura
$sql = "SELECT * FROM membros WHERE igreja = ? AND ativo = 'Sim'";
$query = $pdo->prepare($sql);
$query->execute([$_SESSION['id_igreja']]);
```

### 2.3 Regras de Segregacao

| Nivel | id_igreja | Acesso |
|-------|-----------|--------|
| **Bispo** | 0 | Todas as igrejas |
| **Pastor** | > 0 | Apenas sua igreja |
| **Tesoureiro** | > 0 | Apenas sua igreja |
| **Secretario** | > 0 | Apenas sua igreja |
| **Midia** | > 0 | Apenas sua igreja |

---

## 3. SISTEMA DE AUTENTICACAO

### 3.1 Autenticacao Web

**Arquivo:** `sistema/autenticar.php`

```
Fluxo:
1. Usuario submete email + senha
2. Sistema busca usuario por email (prepared statement)
3. Verifica senha com password_verify() contra senha_crip
4. Valida status ativo='Sim'
5. Cria sessao: $_SESSION['id'], ['nome'], ['nivel'], ['id_igreja']
6. Registra log de acesso
7. Redireciona:
   - nivel='Bispo' -> painel-admin
   - outros -> painel-igreja
```

### 3.2 Autenticacao API

**Arquivo:** `apiIgreja/login/login.php`

```
Fluxo (REQUER MIGRACAO):
1. Recebe JSON com email/cpf + senha
2. Busca usuario no banco
3. Compara senha em texto plano (VULNERAVEL)
4. Retorna dados do usuario + igreja

PENDENCIA: Migrar para senha_crip + JWT tokens
```

### 3.3 Variaveis de Sessao

| Variavel | Tipo | Descricao |
|----------|------|-----------|
| `$_SESSION['id']` | int | ID do usuario |
| `$_SESSION['nome']` | string | Nome do usuario |
| `$_SESSION['nivel']` | string | Nivel de acesso |
| `$_SESSION['id_igreja']` | int | ID da igreja (0 = admin) |

---

## 4. SISTEMA DE PERMISSOES

### 4.1 Arquitetura de Permissoes

```
+------------------+
|  grupo_acessos   |  <- Grupos de permissoes (Admin, Pastor, etc)
+--------+---------+
         |
+--------+---------+
|     acessos      |  <- Permissoes individuais (membros, receber, etc)
+--------+---------+
         |
+--------+------------------+
|  usuarios_permissoes      |  <- Vinculo usuario <-> permissao
|  usuarios_permissoes_igreja|  <- Permissoes especificas por igreja
+---------------------------+
```

### 4.2 Niveis de Acesso

| Nivel | Descricao | Escopo |
|-------|-----------|--------|
| **Bispo** | Administrador geral | Multi-igreja, acesso total |
| **Pastor** | Gestor da igreja | Igreja especifica, acesso total |
| **Tesoureiro** | Responsavel financeiro | Igreja especifica, foco financeiro |
| **Secretario** | Responsavel administrativo | Igreja especifica, foco secretaria |
| **Midia** | Responsavel comunicacao | Igreja especifica, sem financeiro |

### 4.3 Permissoes por Modulo (Midia como exemplo)

```php
// verificar_permissoes.php - Papel Midia
$modulos_midia_permitidos = [
    'home_midia', 'membros', 'visitantes', 'eventos',
    'celulas', 'grupos', 'ministerios', 'alertas',
    'versiculos', 'informativos', 'site', 'dados_igreja'
];

$modulos_midia_bloqueados = [
    'receber', 'pagar', 'dizimos', 'ofertas', 'doacoes',
    'movimentacoes', 'fechamentos', 'relatorios_financeiros'
];
```

---

## 5. MODULOS DO SISTEMA

### 5.1 Painel Admin (18 Modulos)

| Modulo | Arquivo | Descricao |
|--------|---------|-----------|
| Home | home.php | Dashboard estatisticas |
| Igrejas | igrejas.php | CRUD de igrejas |
| Usuarios | usuarios.php | Gestao de usuarios |
| Pastores | pastores.php | Pastores multi-igreja |
| Patrimonios | patrimonios.php | Ativos globais |
| Site | site.php | Conteudo publico |
| Tarefas | tarefas.php | Tarefas globais |
| Acessos | acessos.php | Regras de acesso |
| Grupo Acessos | grupo_acessos.php | Grupos de permissao |
| Fornecedores | fornecedores.php | Cadastro fornecedores |
| Anexos | anexos.php | Documentos globais |
| Cargos | cargos.php | Definicao de cargos |
| Perguntas Site | perguntas_site.php | FAQ |
| Recursos Site | recursos_site.php | Downloads |
| Perfil | perfil.php | Dados do admin |
| Config | config.php | Configuracoes gerais |
| Eventos | eventos.php | Eventos globais |
| Relatorios | rel/ | Relatorios PDF |

### 5.2 Painel Igreja (45+ Modulos)

#### Pessoas (8)
| Modulo | Descricao |
|--------|-----------|
| Membros | Cadastro completo de membros |
| Visitantes | Registro de visitantes |
| Aniversariantes | Lista de aniversarios |
| Pastores | Pastores locais |
| Secretarios | Secretarios da igreja |
| Tesoureiros | Tesoureiros da igreja |
| Fornecedores | Fornecedores locais |
| Usuarios | Usuarios com acesso |

#### Cadastros/Config (8)
| Modulo | Descricao |
|--------|-----------|
| Dados Igreja | Configuracoes da igreja |
| Cultos | Horarios de cultos |
| Alertas | Avisos gerais |
| Eventos | Calendario de eventos |
| Versiculos | Versiculos do dia |
| Grupo Acessos | Permissoes locais |
| Acessos | Regras de acesso |
| Informativos | Boletins |

#### Secretaria (6)
| Modulo | Descricao |
|--------|-----------|
| Patrimonios | Inventario de bens |
| Documentos | Gestao documental |
| Anexos | Arquivos anexados |
| Celulas | Grupos de celulas |
| Grupos | Grupos/classes |
| Ministerios | Areas de atuacao |
| Turmas | Cursos/treinamentos |

#### Financeiro (10)
| Modulo | Descricao |
|--------|-----------|
| Visao Geral | Dashboard financeiro unificado |
| Receber | Contas a receber |
| Pagar | Contas a pagar |
| Dizimos | Registro de dizimos |
| Ofertas | Registro de ofertas |
| Doacoes | Controle de doacoes |
| Vendas | Vendas da igreja |
| Missoes Enviadas | Valores enviados |
| Missoes Recebidas | Valores recebidos |
| Caixas | Controle de caixas |
| Movimentacoes | Transacoes financeiras |
| Fechamentos | Fechamentos de periodo |

#### Relatorios (7+)
| Relatorio | Descricao |
|-----------|-----------|
| Membros | Lista de membros |
| Patrimonios | Inventario |
| Financeiro | Balanco geral |
| Balanco Anual | Demonstrativo anual |
| Receber | Detalhamento receber |
| Pagar | Detalhamento pagar |
| Sintetico | Resumos |

---

## 6. BANCO DE DADOS

### 6.1 Estrutura de Tabelas (60+)

#### Seguranca
```sql
usuarios          -- Contas de usuario
acessos           -- Permissoes disponiveis
acessos_igreja    -- Permissoes por igreja
grupo_acessos     -- Grupos de permissao
usuarios_permissoes        -- Usuario <-> Permissao
usuarios_permissoes_igreja -- Usuario <-> Permissao por igreja
logs              -- Auditoria de acoes
token             -- Tokens de sessao
```

#### Organizacao
```sql
igrejas           -- Cadastro de igrejas
config            -- Configuracoes do sistema
cargos            -- Cargos eclesiasticos
frequencias       -- Frequencias de pagamento
categorias        -- Categorias gerais
formas_pgto       -- Formas de pagamento
```

#### Pessoas
```sql
membros           -- Membros da igreja
visitantes        -- Visitantes
pastores          -- Pastores
secretarios       -- Secretarios
tesoureiros       -- Tesoureiros
fornecedores      -- Fornecedores
```

#### Secretaria
```sql
celulas           -- Celulas/pequenos grupos
celulas_membros   -- Membros das celulas
grupos            -- Grupos gerais
grupos_membros    -- Membros dos grupos
ministerios       -- Ministerios
ministerios_membros
turmas            -- Turmas/cursos
turmas_membros
progresso_turma   -- Progresso em cursos
documentos        -- Documentos
anexos            -- Arquivos anexados
versiculos        -- Versiculos
informativos      -- Boletins
itens             -- Itens de inventario
patrimonios       -- Bens patrimoniais
cultos            -- Programacao de cultos
eventos           -- Eventos
```

#### Financeiro
```sql
receber           -- Contas a receber
pagar             -- Contas a pagar
movimentacoes     -- Movimentacoes financeiras
fechamentos       -- Fechamentos de periodo
sangrias          -- Retiradas de caixa
dizimos           -- Registro de dizimos
ofertas           -- Registro de ofertas
doacoes           -- Doacoes recebidas
missoes_recebidas -- Valores de missoes recebidos
missoes_enviadas  -- Valores de missoes enviados
vendas            -- Vendas realizadas
transferencias    -- Transferencias entre contas
```

#### Site Publico
```sql
site              -- Conteudo do site
recursos_site     -- Recursos para download
perguntas_site    -- FAQ
eventos           -- Eventos publicos
```

### 6.2 Diagrama de Relacionamentos Simplificado

```
igrejas (1) ----< (N) membros
igrejas (1) ----< (N) usuarios
igrejas (1) ----< (N) receber
igrejas (1) ----< (N) pagar
igrejas (1) ----< (N) dizimos
igrejas (1) ----< (N) ofertas

membros (1) ----< (N) dizimos
membros (1) ----< (N) celulas_membros
membros (1) ----< (N) grupos_membros

usuarios (1) ----< (N) usuarios_permissoes
usuarios (1) ----< (N) logs
```

---

## 7. API REST-LIKE

### 7.1 Estrutura de Endpoints

**Base URL:** `/apiIgreja/`

#### Autenticacao
```
POST /login/login.php
Body: {"email": "...", "cpf": "...", "senha": "..."}
Response: {"result": [{"id": 1, "nome": "...", "nivel": "...", "igreja": 1}]}
```

#### Health Check
```
GET /health.php
Response: {"status": "ok", "db": "connected"}
```

#### Padrao CRUD por Modulo
```
GET  /<modulo>/listar.php?igreja=1&limite=10&pagina=1
GET  /<modulo>/listar_id.php?id=1&igreja=1
POST /<modulo>/salvar.php
GET  /<modulo>/excluir.php?id=1&igreja=1
GET  /<modulo>/buscar.php?termo=...&igreja=1
POST /<modulo>/upload.php
```

### 7.2 Modulos da API

| Endpoint | Operacoes |
|----------|-----------|
| /membros/ | listar, listar_id, salvar, excluir, upload, buscar, listar-aniv |
| /pastores/ | listar, listar_id, salvar, excluir, upload |
| /visitantes/ | listar, listar_id, salvar, excluir |
| /dizimos/ | listar, salvar, excluir |
| /ofertas/ | listar, salvar, excluir |
| /doacoes/ | listar, salvar, excluir |
| /pagar/ | listar, salvar, excluir |
| /receber/ | listar, salvar, excluir |
| /vendas/ | listar, salvar, excluir |
| /pat/ | listar, salvar, excluir, transf |
| /tarefas/ | listar, salvar, excluir |
| /secretaria/celulas/ | listar-celulas, add_membros, buscar-celulas |
| /secretaria/grupos/ | listar |
| /notific/ | notif, salvar |
| /dashboard/ | ListAllCards |

### 7.3 Formato de Resposta

```json
// Sucesso
{
    "success": true,
    "resultado": [...dados...],
    "totalItems": 100
}

// Erro
{
    "success": false,
    "resultado": "0"
}
```

---

## 8. SISTEMA DE RELATORIOS

### 8.1 Arquitetura

```
Usuario -> View (rel/<nome>.php) -> Form com filtros
                                         |
                                         v
                              Class (rel/<nome>_class.php)
                                         |
                                         v
                                   HTML Template
                                         |
                                         v
                           DOMPDF -> PDF ou HTML Output
```

### 8.2 Relatorios Disponiveis

| Relatorio | Classe | Descricao |
|-----------|--------|-----------|
| Membros | membros_class.php | Lista de membros |
| Patrimonios | patrimonios_class.php | Inventario |
| Financeiro | financeiro_class.php | Balanco geral |
| Balanco Anual | balanco_anual_class.php | Demonstrativo |
| Receber | receber_class.php | Contas a receber |
| Pagar | pagar_class.php | Contas a pagar |
| Sintetico Receber | sintetico_receber_class.php | Resumo |
| Sintetico Despesas | sintetico_despesas_class.php | Resumo |
| Caixas | caixas_class.php | Movimento de caixa |
| Itens | itens_class.php | Inventario itens |
| Ficha Visitante | ficha_visitante_class.php | Formulario |
| Recibos | recibos_class.php | Impressao recibo |
| Excel Clientes | excel_clientes_class.php | Exportacao |

### 8.3 Configuracoes de Relatorio

```php
// sistema/conexao.php
$relatorio_pdf = 'Sim';        // 'Sim' = PDF, 'Nao' = HTML
$cabecalho_rel_img = 'Sim';    // Logo da igreja no cabecalho
$marca_dagua = 'Sim';          // Marca d'agua no documento
$assinatura_recibo = 'Nao';    // Campo de assinatura
```

---

## 9. INTEGRACOES

### 9.1 WhatsApp (Opcional)

**Provedores Suportados:**
- MenuIA
- WordMensagens (WM)
- Newtek

**Configuracao:**
```php
// Tabela config
api_whatsapp = 'Sim'/'Nao'
token_whatsapp = '...'
instancia_whatsapp = '...'
```

**Endpoints:**
- `painel-admin/apis/teste_whatsapp.php` - Teste de envio
- `painel-admin/apis/agendar.php` - Agendamento

**Documentacao:** `docs/notificacoes_whatsapp.md`

### 9.2 Email

**Arquivo:** `painel-admin/apis/disparar_email.php`
- Funcionalidade basica de envio
- Integracao limitada (placeholder)

### 9.3 LGPD/Cookies

**Arquivo:** `assets/js/lgpd-cookie.js`
- Banner de consentimento de cookies
- Conformidade com LGPD brasileira

---

## 10. SEGURANCA

### 10.1 Medidas Implementadas

| Medida | Status | Descricao |
|--------|--------|-----------|
| Hashing de Senha | OK | password_verify() + senha_crip |
| Controle de Sessao | OK | verificar.php em todas as paginas |
| Permissoes Granulares | OK | verificar_permissoes.php |
| Multi-Tenancy | OK | Filtro igreja em todas as queries |
| Auditoria | OK | Logs de todas as acoes |
| Upload Seguro | OK | Validacao MIME + .htaccess |
| Prepared Statements | PARCIAL | 35+ queries convertidas |

### 10.2 Vulnerabilidades Conhecidas

| Vulnerabilidade | Prioridade | Status | Solucao |
|-----------------|------------|--------|---------|
| API usa senha texto | CRITICA | Pendente | Migrar para senha_crip + JWT |
| Credenciais em codigo | ALTA | Pendente | Usar .env + getenv() |
| SQL concat em filtros | MEDIA | Parcial | Converter para prepared |
| Sem CSRF tokens | MEDIA | Pendente | Implementar tokens |
| Sem rate limiting | MEDIA | Pendente | Middleware na API |

### 10.3 Recomendacoes de Seguranca

1. **Migrar Autenticacao API**
   - Substituir `usuarios.senha` por `usuarios.senha_crip`
   - Implementar JWT tokens
   - Adicionar rate limiting

2. **Proteger Credenciais**
   - Mover para arquivo `.env`
   - Usar `getenv()` em conexao.php
   - Adicionar `.env` ao `.gitignore`

3. **Validacao de Entrada**
   - Sanitizar todos os `$_POST/$_GET`
   - Usar `filter_input()` consistentemente
   - Escapar saidas HTML

4. **Protecao de Uploads**
   - Validar MIME type real (finfo)
   - Limitar extensoes permitidas
   - Bloquear execucao PHP em pastas de upload

---

## 11. CONFIGURACOES DO SISTEMA

### 11.1 Variaveis Globais (conexao.php)

```php
// Banco de Dados
$banco = "ibnm.online";
$servidor = "localhost";
$usuario = "root";
$senha = "";

// Sistema
$nome_sistema = "Nome da Igreja";
$email_super_adm = "contato@...";
$telefone_igreja_sistema = '(00) 00000-0000';
$endereco_igreja_sistema = 'Rua A, Numero 150...';

// Funcionalidades
$verso_carteirinha = 'Sim';      // Verso da carteirinha
$quantidade_tarefas = 20;        // Tarefas no dashboard
$limitar_tesoureiro = 'Sim';     // Restringe tesoureiro
$relatorio_pdf = 'Sim';          // Gera PDF ou HTML
$cabecalho_rel_img = 'Sim';      // Logo nos relatorios
$itens_por_pagina = 9;           // Paginacao
$logs = 'Sim';                   // Auditoria ativa
$dias_excluir_logs = 40;         // Retencao de logs

// Financeiro
$multa_atraso = '0';             // Multa %
$juros_atraso = '0';             // Juros %

// Integracoes
$api_whatsapp = 'Nao';
$token_whatsapp = '';
$instancia_whatsapp = '';
```

### 11.2 Tabela config

| Campo | Tipo | Descricao |
|-------|------|-----------|
| nome | varchar | Nome da organizacao |
| email | varchar | Email principal |
| telefone | varchar | Telefone |
| endereco | text | Endereco completo |
| qtd_tarefas | int | Tarefas no dashboard |
| limitar_tesoureiro | enum | Sim/Nao |
| relatorio_pdf | enum | Sim/Nao |
| cabecalho_rel_img | enum | Sim/Nao |
| itens_por_pagina | int | Paginacao |
| logs | enum | Sim/Nao |
| multa_atraso | decimal | % multa |
| juros_atraso | decimal | % juros |
| dias_excluir_logs | int | Dias para limpar |
| marca_dagua | enum | Sim/Nao |
| assinatura_recibo | enum | Sim/Nao |
| impressao_automatica | enum | Sim/Nao |
| api_whatsapp | enum | Sim/Nao |
| token_whatsapp | varchar | Token API |
| instancia_whatsapp | varchar | Instancia |
| alterar_acessos | enum | Sim/Nao |
| dados_pagamento | text | Config pagamento |
| mostrar_acessos | enum | Sim/Nao |
| logo | varchar | Logo principal |
| logo_rel | varchar | Logo relatorios |
| icone | varchar | Favicon |
| ativo | enum | Sim/Nao |

---

## 12. METRICAS DO PROJETO

### 12.1 Estatisticas de Codigo

| Metrica | Valor |
|---------|-------|
| **Arquivos PHP** | 814+ |
| **Linhas de Codigo** | ~50.000+ |
| **Modulos Admin** | 18 |
| **Modulos Igreja** | 45+ |
| **Endpoints API** | 60+ |
| **Tabelas BD** | 60+ |
| **Relatorios PDF** | 15+ |
| **Arquivos Doc** | 56+ |

### 12.2 Tamanho do Projeto

| Componente | Tamanho |
|------------|---------|
| Total | ~1.1 GB |
| DOMPDF (2x) | ~900 MB |
| Codigo PHP | ~50 MB |
| Assets (CSS/JS/Img) | ~100 MB |
| Documentacao | ~5 MB |

---

## 13. HISTORICO DE VERSOES

### 13.1 Linha do Tempo

| Versao | Data | Destaques |
|--------|------|-----------|
| v6.01 | 2025-08-02 | Layout 3.0, padronizacao visual |
| v6.02 | 2025-08-03 | Responsividade mobile, lazy loading |
| v6.03 | 2025-08-04 | Stepper cadastro membros (4 etapas) |
| v6.04 | 2025-08-05 | Responsividade consolidada, rotas |
| v6.05 | 2025-08-07 | Correcao conexao API, healthchecks |
| v6.06 | 2025-08-08 | Nivel "Midia", dashboard dedicado |
| v6.07 | 2025-08-09 | Visao Geral financeiro unificada |
| v6.08 | 2025-08-10 | PDFs com "Lancado por", filtros |
| v6.09 | 2025-08-11 | Tempo real na Visao Geral, responsividade |
| v6.10 | 2025-08-11 | Documentacao WhatsApp |
| v6.12 | 2025-08-13 | Padronizacao @igreja/ |

### 13.2 Updates do Modulo Financeiro (2026)

| Update | Tipo | Arquivos | Destaques |
|--------|------|----------|-----------|
| v001 | Bugfix | 1 | Cards duplicados corrigidos |
| v002 | Security | 6 | verificar.php, prepared statements |
| v003 | Security+Perf | 4 | MIME validation, SUM() optimization |
| v004 | Refactor | 9 | CSS/JS centralizados, charset UTF-8, notificacoes dashboard |

---

## 14. PROCEDIMENTOS OPERACIONAIS

### 14.1 Deploy

1. **Backup Completo**
   ```bash
   # Banco de dados
   mysqldump -u root ibnm.online > backup_YYYYMMDD.sql

   # Arquivos
   tar -czvf backup_files_YYYYMMDD.tar.gz sistema/img/
   ```

2. **Aplicar Updates**
   - Fazer upload dos arquivos modificados
   - Executar SQLs de alteracao (se houver)
   - Limpar cache do navegador

3. **Validacao**
   - Testar login (admin e igreja)
   - Verificar modulos criticos (financeiro)
   - Testar relatorios PDF
   - Verificar uploads

### 14.2 Manutencao

| Tarefa | Frequencia | Comando/Acao |
|--------|------------|--------------|
| Limpar logs | Automatico | config.dias_excluir_logs |
| Backup BD | Diario | mysqldump |
| Backup uploads | Semanal | tar sistema/img/ |
| Verificar sessoes | Mensal | Limpar token antigos |
| Atualizar PHP | Anual | Testar compatibilidade |

### 14.3 Troubleshooting

| Problema | Solucao |
|----------|---------|
| Login falha | Verificar senha_crip, ativo='Sim' |
| Pagina sem acesso | Checar verificar_permissoes.php |
| PDF em branco | Verificar $relatorio_pdf, pasta dompdf |
| Upload falha | Checar permissoes pasta, extensoes |
| API vazia | Verificar php://input, JSON valido |
| Charset errado | Confirmar charset=utf8mb4 na conexao |

---

## 15. ROADMAP FUTURO

### 15.1 Melhorias Planejadas

| Prioridade | Item | Descricao |
|------------|------|-----------|
| CRITICA | JWT na API | Autenticacao segura mobile |
| ALTA | .env | Credenciais fora do codigo |
| ALTA | Prepared 100% | Todas queries parametrizadas |
| MEDIA | CSRF Tokens | Protecao de formularios |
| MEDIA | Rate Limiting | Protecao contra abuso |
| MEDIA | Dark Mode | Tema escuro nos paineis |
| BAIXA | PWA | App offline-first |
| BAIXA | Dashboard Analytics | Graficos interativos |

### 15.2 Funcionalidades Sugeridas

- **Soft Delete** - Recuperacao de registros excluidos
- **Auditoria Detalhada** - Log de todas as alteracoes em campos
- **Cache de Totalizadores** - Performance para igrejas grandes
- **Graficos Chart.js** - Dashboard visual
- **Exportacao Excel** - Relatorios em XLSX
- **App Mobile Nativo** - Flutter/React Native
- **Integracao PIX** - Pagamentos automaticos
- **Backup Automatico** - Rotina agendada

---

## 16. CONTATOS E SUPORTE

### 16.1 Equipe de Desenvolvimento

| Papel | Nome | Contato |
|-------|------|---------|
| **Desenvolvedor Principal** | Andre Lopes | contato@agenciadigitalslz.com.br |
| **WhatsApp** | Suporte | +55 98 9 8741 7250 |
| **Agencia** | Agencia Digital SLZ | agenciadigitalslz.com.br |

### 16.2 Documentacao de Referencia

| Documento | Localizacao | Proposito |
|-----------|-------------|-----------|
| README.md | Raiz | Visao geral |
| contexto.md | Raiz | Detalhes tecnicos |
| CLAUDE.md | Raiz | Instrucoes AI |
| docs/updates.md | docs/ | Changelog |
| docs/progresso_projeto.md | docs/ | Avancos diarios |
| docs/planejamentos.md | docs/ | Backlog |

---

## 17. ANEXOS

### 17.1 Checklist de Desenvolvimento

- [ ] Contexto analisado (contexto.md)
- [ ] Escopo id_igreja validado
- [ ] Permissoes verificadas
- [ ] Queries com prepared statements
- [ ] Uploads validados (tipo, tamanho, MIME)
- [ ] Documentacao atualizada (docs/updates.md)
- [ ] Logs de debug removidos
- [ ] Testes executados
- [ ] Commit semantico sugerido

### 17.2 Padrao de Commits

```bash
feat(modulo): adiciona nova funcionalidade
fix(modulo): corrige bug especifico
docs(readme): atualiza documentacao
refactor(api): melhora estrutura do codigo
security(auth): implementa medida de seguranca
style(ui): ajusta visual/responsividade
test(modulo): adiciona testes
```

### 17.3 Comandos Uteis

```bash
# Backup banco
mysqldump -u root -p ibnm.online > backup.sql

# Restaurar banco
mysql -u root -p ibnm.online < backup.sql

# Verificar sintaxe PHP
php -l arquivo.php

# Permissoes de upload (Linux)
chmod -R 755 sistema/img/

# Verificar conexao
php -r "new PDO('mysql:host=localhost;dbname=ibnm.online', 'root', '');"
```

---

**FIM DO DOSSIER**

*Documento gerado em 2026-01-23*
*SaaS Igreja - Sistema de Gestao Eclesiastica*
*Versao do Dossier: 1.0*
