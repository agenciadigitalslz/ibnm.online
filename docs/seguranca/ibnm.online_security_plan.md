# 🔐 IBNM.ONLINE SECURITY PLAN — PLATINUM EDITION 2026

**Documento:** Plano Diretor de Segurança da Informação  
**Projeto:** SaaS de Gestão Eclesiástica ibnm.online  
**Versão:** 1.0.0 (Nexus Build)  
**Data:** 10 de Fevereiro de 2026  
**Responsável Técnico:** Nexus (Co-piloto IA)  
**Status:** 🚀 PRONTO PARA IMPLEMENTAÇÃO  

---

## 1. 🎯 VISÃO GERAL
O objetivo deste plano é elevar o nível de maturidade de segurança do **ibnm.online** de um sistema vulnerável para um padrão **SaaS Enterprise**, garantindo a integridade dos dados, a privacidade dos usuários e a conformidade total com a LGPD.

---

## 2. 🛡️ ESTRATÉGIA DE DEFESA EM PROFUNDIDADE (5 CAMADAS)

### CAMADA 1: PERÍMETRO & ANTI-BOT
*   **Honeypot:** Campos ocultos em formulários de login para capturar e bloquear bots automaticamente.
*   **Rate Limiting Progressivo:** Bloqueio de IP por 30 minutos após 10 tentativas falhas. Delays exponenciais entre a 3ª e a 5ª tentativa.
*   **Segurança via .htaccess:** Bloqueio de acesso direto a arquivos sensíveis (como `.env` e backups `.sql`).
*   **Headers de Segurança:** Implementação de `Strict-Transport-Security`, `X-Content-Type-Options`, `X-Frame-Options` e `Content-Security-Policy` (CSP).

### CAMADA 2: AUTENTICAÇÃO ROBUSTA
*   **Migração de Senhas:** Conversão de todas as senhas de texto puro para hashes criptográficos usando `argon2id` ou `bcrypt` (algoritmo padrão do PHP `password_hash`).
*   **API JWT (JSON Web Token):** Substituição de autenticação por POST puro por tokens JWT com expiração de 1 hora.
*   **Fingerprint de Sessão:** Verificação de User-Agent e IP a cada requisição para evitar sequestro de sessão (*Session Hijacking*).

### CAMADA 3: BLINDAGEM DE DADOS (ANTI-SQLi)
*   **Prepared Statements Obrigatórios:** Migração total da pasta `apiIgreja/` para o padrão PDO com parâmetros nomeados.
*   **Sanitização de Input:** Filtros rigorosos para HTML, scripts e caracteres especiais em todos os campos de texto.
*   **Credenciais Isoladas:** Remoção de senhas do banco de dentro do arquivo `conexao.php` para um arquivo `.env` fora do diretório público.

### CAMADA 4: AUDITORIA & MONITORAMENTO
*   **SecurityLogger:** Sistema centralizado que grava toda atividade suspeita (tentativas de SQLi, logins falhos, deleções em massa).
*   **Dashboard de Segurança:** Painel administrativo para visualizar logs de segurança em tempo real.
*   **Alertas via WhatsApp:** Notificação imediata para o André em caso de eventos críticos (ex: criação de novo Super Admin).

### CAMADA 5: CONFORMIDADE LGPD
*   **Criptografia em Repouso:** Dados sensíveis (como CPFs) serão protegidos.
*   **Logs de Acesso:** Rastreabilidade total de quem acessou qual dado e quando.
*   **Direito ao Esquecimento:** Funcionalidade de deleção segura de dados de membros conforme solicitado.

---

## 3. 📅 CRONOGRAMA DE EXECUÇÃO

| Fase | Título | Foco Principal | Prazo Estimado |
| :--- | :--- | :--- | :--- |
| **Fase 1** | **Blindagem Crítica** | Corrigir SQLi no Login e Hashear Senhas. | 1-3 dias |
| **Fase 2** | **Proteção .env** | Isolar credenciais e configurar .htaccess. | 1 dia |
| **Fase 3** | **API JWT** | Refatorar API Mobile para tokens seguros. | 5-7 dias |
| **Fase 4** | **Monitoramento** | Implementar SecurityLogger e Dashboard. | 3-5 dias |

---

## 4. 📝 RECOMENDAÇÕES IMEDIATAS (URGENTE)
1.  **Backup Total:** Realizar o backup `openclaw-backup_2.tar.gz` (concluído hoje).
2.  **Troca de Credenciais:** Assim que o isolamento `.env` for feito, trocar as senhas do banco de dados na VPS.
3.  **Ambiente de Staging:** Testar a migração dos hashes de senhas em um clone do banco antes de rodar em produção para não bloquear os usuários.

---

**Nexus — Estrategista de Segurança & Co-piloto Técnico**  
🚀 *Segurança não é um produto, é um processo.*
