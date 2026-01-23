---
Tipo: analise
Data: 2025-08-09
Autor: André Lopes
Status: ativo
---

# Planejamentos — SaaS Igrejas

Documento vivo para planejamento de sprints e blocos de trabalho. Manter enxuto, priorizando o próximo incremento (2-3 itens).

## Backlog imediato (próximo incremento)
- Unificar autenticação da API (`apiIgreja/login/login.php`) com `senha_crip` + `password_verify` e preparar tokens
- Revisar queries com concatenação direta nos módulos mais acessados e migrar para prepared statements
- Criar templates básicos em `docs/README.md` (setup e visão geral)
- Responsividade (Lote 2): remover JS de resize de logos; prefers-reduced-motion; bullets dinâmicos no carrossel

## Alvos da Sprint atual
- Governança de documentação: consolidar documentos-chave (updates, planejamentos, progresso)
- Ajustes de UX no site público (cta, contraste e acessibilidade)
- Migrar `membro.php` para o padrão visual (Stepper multi-etapas)

## Critérios de aceite
- Segurança validada (permissões e escopo `id_igreja`)
- Documentos `updates.md` e `progresso_projeto.md` atualizados
- Sem logs de depuração em produção

## Plano — Notificações WhatsApp (Aprovadas) v1

Aprovadas:
- Aniversariantes automático (06:00)
- Novos conteúdos (eventos, cultos, notícias)
- Boas‑vindas a novos membros (após cadastro/integração)

Observação: item duplicado “Boas‑vindas” consolidado acima para evitar redundância.

### Diretrizes transversais
- Segurança: consultas preparadas, sanitização de telefones, escopo por `id_igreja`.
- Quiet hours: enviar entre 06:00–21:00 (parametrizável por igreja).
- Rate‑limit: travas simples por lote; logs básicos de sucesso/erro.
- Consentimento: respeitar opt‑out futuro (planejar flag em perfil).
- Tom/Mensagem (baseado em `docs/sobre_ibnm.md`): linguagem pastoral, acolhedora, centrada em missão/visão; ênfase em comunidade, serviço e esperança.
### 1) Aniversariantes automático (06:00)

- Gatilho: rotina diária (cron/script) às 06:00.
- Alvo: membros e pastores com `day(data_nasc)=hoje` e `ativo='Sim'` (por igreja).
- Fluxo:
  1. Selecionar aniversariantes por igreja; montar mensagem personalizada.
  2. Enviar via `apis/texto.php` (imediato) ou `agendar.php` (para 06:00, se rodar antes).
  3. Fallback: enviar confirmação para `telefone_igreja_sistema` (Super Admin) com resumo (quantidade e nomes/telefones parciais).
  4. Persistir log mínimo (membro, data, status) para evitar duplicidades no dia.
- Locais envolvidos:
  - `apiIgreja/notific/script.php` (modelo de loop por páginas/igrejas) — criar bloco “aniversariantes” 06:00.
  - Helper templates (futuro): `sistema/helpers/whatsapp_templates.php`.
- Template sugerido (tono pastoral):
  - "*[NOME_SISTEMA]*\n🎉 Feliz Aniversário, [NOME]!\nQue o Senhor abençoe sua vida com graça e paz. Estamos com você em oração.\n— Igreja Batista Nacional Maracanã"
### 2) Novos conteúdos (eventos, cultos, notícias)

- Gatilho: ao salvar/ativar registro em `eventos`, `cultos`, `informativos` (quando `ativo='Sim'`).
- Alvo: membros da igreja com telefone válido; opcionalmente grupos/células/ministerios.
- Fluxo:
  1. Hook após `INSERT/UPDATE` em `paginas/eventos/salvar.php` (e equivalentes) para montar mensagem com título + link público (`url_sistema_site + eventos.php?e=[url]`).
  2. Enviar imediato via `apis/texto.php` em lotes (respeitando rate‑limit e quiet hours).
  3. Registrar log de disparo (tabela `whatsapp_logs` futura) com status por telefone.
- Locais envolvidos: `sistema/painel-igreja/paginas/eventos/salvar.php` (+ cultos/informativos), `apis/texto.php`.
- Template sugerido:
  - "*[NOME_SISTEMA]*\n🗓️ Novo [TIPO]: [TITULO]\nConfira os detalhes aqui: [LINK]"
### 3) Boas‑vindas a novos membros (após cadastro)

- Gatilho: `paginas/membros/salvar.php` quando `INSERT` (não existe hoje — seguro implementar).
- Alvo: telefone do novo membro (se informado e `api_whatsapp != 'Não'`).
- Fluxo:
  1. Após inserir, montar mensagem de acolhimento com missão/visão (vide `docs/sobre_ibnm.md`).
  2. Enviar via `apis/texto.php` (imediato). Respeitar quiet hours: se fora do horário, agendar para 08:00 do próximo dia.
  3. Logs mínimos (membro, data, status) e idempotência (não enviar duas vezes no mesmo dia/registro).
- Locais envolvidos: `sistema/painel-igreja/paginas/membros/salvar.php`, `apis/texto.php`.
- Template sugerido (pastoral/acolhimento):
  - "*[NOME_SISTEMA]*\n🤝 Seja muito bem‑vindo, [NOME]!\nNossa missão é alcançar pessoas e capacitá‑las a servir plenamente ao Senhor.\nConte conosco no que precisar. Deus abençoe!"
### 4) Métrica de inatividade (para reengajamento futuro)

- Definição: dias desde a última atividade do membro.
- Fonte de dados (existente): MAX data entre `dizimos`, `ofertas`, `doacoes`, `celulas_membros`, `grupos_membros`, `ministerios_membros`, `turmas_membros`; fallback `membros.data_cad`.
- Uso: coluna na listagem de membros + filtros por faixas (30/60/90+) e gatilho de reengajamento via WhatsApp.
- Evolução: futuramente registrar presença em cultos/eventos para tornar a métrica mais fiel.
