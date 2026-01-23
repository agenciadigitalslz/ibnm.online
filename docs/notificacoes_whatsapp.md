# Notificações e Mensagens — API WhatsApp (Menuia/Wm/Newtek)

---
Tipo: implementacao
Data: 2025-08-11
Autor: André Lopes
Status: ativo
---

Documento de referência para manutenção e expansão das notificações via WhatsApp no SaaS Igrejas. Abrange provedores Menuia, WordMensagens (wm) e NewTek, gatilhos existentes, templates de mensagens e locais de alteração.

## Visão geral e configuração
- Provedores suportados: `menuia`, `wm` (WordMensagens), `newtek`.
- Chaves de configuração (lidas de `config` em `sistema/conexao.php`):
  - `api_whatsapp` (seletor do provedor)
  - `token_whatsapp` (App Key / Token)
  - `instancia_whatsapp` (AuthKey / Instância)
- UI de configuração e teste: `sistema/painel-admin/index.php` (campo de seleção, token, instância e botão de teste que chama `apis/teste_whatsapp.php`).
- Formato de telefone: `55` + número sanitizado (remove espaços, parênteses e hífens).
## Endpoints de envio

- Envio imediato (texto):
  - Painel Igreja: `sistema/painel-igreja/apis/texto.php`
  - Painel Admin:  `sistema/painel-admin/apis/texto.php`
- Agendamento de envio:
  - Painel Igreja: `sistema/painel-igreja/apis/agendar.php`
  - Painel Admin:  `sistema/painel-admin/apis/agendar.php`
- Cancelamento de agendamento:
  - Painel Igreja: `sistema/painel-igreja/apis/cancelar_agendamento.php`
  - Painel Admin:  `sistema/painel-admin/apis/cancelar_agendamento.php`
- Teste de provedor:
  - Painel Igreja: `sistema/painel-igreja/apis/teste_whatsapp.php`
  - Painel Admin:  `sistema/painel-admin/apis/teste_whatsapp.php`

Implementações por provedor:
- Menuia: `https://chatbot.menuia.com/api/create-message` (POST multipart: `appkey`, `authkey`, `to`, `message`, flags `sandbox`, `agendamento`, etc.).
- Wm: `http://api.wordmensagens.com.br/` (`send-text`, `agendar-text`, `delete-agenda`).
- NewTek: `https://webapi.newteksoft.com.br/` (`enviar-texto`, `agendar-texto`, `deletar`) com JSON.
## Tipos de notificações já implementadas

1) Cadastro de Usuários/Tesoureiros (boas‑vindas)
- Gatilho: ao salvar usuário/tesoureiro (telefone preenchido e `api_whatsapp != 'Não'`).
- Disparo: imediato (texto).
- Templates principais:
  - Cabeçalho com nome do sistema
  - Saudação, e-mail, senha padrão, URL de acesso e lembrete para troca de senha
- Locais:
  - `sistema/painel-admin/paginas/usuarios/salvar.php`
  - `sistema/painel-igreja/paginas/usuarios/salvar.php`
  - `sistema/painel-igreja/paginas/tesoureiros/salvar.php`

2) Recuperação de Senha
- Gatilho: solicitação de recuperar senha com e-mail existente.
- Disparo: imediato (texto) com link único de reset.
- Local: `sistema/recuperar-senha.php`

3) Financeiro — Receber (para o membro)
- Lembrete de Vencimento (agendado)
  - Gatilhos: ao salvar conta a receber; ao baixar gerando próxima recorrência; ao baixar com resíduo.
  - Disparo: agendado para `vencimento 08:00:00`.
  - Local: `sistema/painel-igreja/paginas/receber/salvar.php`, `.../receber/baixar.php`.
- Cobrança Manual (imediato)
  - Gatilho: botão “Gerar Cobrança” na listagem.
  - Disparo: imediato; conteúdo varia entre “Conta Vencida” ou “Lembrete de Pagamento” conforme data.
  - Local: `sistema/painel-igreja/paginas/receber/cobrar.php`.

4) Financeiro — Pagar (para o telefone da igreja/sistema)
- Lembrete de Vencimento (agendado)
  - Gatilhos: ao salvar conta a pagar; ao baixar gerando próxima recorrência; ao baixar com resíduo.
  - Disparo: agendado para `vencimento 08:00:00`.
  - Local: `sistema/painel-igreja/paginas/pagar/salvar.php`, `.../pagar/baixar.php`.

5) Tarefas (usuário responsável)
- Lembrete de Tarefa Agendada (agendado)
  - Gatilho: cadastro/edição com `hora_alerta` definida.
  - Disparo: agendado para `data + hora_alerta`.
  - Local: `sistema/painel-igreja/paginas/tarefas/salvar.php`.
## Estrutura das mensagens (templates)

Padrões recorrentes (placeholders entre colchetes):

- Boas‑vindas
  - "*[NOME_SISTEMA]*\n🤩 Olá [NOME] Você foi Cadastrado no Sistema!!\n*Email:* [EMAIL]\n*Senha:* [SENHA]\n*Url Acesso:* [URL]\n\nAo entrar no sistema, troque sua senha!"
- Recuperação de senha
  - "*[NOME_SISTEMA]*\n🤩 Link para Recuperação de Senha\n\n[RESET_LINK]"
- Financeiro — Lembrete
  - "💰 *[NOME_SISTEMA]*\n_Conta Vencendo Hoje_\n*Descrição:* [DESCRICAO]\n*Valor:* [VALOR]\n[OPCIONAL: *Dados para o Pagamento:*\n[DADOS_PGTO]]"
- Financeiro — Cobrança
  - "*[NOME_SISTEMA]*\n[_Conta Vencida_ | _Lembrete de Pagamento_]\n*Descrição:* [DESCRICAO]\n*Valor:* [VALOR]\n*Vencimento:* [VENCIMENTO]\n[OPCIONAL: *Dados para o Pagamento:*\n[DADOS_PGTO]]"
- Tarefas — Lembrete
  - "*[NOME_SISTEMA]*\n🤩 Lembrete de Tarefa Agendada\n🕛 *Hora:* [HORA]\n📅 *Data:* [DATA]\n✅ *Descrição:* [DESCRICAO]"

Observações:
- Quebra de linha: nos scripts é usado `%0A` e convertido para `\n` antes do envio em Menuia/NewTek.
- Sanitização de telefone: `55` + `preg_replace('/[ ()-]+/','', $telefone)`.
- Agendamento: armazenar e atualizar o `hash` de agendamento no registro para permitir cancelamento/reagendamento.
## Boas práticas e segurança

- Restringir conteúdo dinâmico a dados já validados e escapar saídas em telas.
- Não concatenar SQL: usar PDO com `prepare`/`bindValue` nos módulos que alimentam notificações.
- Armazenar credenciais fora do VCS (migrar futuramente para `.env`/variáveis de ambiente conforme `contexto.md`).
- Padronizar mensagens e centralizar templates para futura reutilização (ver sessão “Roadmap”).
- Respeitar escopo multi‑igrejas: sempre filtrar por `$_SESSION['id_igreja']` ao buscar dados para mensagens.

## Locais de alteração rápidos (por caso de uso)

- Configuração/Teste de API: `sistema/painel-admin/index.php` (UI), `sistema/painel-igreja/index.php` (JS para teste), `apis/teste_whatsapp.php`.
- Envio imediato: `apis/texto.php` (de cada painel).
- Agendamento/Cancelamento: `apis/agendar.php`, `apis/cancelar_agendamento.php`.
- Usuários/Tesoureiros: `paginas/usuarios/salvar.php` (ambos os painéis), `paginas/tesoureiros/salvar.php` (painel igreja).
- Recuperação de senha: `sistema/recuperar-senha.php`.
- Financeiro Receber: `paginas/receber/salvar.php`, `paginas/receber/baixar.php`, `paginas/receber/cobrar.php`, `paginas/receber/listar.php` (botão cobrar).
- Financeiro Pagar: `paginas/pagar/salvar.php`, `paginas/pagar/baixar.php`.
- Tarefas: `paginas/tarefas/salvar.php`.
## Roadmap de expansão (sugestões)

- Centralizar templates em um helper (`sistema/helpers/whatsapp_templates.php`) para reaproveitamento e tradução.
- Suporte a variáveis condicionais (ex.: presença de `dados_pagamento`).
- Logs de envio (tabela `whatsapp_logs`) com status/erro para auditoria.
- Rate limit e fila (ex.: cron) para grandes volumes.
- Migração de credenciais para `.env` + rotação periódica.
- Unificação de autenticação da API app (migrar para `senha_crip` + tokens), caso venha a enviar notificações por eventos de app.

## Anexos/observações

- Os mesmos endpoints suportam Wm/NewTek, mas os payloads mudam (multipart vs JSON). Referenciar os arquivos `apis/*.php` para ajustes específicos.
- Teste rápido: usar o botão “Api Whatsapp” na UI do painel para validar appkey/authkey/telefone.

## Referências diretas no código (citadas)

- `sistema/painel-igreja/apis/texto.php`
- `sistema/painel-igreja/apis/agendar.php`
- `sistema/painel-igreja/apis/cancelar_agendamento.php`
- `sistema/painel-igreja/apis/teste_whatsapp.php`
- `sistema/painel-admin/apis/texto.php`
- `sistema/painel-admin/apis/agendar.php`
- `sistema/painel-admin/apis/cancelar_agendamento.php`
- `sistema/painel-admin/apis/teste_whatsapp.php`
- `sistema/painel-igreja/paginas/receber/salvar.php`
- `sistema/painel-igreja/paginas/receber/baixar.php`
- `sistema/painel-igreja/paginas/receber/cobrar.php`
- `sistema/painel-igreja/paginas/receber/listar.php`
- `sistema/painel-igreja/paginas/pagar/salvar.php`
- `sistema/painel-igreja/paginas/pagar/baixar.php`
- `sistema/painel-admin/paginas/usuarios/salvar.php`
- `sistema/painel-igreja/paginas/usuarios/salvar.php`
- `sistema/painel-igreja/paginas/tesoureiros/salvar.php`
- `sistema/recuperar-senha.php`

## Possíveis melhorias

# APROVADO:
- Aniversariantes automático (06:00)
  - Enviar felicitações automaticamente para membros e pastores no dia do aniversário, às 06:00.
  - Fallback: disparar confirmação para o telefone do Super Admin (`config.telefone`) informando que os parabéns foram enviados a [NOME] ([TELEFONE]).
  - Considerar evitar duplicidades (marcar envio por pessoa/data) e respeitar `ativo='Sim'`.
  - Locais sugeridos: agendar via `apiIgreja/notific/script.php` (bloco horário) ou cron dedicado; template centralizado num helper.

# APROVADO:
- Novos conteúdos (eventos, cultos, notícias)
  - Ao criar/ativar um evento/culto/notícia, enviar notificação WhatsApp com título e link para a página pública.
  - Público alvo configurável (todos os membros com telefone válido; grupos/células; equipe). Respeitar `igreja` na filtragem.
  - Locais sugeridos: hooks nos `paginas/eventos/salvar.php`, `paginas/secretaria/cultos/salvar.php`, etc., chamando `apis/texto.php` ou `agendar.php`.

# APROVADO:
- Outras sugestões
  - Boas‑vindas a novos membros (após cadastro/integração) com instruções e contatos úteis.
  - Rate limit e fila de envio para grandes campanhas, com logs e retentativas.
