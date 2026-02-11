# Sprint 4 - Auditoria + LGPD + Observabilidade
## Planejamento Detalhado

**Projeto:** IBNM.online - Sistema de Gestão Eclesiástica  
**Sprint:** 4 de 4 (Defense in Depth - FINAL)  
**Versão Alvo:** v7.30  
**Previsão:** 10/02/2026 - 12/02/2026  
**Duração Estimada:** 5-7h (baseado em performance Sprints 1-3)  
**Responsável:** Nexus (Security Team)

---

## 🎯 Objetivo

Implementar **auditoria centralizada, dashboard de segurança e ferramentas LGPD** para:
- ✅ Monitoramento de eventos de segurança
- ✅ Dashboard administrativo (métricas + alertas)
- ✅ Criptografia de dados sensíveis (CPF)
- ✅ Conformidade LGPD (exclusão de dados)
- ✅ Alertas automáticos de segurança
- ✅ Observabilidade completa do sistema

---

## 📋 Tasks

### Task 4.1: Tabela security_logs (Auditoria Centralizada)
**Duração estimada:** 30min

#### Subtasks
1. ✅ Criar script SQL `sprint4_security_logs.sql`
2. ✅ Tabela com campos: evento, user_id, ip, user_agent, detalhes, severidade
3. ✅ Índices otimizados (idx_user_id, idx_evento, idx_created_at)
4. ✅ Evento de limpeza LGPD (180 dias para logs)
5. ✅ Particionamento por data (opcional - performance)

**Entregável:**
- `SQL/sprint4_security_logs.sql`
- Tabela `security_logs` no banco

---

### Task 4.2: Classe SecurityLogger
**Duração estimada:** 1h

#### Subtasks
1. ✅ Criar `sistema/helpers/SecurityLogger.php`
2. ✅ Métodos:
   - `log(string $evento, string $severidade, array $detalhes)`
   - `logLogin(int $userId, bool $sucesso, string $ip)`
   - `logLogout(int $userId, string $tipo)`
   - `logTokenRenovado(int $userId, string $ip)`
   - `logAcessoNegado(int $userId, string $recurso)`
   - `logAlteracaoDados(int $userId, string $tabela, int $id)`
3. ✅ Níveis de severidade: INFO, WARNING, ERROR, CRITICAL
4. ✅ Integração com JWTManager, RateLimiter, etc.

**Entregável:**
- `sistema/helpers/SecurityLogger.php` (6-8KB)

---

### Task 4.3: Dashboard de Segurança (Admin)
**Duração estimada:** 1h 30min

#### Subtasks
1. ✅ Criar página `sistema/painel-admin/dashboard-seguranca.php`
2. ✅ Cards de métricas:
   - Total de logins (últimas 24h, 7 dias, 30 dias)
   - Tentativas de login bloqueadas
   - Tokens ativos vs revogados
   - Eventos críticos (últimas 24h)
   - Bots detectados
3. ✅ Gráficos (Chart.js ou similar):
   - Logins por hora (últimas 24h)
   - Eventos de segurança por tipo
   - IPs mais ativos
4. ✅ Tabela de eventos recentes (últimos 50)
5. ✅ Filtros: data, severidade, tipo de evento

**Entregável:**
- `sistema/painel-admin/dashboard-seguranca.php`
- API endpoint: `apiIgreja/admin/estatisticas-seguranca.php`

---

### Task 4.4: Criptografia de Dados Sensíveis (CPF)
**Duração estimada:** 45min

#### Subtasks
1. ✅ Criar classe `sistema/helpers/Encryptor.php`
2. ✅ Métodos:
   - `encrypt(string $data): string` (AES-256-CBC)
   - `decrypt(string $encrypted): string|false`
   - `hashCPF(string $cpf): string` (SHA-256 para buscas)
3. ✅ Chave de criptografia via `.env` (ENCRYPTION_KEY)
4. ✅ Script de migração: `scripts/encrypt_existing_cpf.php`
5. ✅ Atualizar queries de CPF para usar versão criptografada

**Entregável:**
- `sistema/helpers/Encryptor.php`
- `scripts/encrypt_existing_cpf.php`
- Coluna `cpf_encrypted` em `usuarios`

---

### Task 4.5: Endpoint LGPD (Exclusão de Dados)
**Duração estimada:** 45min

#### Subtasks
1. ✅ Criar `apiIgreja/auth/delete-account.php`
2. ✅ Validações:
   - Access token válido
   - Confirmação via senha
   - Não permitir para Bispo (admin principal)
3. ✅ Ações:
   - Anonimizar dados: `nome = 'Usuário Excluído'`, `email = NULL`, `cpf = NULL`
   - Revogar todos os refresh tokens
   - Marcar `ativo = 'Não'`
   - Registrar em `security_logs` (auditoria LGPD)
4. ✅ Opção de exclusão total (CASCADE) vs anonimização

**Entregável:**
- `apiIgreja/auth/delete-account.php`

---

### Task 4.6: Integração SecurityLogger no Sistema
**Duração estimada:** 30min

#### Subtasks
1. ✅ Atualizar `sistema/autenticar.php`:
   - `SecurityLogger::logLogin()` (sucesso/falha)
2. ✅ Atualizar `apiIgreja/auth/logout.php`:
   - `SecurityLogger::logLogout()`
3. ✅ Atualizar `apiIgreja/auth/token.php`:
   - `SecurityLogger::logTokenRenovado()`
4. ✅ Atualizar `RateLimiter`:
   - `SecurityLogger::log()` em bloqueios

**Entregável:**
- Arquivos existentes atualizados com logs

---

### Task 4.7: Alertas de Segurança (Opcional - Básico)
**Duração estimada:** 30min

#### Subtasks
1. ✅ Criar `sistema/helpers/AlertManager.php`
2. ✅ Métodos:
   - `enviarAlerta(string $titulo, string $mensagem, string $nivel)`
   - `alertaBloqueioMassivo(int $tentativas, string $ip)`
   - `alertaTokenRevogadoMassivo(int $userId)`
3. ✅ Canal: Email (simples, via PHP `mail()`)
4. ✅ Threshold configurável (ex: 10 bloqueios em 5 min → alerta)

**Entregável:**
- `sistema/helpers/AlertManager.php` (básico)

---

### Task 4.8: Endpoint de Estatísticas de Segurança
**Duração estimada:** 30min

#### Subtasks
1. ✅ Criar `apiIgreja/admin/estatisticas-seguranca.php`
2. ✅ Retornar JSON com:
   - Total de logins (24h, 7d, 30d)
   - Tentativas bloqueadas
   - Tokens ativos/revogados
   - Eventos críticos
   - Top 10 IPs mais ativos
   - Eventos por hora (gráfico)

**Entregável:**
- `apiIgreja/admin/estatisticas-seguranca.php`

---

### Task 4.9: Testes de Integração
**Duração estimada:** 45min

#### Subtasks
1. ✅ Criar `teste_sprint4_completo.php`
2. ✅ Testes:
   - Gravação de logs (security_logs)
   - Criptografia/descriptografia de CPF
   - Endpoint de exclusão de conta
   - Estatísticas de segurança
   - Dashboard de segurança (visual)

**Entregável:**
- `teste_sprint4_completo.php`

---

### Task 4.10: Documentação Final
**Duração estimada:** 30min

#### Subtasks
1. ✅ Criar `SPRINT_4_RELATORIO_FINAL.md`
2. ✅ Atualizar `docs/updates.md` (v7.30)
3. ✅ Criar `SECURITY_AUDIT_REPORT.md` (consolidado de todos os sprints)
4. ✅ Atualizar `README.md` com badges de segurança

**Entregável:**
- Documentação completa do Sprint 4
- Relatório consolidado de auditoria

---

## 📊 Resumo de Entregas

### Arquivos Novos (10-12)
1. `SQL/sprint4_security_logs.sql`
2. `sistema/helpers/SecurityLogger.php`
3. `sistema/helpers/Encryptor.php`
4. `sistema/helpers/AlertManager.php` (opcional)
5. `sistema/painel-admin/dashboard-seguranca.php`
6. `apiIgreja/admin/estatisticas-seguranca.php`
7. `apiIgreja/auth/delete-account.php`
8. `scripts/encrypt_existing_cpf.php`
9. `teste_sprint4_completo.php`
10. `SPRINT_4_PLANEJAMENTO.md` (este arquivo)
11. `SPRINT_4_RELATORIO_FINAL.md`
12. `SECURITY_AUDIT_REPORT.md`

### Arquivos Modificados (4-6)
1. `sistema/autenticar.php` (integração SecurityLogger)
2. `apiIgreja/auth/logout.php` (logs)
3. `apiIgreja/auth/token.php` (logs)
4. `sistema/helpers/RateLimiter.php` (logs)
5. `.env.example` (ENCRYPTION_KEY)
6. `docs/updates.md` (v7.30)

---

## 🎯 Critérios de Sucesso

| Critério | Meta |
|----------|------|
| Tempo de implementação | < 7h |
| Testes aprovados | 100% |
| Logs funcionais | ✅ |
| Dashboard operacional | ✅ |
| Criptografia AES-256 | ✅ |
| Endpoint LGPD funcional | ✅ |
| Conformidade LGPD Art. 18 | ✅ |

---

## 🔒 Conformidade LGPD

### Artigos Implementados

**Art. 16 (Retenção de Dados):**
- ✅ Logs de segurança: 180 dias
- ✅ Refresh tokens: 90 dias após expiração
- ✅ Failed logins: 90 dias

**Art. 18 (Direitos do Titular):**
- ✅ Exclusão/Anonimização de dados
- ✅ Confirmação de exclusão
- ✅ Revogação de consentimento (tokens)

---

## 📈 Métricas de Performance Esperadas

| Operação | Overhead |
|----------|----------|
| Log de evento | < 5ms |
| Criptografia CPF | < 3ms |
| Descriptografia CPF | < 3ms |
| Estatísticas (query) | < 50ms |
| Dashboard completo | < 200ms |

---

## 🚀 Roadmap Futuro (Pós-Sprint 4)

1. **OAuth 2.0** (login social: Google, Facebook)
2. **Two-Factor Authentication** (2FA via SMS/email)
3. **WAF** (Web Application Firewall - ModSecurity)
4. **Penetration Testing** (Bug Bounty externo)
5. **SOC 2 Type II** (auditoria de segurança empresarial)

---

**Pronto para iniciar Sprint 4 (FINAL)?** 🎯
