# Contexto Técnico — SaaS Igreja v7.40

Documento de referência para manutenção, implementação de novas features e correções. Use este guia antes de alterar o sistema.

- Agência: Agência Digital — agenciadigitalslz.com.br
- Responsável: André Lopes
- Contato: contato@agenciadigitalslz.com.br | Tel/WhatsApp: +55 98 9 8741 7250

## 1. Arquitetura Geral
- PHP procedural com organização por pastas de domínio.
- Banco MySQL/MariaDB com forte segregação por `igreja`.
- Dois painéis: `sistema/painel-admin/` (Sede) e `sistema/painel-igreja/` (Operacional).
- Site público em `@igreja/` com Tailwind/GSAP.
- APIs para integração móvel em `apiIgreja/` (JSON).
- Relatórios com Dompdf; alternância PDF/HTML por `config.relatorio_pdf`.

### 1.1 Entradas Globais
- `sistema/conexao.php`: conexão PDO, URLs base, bootstrap da tabela `config`, flags de sistema.
- Sessões: `$_SESSION['id']`, `$_SESSION['nivel']`, `$_SESSION['id_igreja']` controlam acesso.
- Gate genérico: `verificar.php` (bloqueia não autenticados) + `verificar_permissoes.php` (bloqueia sem permissão).

## 2. Mapa das Pastas
- `@igreja/` — front público (`index.php`, `igreja.php`, `igrejas.php`, `eventos.php`), assets do site.
- `@igreja/sistema/`
  - `index.php` (login), `autenticar.php` (processa login), `logout.php`.
  - `conexao.php` (DB, URLs, config), `logs.php` (auditoria), `assets/` (tema), `img/` (uploads).
  - `painel-igreja/`
    - `index.php` (layout + roteamento via `paginas/<mod>.php`), `verificar.php`, `verificar_permissoes.php`, `dompdf/`.
    - `paginas/` (cada módulo com `listar.php`, `salvar.php`, `excluir.php` e variações).
  - `painel-admin/`
    - Estrutura similar ao painel-igreja, mas com escopo multi-igrejas e módulos da Sede.
- `apiIgreja/` — endpoints REST-like (procedurais) por módulo.
- `@igreja/SQL/novo_igreja.sql` — schema v6.0 oficial.

## 3. Fluxos Críticos
### 3.1 Autenticação Web
- Login em `sistema/index.php` → `sistema/autenticar.php`.
- Verificação de senha via `password_verify` contra `usuarios.senha_crip`.
- Set de sessão: `nome`, `id`, `nivel`, `id_igreja`.
- Redirecionamento: `igreja==0` → `painel-admin/`; senão `painel-igreja/`.
- Logout: `sistema/logout.php` (log + destruir sessão).

### 3.2 Autenticação API (App)
- `apiIgreja/login/login.php` compara `usuarios.senha` em texto.
- Recomenda-se alinhar com `senha_crip` (hash) e migrar para tokens (JWT ou similar).

### 3.3 Permissões
- `verificar.php` exige `$_SESSION['id']`.
- `verificar_permissoes.php` confere permissões de módulo/ação via `grupo_acessos`, `acessos`, `usuarios_permissoes(_igreja)`.
- Para liberar novo módulo, criar entrada em `acessos` e vinculá-la a `grupo_acessos`/`usuarios_permissoes`.

### 3.4 Relatórios
- Classes `*_class.php` em `rel/` geram HTML e renderizam no Dompdf.
- `config.relatorio_pdf` controla se baixa PDF ou exibe HTML.

## 4. Padrões de Módulos (CRUD)
Cada módulo no painel segue padrão:
- `paginas/<modulo>.php` (view/listagem e modais) chama via AJAX os scripts:
  - `paginas/<modulo>/listar.php` — paginação/filtros
  - `paginas/<modulo>/salvar.php` — insert/update (validar `igreja` e dados obrigatórios)
  - `paginas/<modulo>/excluir.php` — exclusão lógica/física
  - Demais: `listar_id.php`, `mudar-status.php`, `upload.php`, etc.
- Convenções comuns:
  - Validar/ler `$_SESSION['id_igreja']` para escopo.
  - Usar `prepare`/bindValue; evitar concatenação direta.
  - Responder mensagens claras ("Salvo", "Editado", erros).

## 5. Banco de Dados
- Importar `SQL/novo_igreja.sql` para iniciar.
- Chaves de segregação: coluna `igreja` (inteiro) em módulos de negócio.
- Tabelas-chave:
  - Segurança: `usuarios`, `acessos`, `grupo_acessos`, `usuarios_permissoes`, `usuarios_permissoes_igreja`, `logs`, `token`
  - Cadastros: `igrejas`, `cargos`, `frequencias`, `categorias`, `formas_pgto`
  - Pessoas: `membros`, `visitantes`, `pastores`, `secretarios`, `tesoureiros`, `fornecedores`
  - Secretaria: `celulas`, `celulas_membros`, `grupos`, `grupos_membros`, `ministerios`, `ministerios_membros`, `turmas`, `turmas_membros`, `progresso_turma`, `documentos`, `anexos`, `versiculos`, `informativos`, `itens`, `patrimonios`
  - Financeiro: `receber`, `pagar`, `movimentacoes`, `fechamentos`, `sangrias`, `dizimos`, `ofertas`, `doacoes`, `missoes_recebidas`, `missoes_enviadas`, `vendas`, `transferencias`
  - Site: `site`, `recursos_site`, `perguntas_site`, `eventos`
  - Config: `config`

## 6. Guia de Atualizações
### 6.1 Passos Seguros
1) Criar branch de feature.
2) Mapear impacto (módulos afetados, tabelas, permissões, relatórios).
3) Alterar front (views) e endpoints (`listar.php`, `salvar.php`, `excluir.php`).
4) Ajustar permissões (se novo módulo/ação).
5) Adicionar colunas/índices no SQL (nova migration/ALTER) e documentar.
6) Testar em ambiente de dev com base limpa e base real (cópia anonimiz.)
7) Atualizar `contexto.md` (se padrões mudarem) e `README` (se recursos mudarem).

### 6.2 Checklists por Domínio
- Segurança
  - Sanitizar todos os `$_POST/$_GET` (filter_input, regex).
  - Prepared statements em todas as queries.
  - Evitar eco de dados sem escape em HTML.
  - Revisar uploads (extensão/size/mime, bloqueio execução).
- Sessão/Permissões
  - Usar `verificar.php` em todas as páginas internas.
  - Verificar `verificar_permissoes.php` para novos módulos/ações.
- Financeiro
  - Validar cálculos (juros/multa/parcelas), uso de datas e timezone.
  - Geração de comprovantes/relatórios.
- Site
  - Limitar HTML permitido em descrições (FAQ/recursos/eventos).
  - Otimizar imagens (dimensões/tamanho) e responsividade.
- APIs
  - Unificar autenticação com hash de senha e token.
  - Definir versão (`/v1/`) e políticas de rate-limit.

## 7. Pontos de Atenção (Dívidas Técnicas)
- `apiIgreja/login/login.php` usa `usuarios.senha` (texto). Planejar migração para `senha_crip` com `password_verify` e tokens.
- `sistema/conexao.php` contém credenciais em texto: mover para variáveis de ambiente e `.env` (fora do VCS) e consumir via `getenv()`.
- Revisar queries com concatenação de variáveis em módulos (especialmente filtros de listagem) e trocar por `prepare`.
- Implementar CSRF tokens nas ações sensíveis de formulário.
- Restringir execução em diretórios de upload (e.g., deny scripts via `.htaccess`/headers Nginx).

## 8. Extensões e Integrações
- WhatsApp (opcional): `menuia`, `wm` (WordMensagens), `newtek` — configurável na UI. Endpoint de teste: `painel-admin/apis/teste_whatsapp.php`.
- PDF: Dompdf já incluso (não requer composer). Caso deseje migrar para composer, mover vendor para raiz e padronizar autoload.

## 9. Padrões de Código
- Nomeação clara de variáveis (sem abreviações obscuras).
- Funções pequenas, early-returns, tratamento de erros próximo à origem.
- Comentários curtos explicando o “porquê” (não o óbvio “como”).
- Evitar duplicação: fatorar helpers quando detectar padrões repetidos.

## 10. Procedimentos Operacionais
- Backup: banco + uploads (`sistema/img/**`) antes de atualizar.
- Logs: manter `config.logs = 'Sim'` em produção; rodar limpeza por `config.dias_excluir_logs`.
- Auditoria: use os relatórios de `logs`, `fechamentos`, `financeiro` para validação pós-deploy.

## 11. Como Adicionar um Novo Módulo
1) Criar pasta em `paginas/<modulo>/` com `listar.php`, `salvar.php`, `excluir.php`.
2) Adicionar entrada em `paginas/<modulo>.php` (view/menu/modal) e configurar rota em `index.php` via `?pagina=<modulo>`.
3) Criar tabela ou colunas no banco (ALTER/Migration) — manter compatibilidade com `igreja`.
4) Cadastrar `acessos` e vincular a `grupo_acessos`/`usuarios_permissoes`.
5) Criar relatório (opcional) em `rel/` e configurar Dompdf.
6) Testar permissões com perfis diferentes.

## 12. Troubleshooting Rápido
- Login não funciona: ver `sistema/autenticar.php`, sessões, `usuarios.senha_crip` e `ativo='Sim'`.
- Sem acesso a página: conferir `verificar.php` e mapeamento em `verificar_permissoes.php`.
- PDF em branco: checar `relatorio_pdf` e pasta `dompdf/` + permissões.
- Upload falha: checar permissões de pasta e validação de extensões.
- API retorna vazio: inspecionar `php://input`, JSON válido e queries do módulo.

---

## 📚 Documentação Prioritária para Entendimento do Projeto

**IMPORTANTE:** Quando solicitado "Leia o contexto.md do projeto para entender", consulte obrigatoriamente TODOS os documentos abaixo na ordem apresentada:

### 1. 📋 **README.md** (Visão Geral Completa)
- **Propósito:** Entendimento geral do sistema e suas funcionalidades
- **Conteúdo:** Arquitetura, módulos, níveis de acesso, recursos técnicos
- **Quando ler:** SEMPRE primeiro para ter visão macro do projeto
- **Localização:** Raiz do projeto (`/README.md`)

### 2. 📈 **docs/progresso_projeto.md** (Estado Atual)
- **Propósito:** Avanços diários e implementações recentes
- **Conteúdo:** Últimas alterações, versões, melhorias implementadas
- **Quando ler:** Após README para entender o que foi feito recentemente
- **Localização:** `docs/progresso_projeto.md`

### 3. 📝 **docs/updates.md** (Changelog Detalhado)
- **Propósito:** Histórico completo de versões e mudanças
- **Conteúdo:** Versionamento v6.x, correções, novidades, melhorias técnicas
- **Quando ler:** Para entender evolução completa do sistema
- **Localização:** `docs/updates.md`

### 🎯 Ordem de Leitura Obrigatória

```bash
1º → README.md           # Visão macro e funcionalidades
2º → progresso_projeto.md # Estado atual e últimas implementações  
3º → updates.md          # Histórico completo de mudanças
4º → contexto.md         # Detalhes técnicos (este documento)
```

### 📖 Próximas Adições Planejadas

Futuramente serão incluídos na leitura inicial:
- `docs/arquitetura.md` - Detalhamento da arquitetura técnica
- `docs/seguranca.md` - Protocolos de segurança específicos
- `docs/api.md` - Documentação completa da API
- `docs/deploy.md` - Procedimentos de deploy e produção

### ⚡ Regras de Leitura

**🟢 SEMPRE:**
- Ler README.md primeiro para visão geral
- Consultar progresso_projeto.md para estado atual
- Verificar updates.md para entender mudanças recentes
- Usar contexto.md para detalhes técnicos específicos

**🔴 NUNCA:**
- Pular a leitura do README.md
- Ignorar o progresso atual do projeto
- Assumir funcionalidades sem verificar a documentação
- Trabalhar sem entender o contexto completo

---
---

## 8. Security Hardening — Defense in Depth (v7.00→v7.30)

**Status:** ✅ COMPLETO (4 Sprints — 10/02/2026)
**Score:** Enterprise Grade

### Sprints Concluídos
| Sprint | Versão | Entregas Principais |
|--------|--------|---------------------|
| Sprint 1 | v7.00 | SQL Injection fix (PDO), `.env` (credenciais isoladas), `password_verify` (bcrypt), Apache hardening |
| Sprint 2 | v7.10 | Honeypot, `RateLimiter.php`, `SessionFingerprint.php`, CSP/OWASP headers |
| Sprint 3 | v7.20 | JWT library, `JWTManager.php`, `refresh_tokens` table, auth endpoints (token/logout/delete) |
| Sprint 4 | v7.30 | `SecurityLogger.php` (13 eventos, 4 severidades), `Encryptor.php` (AES-256-CBC), `AlertManager.php`, LGPD delete-account, Dashboard de Segurança (Chart.js), 34/34 testes |

### Camadas de Defesa
```
1. Apache Hardening (.htaccess)
2. Honeypot (campo website_url)
3. RateLimiter (IP tracking via failed_logins)
4. CSP + OWASP Headers
5. SessionFingerprint (hash IP+UA)
6. password_verify (bcrypt)
7. JWT Authentication (access + refresh tokens)
8. SecurityLogger (auditoria avançada)
9. Encryptor AES-256-CBC (CPF, dados sensíveis)
10. AlertManager (thresholds automáticos)
```

### Arquivos de Segurança
- `sistema/helpers/`: RateLimiter, SessionFingerprint, SecurityLogger, Encryptor, AlertManager, JWTManager
- `sistema/painel-admin/paginas/seguranca.php`: Dashboard de segurança
- `apiIgreja/auth/`: token.php, logout.php, delete-account.php
- `apiIgreja/admin/estatisticas-seguranca.php`: API de métricas
- Relatórios: `SPRINT_1_RELATORIO_FINAL.md` até `SPRINT_4_PLANEJAMENTO.md`

### Testes
- **34/34 testes** passando (Sprint 4)
- Script: `teste_sprint4_completo.php`

---
Este documento é vivo: ao alterar padrões, atualizar aqui. Para visão de produto (módulos/recursos), veja `README.md`.
