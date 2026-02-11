# 🛡️ SPRINT 2 - PLANEJAMENTO DETALHADO
## Perímetro de Defesa (Anti-Bot + Rate Limiting)

**Projeto**: IBNM.online SaaS de Gestão Eclesiástica  
**Versão Alvo**: v7.10 (Defense Layer)  
**Data de Início**: 09 de Fevereiro de 2026, 23:48 GMT-3  
**Responsável**: Nexus (Co-piloto IA) + André Lopes (Tech Lead)

---

## 🎯 OBJETIVO DA SPRINT 2

Implementar **camada de perímetro** para proteger o sistema contra ataques automatizados (bots), brute force e session hijacking.

### Foco Principal
- ✅ Bloquear bots automatizados
- ✅ Prevenir ataques de força bruta
- ✅ Proteger contra sequestro de sessão
- ✅ Fortalecer headers de segurança HTTP

---

## 📋 TAREFAS DA SPRINT 2

### **Task 2.1: Honeypot Anti-Bot**
**Objetivo**: Detectar e bloquear bots automatizados  
**Tempo Estimado**: 30 minutos  
**Prioridade**: 🔴 ALTA

#### Implementação
1. Campo oculto no formulário de login (HTML)
2. Validação no backend (PHP)
3. Log de tentativas de bot detectadas

#### Arquivos Afetados
- `sistema/index.php` (formulário de login)
- `sistema/autenticar.php` (validação)

---

### **Task 2.2: Rate Limiting Progressivo**
**Objetivo**: Bloquear IPs após tentativas falhas  
**Tempo Estimado**: 1h 30min  
**Prioridade**: 🔴 CRÍTICA

#### Escalação Aprovada (André)
```
3 tentativas   → Bloqueio de 1 minuto
6 tentativas   → Bloqueio de 30 minutos
9 tentativas   → Bloqueio de 1 hora
12+ tentativas → Bloqueio de 24 horas
```

#### Implementação
1. Criar tabela `failed_logins`
2. Implementar lógica de bloqueio progressivo
3. Cleanup automático de registros antigos (90 dias)

#### Arquivos Afetados
- `SQL/sprint2_failed_logins.sql` (tabela)
- `sistema/autenticar.php` (validação de bloqueio)
- `apiIgreja/login/login.php` (validação de bloqueio)

---

### **Task 2.3: Fingerprint de Sessão**
**Objetivo**: Prevenir sequestro de sessão (Session Hijacking)  
**Tempo Estimado**: 45 minutos  
**Prioridade**: 🟠 ALTA

#### Implementação
1. Gerar fingerprint (User-Agent + IP)
2. Validar fingerprint a cada requisição
3. Destruir sessão se fingerprint mudar

#### Arquivos Afetados
- `sistema/verificar.php` (validação de sessão)

---

### **Task 2.4: Headers de Segurança Avançados**
**Objetivo**: Adicionar Content Security Policy (CSP)  
**Tempo Estimado**: 30 minutos  
**Prioridade**: 🟡 MÉDIA

#### Implementação
1. CSP restritivo mas funcional
2. Permissions Policy
3. HSTS (produção)

#### Arquivos Afetados
- `.htaccess` (raiz do projeto)

---

## 🗄️ ESTRUTURA DO BANCO DE DADOS

### Tabela `failed_logins`

```sql
CREATE TABLE IF NOT EXISTS failed_logins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(45) NOT NULL,
    email_tentativa VARCHAR(255) NULL,
    tentativas INT DEFAULT 1,
    bloqueado_ate DATETIME NULL,
    ultimo_erro VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_ip (ip),
    INDEX idx_bloqueado (bloqueado_ate),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Limpeza Automática (Evento MySQL)

```sql
CREATE EVENT IF NOT EXISTS limpar_failed_logins_antigos
ON SCHEDULE EVERY 1 DAY
DO
DELETE FROM failed_logins WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY);
```

---

## 📊 CRONOGRAMA

| Task | Início | Duração | Conclusão Estimada |
|------|--------|---------|-------------------|
| **2.1** Honeypot | 23:50 | 30min | 00:20 |
| **2.2** Rate Limiting | 00:20 | 1h 30min | 01:50 |
| **2.3** Fingerprint | 01:50 | 45min | 02:35 |
| **2.4** Headers CSP | 02:35 | 30min | 03:05 |

**Total Estimado**: 3h 15min (madrugada de 10/02/2026)

---

## ✅ CRITÉRIOS DE ACEITE

### Task 2.1: Honeypot
- [ ] Campo oculto implementado (CSS: display:none)
- [ ] Bot detectado quando honeypot é preenchido
- [ ] Log registrado em `failed_logins` (tipo: 'BOT_DETECTED')
- [ ] Mensagem genérica de erro (não revelar detecção)

### Task 2.2: Rate Limiting
- [ ] Tabela `failed_logins` criada
- [ ] Bloqueio progressivo funcionando (1min, 30min, 1h, 24h)
- [ ] IP liberado após login bem-sucedido
- [ ] Evento de limpeza automática ativo

### Task 2.3: Fingerprint
- [ ] Fingerprint gerado em `verificar.php`
- [ ] Sessão destruída se fingerprint mudar
- [ ] Redirecionamento para login com mensagem

### Task 2.4: Headers CSP
- [ ] CSP implementado (permitindo CDNs necessários)
- [ ] Permissions Policy implementado
- [ ] HSTS configurado (comentado para localhost)

---

## 🧪 PLANO DE TESTES

### Teste 1: Honeypot Anti-Bot
```bash
# Simular bot preenchendo honeypot
POST sistema/autenticar.php
Body: email=admin@test.com&senha=123&website=bot-value

# Resultado esperado:
# - HTTP 302 (redirect)
# - Log em failed_logins: tipo='BOT_DETECTED'
# - Mensagem: "Erro no sistema"
```

### Teste 2: Rate Limiting
```bash
# Simular 3 tentativas erradas
for i in {1..3}; do
  curl -X POST http://localhost/ibnm.online/sistema/autenticar.php \
    -d "email=teste@test.com&senha=errada$i"
done

# Resultado esperado após 3ª tentativa:
# - Bloqueio de 1 minuto
# - Mensagem: "IP bloqueado. Tente em X minutos."

# Simular 6 tentativas (após desbloqueio)
# - Bloqueio de 30 minutos

# Simular 9 tentativas
# - Bloqueio de 1 hora

# Simular 12 tentativas
# - Bloqueio de 24 horas
```

### Teste 3: Fingerprint de Sessão
```bash
# 1. Login bem-sucedido
# 2. Capturar cookie de sessão
# 3. Alterar User-Agent
# 4. Tentar acessar página protegida

# Resultado esperado:
# - Sessão destruída
# - Redirect para login
# - Mensagem: "Sessão inválida"
```

### Teste 4: Headers CSP
```bash
curl -I http://localhost/ibnm.online/

# Resultado esperado:
# Content-Security-Policy: default-src 'self'; script-src...
# Permissions-Policy: geolocation=(), microphone=()...
```

---

## 📁 ARQUIVOS A SEREM CRIADOS/MODIFICADOS

### Novos Arquivos
```
SQL/sprint2_failed_logins.sql           (tabela + evento)
sistema/helpers/RateLimiter.php         (classe helper)
sistema/helpers/SessionFingerprint.php  (classe helper)
SPRINT_2_RELATORIO_FINAL.md            (relatório pós-sprint)
```

### Arquivos Modificados
```
sistema/index.php                       (honeypot)
sistema/autenticar.php                  (rate limiting + honeypot)
sistema/verificar.php                   (fingerprint)
apiIgreja/login/login.php              (rate limiting)
.htaccess                               (CSP + Permissions Policy)
docs/updates.md                         (changelog v7.10)
```

---

## 🔒 CONSIDERAÇÕES DE SEGURANÇA

### Honeypot
- ✅ Campo invisível via CSS (não via `type="hidden"`)
- ✅ Nome genérico ("website", "url", "company")
- ✅ Não revelar que bot foi detectado (mensagem genérica)

### Rate Limiting
- ✅ Armazenar IP em VARCHAR(45) (suporta IPv6)
- ✅ Bloqueio no servidor (PHP), não apenas cliente
- ✅ Limpar registros antigos (LGPD - 90 dias)

### Fingerprint
- ⚠️ User-Agent pode mudar legitimamente (atualizações de browser)
- ⚠️ IP pode mudar (mobile, VPN)
- ✅ Solução: Validar apenas em mudanças abruptas

### CSP
- ⚠️ Muito restritivo pode quebrar funcionalidades
- ✅ Permitir CDNs necessários (Bootstrap, jQuery, FontAwesome)
- ✅ Permitir `unsafe-inline` temporariamente (refatorar depois)

---

## 📊 MÉTRICAS DE SUCESSO

| Métrica | Meta | Como Medir |
|---------|------|-----------|
| Bots bloqueados | >90% | Log `failed_logins` (tipo='BOT_DETECTED') |
| Brute force prevenido | 100% | Bloqueio após 3 tentativas |
| Session hijacking prevenido | >95% | Fingerprint validation |
| Headers CSP ativos | 100% | curl -I |

---

## 🚨 RISCOS E MITIGAÇÕES

| Risco | Probabilidade | Impacto | Mitigação |
|-------|---------------|---------|-----------|
| Usuário legítimo bloqueado | MÉDIA | ALTO | Desbloqueio manual via admin |
| Fingerprint falso positivo | BAIXA | MÉDIO | Validar apenas mudanças abruptas |
| CSP quebrar funcionalidades | MÉDIA | ALTO | Testar exaustivamente antes deploy |
| Rate limiting muito agressivo | BAIXA | MÉDIO | Escalação progressiva (aceita) |

---

## 📚 REFERÊNCIAS

- [OWASP: Brute Force Attack Prevention](https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html)
- [Content Security Policy (CSP) Guide](https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP)
- [Session Fixation Prevention](https://cheatsheetseries.owasp.org/cheatsheets/Session_Management_Cheat_Sheet.html)

---

## 🎯 PRÓXIMA SPRINT (Preview)

**Sprint 3: Autenticação Enterprise (JWT)**
- JWT (JSON Web Tokens)
- Refresh Token
- Middleware de validação
- Migração completa da API

**Previsão**: 15-22 de Fevereiro de 2026

---

**Status**: 🟡 INICIANDO  
**Aprovação**: ✅ André Lopes (09/02/2026 23:48)

---

_"A melhor defesa é uma boa estratégia de perímetro."_  
— Nexus, Security Team
