# Changelog — SaaS Igrejas (v6.x)

Este documento registra mudanças contínuas do projeto. Padrão de versionamento local: v6.01+ (duas casas decimais). Datas em ISO (AAAA-MM-DD).

## v6.09 — 2025-08-11

### Melhorias
- Visão Geral (Financeiro): atualização em tempo real (sem botão). Ao alterar data ou tesoureiro, os cards e a lista unificada recarregam via AJAX.
- Responsividade: números dos cards com auto-shrink quando >= 7 dígitos, evitando quebra/descida desnecessária.
- UI filtros: largura dos campos de data em Receber/Pagar ajustada para exibir o ano completo.

### Técnicos
- Criado `sistema/painel-igreja/paginas/financeiro_geral/listar.php` para consolidar cards e lista unificada via POST (dataInicial, dataFinal, usuario_lanc), respeitando “mostrar só meus registros”.
- `sistema/painel-igreja/paginas/financeiro_geral.php` agora injeta `#listar` e faz chamadas AJAX em tempo real ao alterar filtros.
- `sistema/painel-igreja/js/ajax.js`: executa scripts embutidos após `#listar`.html(), garantindo rehidratação (DataTables/cards) após cada busca.
- `sistema/painel-igreja/paginas/pagar/listar.php`: correção do filtro por período no else final para usar `$tipo_data <= $dataFinal` (antes usava campo fixo e abrangia o ano todo).

### Segurança
- Somatórios e listagens da Visão Geral com consultas preparadas (PDO + parâmetros), incluindo filtro por lançador quando selecionado.

---

## v6.08 — 2025-08-10

### Novidades
- PDFs (Receber e Pagar):
  - Inclusa coluna “Lançado por” nas tabelas.
  - Cabeçalhos exibem “Lançado por: NOME” quando filtrado por usuário.
- Filtro por Tesoureiro (PDF): campos adicionados nos formulários para gerar relatórios filtrados por lançador.

### Mudanças
- Receber/Pagar (telas): removido o filtro de tesoureiro visual para não impactar a experiência diária; mantido apenas na Visão Geral e nos PDFs.

### Técnicos
- `sistema/painel-igreja/rel/receber_class.php` e `rel/pagar_class.php`: passam `usuario_lanc` sanitizado para os relatórios.
- `sistema/painel-igreja/rel/receber.php`, `rel/pagar.php` e `rel/financeiro.php`:
  - Ajustes de consultas para considerar o filtro de lançador quando informado.
  - Adição das colunas e textos de cabeçalho referentes ao lançador.

---

## v6.07 — 2025-08-09

### Novidades
- Financeiro: criada página “Visão Geral” unificando Recebimentos e Despesas (cards e tabela unificada por período).

### UI/UX
- Topo da página com botões Recebimentos/Despesas e filtros de data alinhados.
- Rota amigável habilitada para `financeiro_geral` via `.htaccess` do painel da igreja.

### Técnicos
- `sistema/painel-igreja/paginas/financeiro_geral.php` criado.
- Ajustes de menu para incluir “Visão Geral” como primeira opção do Financeiro.

---

## v6.06 — 2025-08-08

### Novidades
- Nível de acesso “Mídia”
  - Dashboard dedicado em `sistema/painel-igreja/paginas/home_midia/`.
  - Permissões granulares no `verificar_permissoes.php` (oculta dados financeiros e relatórios).

### UI/UX
- Ajustes de layout em tabelas (botões de ação em linha, com rolagem horizontal quando necessário).

---

## v6.05 — 2025-08-07

### Correções e Saúde do Sistema
- API (conexão PDO): correção do DSN (uso de `$servidor`), evitando falhas de conexão.
- Healthchecks criados para verificação de conectividade a BD (ambiente de testes), retornando status e tempos.

### Técnicos
- Ajustes em `conexao.php` para maior robustez local (ex.: `127.0.0.1` vs `localhost`) e tratamento de erros para impedir chamadas em `$pdo` nulo.

---

## v6.04 — 2025-08-05

### Frontend/UX
- Responsividade consolidada das páginas públicas (`index.php`, `igreja.php`, `eventos.php`, `igrejas.php`): revisão de grids, espaçamentos e tamanhos mínimos de toque; consolidação dos ajustes anteriores.
- Ícones de redes sociais (Instagram, Facebook, YouTube) padronizados para versão “ícone-only” no mobile; rótulos exibidos em md+.

### Infra/Rotas
- Redirecionamento raiz → `/sede` em `@igreja/index.php`.

### Documentação
- Criados documentos-chave: `docs/updates.md`, `docs/planejamentos.md`, `docs/progresso_projeto.md`.

---

## v6.03 — 2025-08-04

### Novidades
- `membro.php`: migração para stepper (4 etapas) com melhorias de UX (navegação, botões “Anterior/Próximo”, estado de envio).

### Frontend
- Refinos de layout e acessibilidade (contraste, foco visível, tamanho de hit area).

---

## v6.02 — 2025-08-03

### Frontend
- Correção de overflow horizontal no mobile e ajuste de viewport com `100dvh`.
- Iframes responsivos com classe `responsive-embed` (página e modal).
- Imagens principais com `loading="lazy"` e `decoding="async"` (carrossel/listas) — melhoria de LCP.
- Ícone WhatsApp com safe-area (`.whats-fixed`, `bottom: calc(20px + env(safe-area-inset-bottom))`) para evitar sobreposição de CTAs.
- Ajustes gerais nos CTAs/carrosséis: setas com área de toque ampliada; variações hover, sombras e transições consistentes.

---

## v6.01 — 2025-08-02

### Base visual 3.0
- Padronização do layout 3.0 nos componentes públicos (hero, cards, CTAs, modais) e aplicação de utilitários de tema.
- Adequação de links e rotas públicas (`index.php`, `igreja.php`, `igrejas.php`) ao novo esquema de navegação.

---

## Itens incorporados do update_novo.md (curadoria)

- Dashboard “Mídia” (home_midia) e permissões específicas do papel “Mídia”.
- Financeiro — Visão Geral com consultas parametrizadas e filtros por período/usuário.
- Melhorias de responsividade em cards financeiros e páginas do painel.
- Fortalecimento de segurança: uso de consultas parametrizadas, escape de saídas e reforço de regras de acesso.

Notas: Foram ignorados itens genéricos/incompatíveis com o escopo atual (ex.: alegação de MVC completo ou API REST ampla), mantendo apenas o que foi de fato implementado neste repositório.

---

## Próximos passos sugeridos
- Padronizar leitura de credenciais por variáveis de ambiente.
- JWT + rate-limit na API móvel; unificação de autenticação com `password_verify`.
- CSRF em formulários sensíveis e reforço em uploads.
- Revisão de SQLs antigos para prepared statements em 100% dos módulos.

## v6.10 — 2025-08-11

### Documentação
- Criado `docs/notificacoes_whatsapp.md`: catálogo de notificações WhatsApp (Menuia/Wm/NewTek) com gatilhos, templates, endpoints e locais de alteração para manutenção e futuras expansões.

## v6.12 — 2025-08-13

### Documentação
- Padronizado diretório oficial do projeto de `ibnm.online` para `@igreja/` em todos os documentos: `agent.md`, `CLAUDE.md`, `contexto.md`, `.cursor/rules/rules.mdc`, `docs/progresso_projeto.md`, `docs/responsividade.md`, `docs/updates.md`.
- Ajustado “Mapa das Pastas” em `contexto.md` para `@igreja/sistema/` e `@igreja/SQL/novo_igreja.sql`.
- Atualizado `docs/updates.md` (v6.04) para referenciar `@igreja/index.php`.

## v6.11 — 2025-08-12

### Planejamento
- `docs/planejamentos.md`: adicionada seção "Plano — Notificações WhatsApp (Aprovadas) v1" com escopo, fluxos e templates base alinhados a `docs/sobre_ibnm.md`.

- `apiIgreja/notific/script.php`: adicionada rotina de Aniversariantes 06:00 com fallback ao Super Admin e modo de teste via `?teste=1` (envia para 98 98741-7250).
- `apiIgreja/notific/script.php`: compatibilidade adicionada quando coluna `config.notificacao` não existir (usa rotina de horário apenas e ignora ciclo 1..4).