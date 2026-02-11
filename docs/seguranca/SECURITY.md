# 🔐 SECURITY.md — Política de Segurança do IBNM.online

**Versão**: 1.0.0 (Platinum Edition)  
**Última Atualização**: 09 de Fevereiro de 2026  
**Status**: ✅ Sprint 1 Concluída

---

## 📋 Sumário

1. [Visão Geral](#visão-geral)
2. [Vulnerabilidades Conhecidas](#vulnerabilidades-conhecidas)
3. [Medidas de Proteção Implementadas](#medidas-de-proteção-implementadas)
4. [Configuração Segura](#configuração-segura)
5. [Reportar Vulnerabilidades](#reportar-vulnerabilidades)
6. [Roadmap de Segurança](#roadmap-de-segurança)

---

## 🎯 Visão Geral

O **IBNM.online** é um SaaS de gestão eclesiástica multi-tenancy que passou por um processo completo de **Security Hardening** em Fevereiro de 2026, elevando seu nível de maturidade de segurança para padrão **Enterprise**.

### Modelo de Defesa em Profundidade

```
┌─────────────────────────────────────────────────────┐
│  CAMADA 4: AUDITORIA E MONITORAMENTO                │ Sprint 4 (Planejada)
├─────────────────────────────────────────────────────┤
│  CAMADA 3: AUTENTICAÇÃO ENTERPRISE (JWT)            │ Sprint 3 (Planejada)
├─────────────────────────────────────────────────────┤
│  CAMADA 2: PERÍMETRO (Anti-Bot + Headers)           │ Sprint 2 (Planejada)
├─────────────────────────────────────────────────────┤
│  CAMADA 1: BLINDAGEM CRÍTICA (SQL + .env)           │ ✅ Sprint 1 (CONCLUÍDA)
└─────────────────────────────────────────────────────┘
```

---

## 🔴 Vulnerabilidades Conhecidas

### ✅ Corrigidas (Sprint 1 - 09/02/2026)

| CVE/ID | Tipo | Severidade | Localização | Status |
|--------|------|------------|-------------|--------|
| IBNM-2026-001 | SQL Injection | 🔴 CRÍTICA | `apiIgreja/login/login.php` | ✅ CORRIGIDA |
| IBNM-2026-002 | Credenciais Expostas | 🔴 CRÍTICA | `sistema/conexao.php` | ✅ ISOLADAS |
| IBNM-2026-003 | Information Disclosure | 🟠 ALTA | Apache `ServerTokens` | ✅ CORRIGIDA |
| IBNM-2026-004 | Path Traversal | 🟠 ALTA | Acesso direto a `.env` | ✅ BLOQUEADA |
| IBNM-2026-005 | Directory Listing | 🟡 MÉDIA | Navegação de pastas | ✅ DESABILITADA |

### ⚠️ Pendentes (Sprints 2-4)

| ID | Tipo | Severidade | Sprint |
|----|------|------------|--------|
| IBNM-2026-006 | Brute Force Attack | 🟠 ALTA | Sprint 2 |
| IBNM-2026-007 | Session Hijacking | 🟠 ALTA | Sprint 3 |
| IBNM-2026-008 | Dados em Texto Plano | 🟡 MÉDIA | Sprint 4 |

---

## 🛡️ Medidas de Proteção Implementadas

### 1. Proteção Contra SQL Injection

**Status**: ✅ IMPLEMENTADO (API de Login)

- **Prepared Statements** (PDO com parâmetros nomeados)
- **Sanitização de inputs** (`filter_var`, validação de tipos)
- **Escopo por `id_igreja`** (segregação multi-tenancy)

**Exemplo de código seguro**:
```php
$query = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND ativo = 'Sim'");
$query->execute([$email_sanitizado]);
```

---

### 2. Proteção de Credenciais

**Status**: ✅ IMPLEMENTADO

- **Arquivo `.env`** fora do controle de versão (`.gitignore`)
- **Template `.env.example`** versionado para referência
- **Variáveis de ambiente** carregadas via função `loadEnv()`
- **Bloqueio HTTP** de acesso direto a `.env` via `.htaccess`

**Estrutura do `.env`**:
```env
DB_HOST=localhost
DB_NAME=ibnm.online
DB_USER=root
DB_PASSWORD=senha_segura_aqui
JWT_SECRET=chave_jwt_32_bytes
ENCRYPTION_KEY=chave_aes256_32_bytes
```

---

### 3. Autenticação Robusta

**Status**: ✅ IMPLEMENTADO (Senhas Web + API)

- **Algoritmo**: bcrypt (`PASSWORD_BCRYPT`, cost 10-12)
- **Compatibilidade**: argon2id disponível (PHP 7.2+)
- **API**: `password_verify()` implementado
- **Web**: Já utilizava hashes desde v6.0

**Verificação de senha segura**:
```php
if (password_verify($senha, $usuario['senha_crip'])) {
    // Autenticado com sucesso
}
```

---

### 4. Headers de Segurança

**Status**: ✅ IMPLEMENTADO

| Header | Valor | Proteção |
|--------|-------|----------|
| `X-Frame-Options` | `SAMEORIGIN` | Clickjacking |
| `X-Content-Type-Options` | `nosniff` | MIME Sniffing |
| `X-XSS-Protection` | `1; mode=block` | XSS (navegadores antigos) |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | Vazamento de URLs |
| `ServerTokens` | `Prod` | Ocultação de versão Apache |

---

### 5. Hardening de Arquivos

**Status**: ✅ IMPLEMENTADO

**Bloqueados via `.htaccess`**:
- `.env` (credenciais)
- `*.sql` (dumps de banco)
- `*.bak`, `*.backup` (backups)
- `*.log` (logs de erro)
- `*.md` (documentação sensível)

**Proteção de uploads**:
```apache
<FilesMatch "\.(jpg|jpeg|png|gif|pdf)$">
    Header set Content-Disposition "inline"
    Header set X-Content-Type-Options "nosniff"
</FilesMatch>
```

---

## ⚙️ Configuração Segura

### Setup Inicial (Desenvolvimento/Staging)

1. **Copiar `.env.example` para `.env`**:
   ```bash
   cp .env.example .env
   ```

2. **Preencher credenciais** (nunca versionar `.env`):
   ```env
   DB_NAME=seu_banco
   DB_USER=seu_usuario
   DB_PASSWORD=senha_forte_aqui
   ```

3. **Gerar chaves de segurança**:
   ```bash
   # JWT Secret (32 bytes base64)
   openssl rand -base64 32
   
   # Encryption Key (32 bytes base64)
   openssl rand -base64 32
   ```

4. **Validar configuração**:
   ```bash
   # Verificar se .env está bloqueado
   curl http://localhost/ibnm.online/.env
   # Deve retornar: 403 Forbidden
   ```

---

### Produção (cPanel/VPS)

1. **Upload via FTP** (excluir `.env` e `backup/`)
2. **Criar `.env` manualmente** no File Manager do cPanel
3. **Permissões do `.env`**: `600` (somente leitura pelo owner)
4. **Trocar credenciais** imediatamente após migração
5. **Ativar HTTPS** (certificado SSL/TLS)
6. **Configurar backups automáticos** (cron diário)

---

## 🚨 Reportar Vulnerabilidades

Se você descobrir uma vulnerabilidade de segurança, **NÃO abra uma issue pública**.

### Processo de Reporte Responsável

1. **Email**: `contato@agenciadigitalslz.com.br`
2. **Assunto**: `[SEGURANÇA] Vulnerabilidade no IBNM.online`
3. **Incluir**:
   - Descrição detalhada da vulnerabilidade
   - Passos para reprodução
   - Impacto potencial
   - Sugestão de correção (se tiver)

### Compromisso

- **Resposta inicial**: 48 horas úteis
- **Avaliação de risco**: 7 dias
- **Correção (crítica)**: 14 dias
- **Correção (alta)**: 30 dias
- **Crédito público**: Após correção (se desejar)

---

## 🗓️ Roadmap de Segurança

### Sprint 2: Perímetro de Defesa (2-3 dias)
**Previsão**: Fevereiro 2026

- [ ] Honeypot no formulário de login
- [ ] Rate Limiting progressivo (1min→3, 30min→6, 1h→9, 24h→12 tentativas)
- [ ] Blacklist automática de IPs
- [ ] Fingerprint de sessão (User-Agent + IP)

---

### Sprint 3: Autenticação Enterprise (5-7 dias)
**Previsão**: Fevereiro 2026

- [ ] JWT (JSON Web Tokens) para API
- [ ] Refresh Token (30 dias de validade)
- [ ] Middleware de validação JWT
- [ ] Migração completa da API para tokens

---

### Sprint 4: Auditoria + LGPD (5-7 dias)
**Previsão**: Fevereiro/Março 2026

- [ ] SecurityLogger centralizado
- [ ] Dashboard de segurança (painel admin)
- [ ] Alertas via WhatsApp (eventos críticos)
- [ ] Criptografia AES-256 de CPFs
- [ ] Direito ao Esquecimento (LGPD Art. 18)
- [ ] Relatórios de conformidade LGPD

---

## 📚 Referências

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP: The Right Way](https://phptherightway.com/)
- [LGPD - Lei Geral de Proteção de Dados](http://www.planalto.gov.br/ccivil_03/_ato2015-2018/2018/lei/l13709.htm)
- [CWE - Common Weakness Enumeration](https://cwe.mitre.org/)

---

## 📞 Contato

**Agência Digital SLZ**  
Email: contato@agenciadigitalslz.com.br  
Telefone: +55 98 9 8741 7250  
Website: https://agenciadigitalslz.com.br

---

**Última Revisão**: 09 de Fevereiro de 2026  
**Responsável**: André Lopes (Tech Lead) + Nexus (Security Engineer)

---

_Segurança não é um produto, é um processo contínuo._
