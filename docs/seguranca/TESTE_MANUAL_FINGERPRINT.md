# Teste Manual - Session Fingerprint (Sprint 2)
**Data:** 10/02/2026  
**Versão:** v7.10  
**Objetivo:** Validar proteção contra session hijacking

---

## 🎯 O Que Testar

Session Fingerprint valida que o **User-Agent + IP** da sessão não mudaram. Se mudarem, a sessão é destruída (proteção contra roubo de cookies).

---

## 📋 Pré-Requisitos

1. ✅ Credenciais de login válidas
2. ✅ Browser com DevTools (Chrome, Edge, Firefox)
3. ✅ Extensão "User-Agent Switcher" (ou usar DevTools)

---

## 🧪 Passos do Teste

### **Teste 1: Login Normal (Baseline)**

1. Abrir `http://localhost/ibnm.online/sistema/`
2. Fazer login com credenciais válidas
3. Após redirecionamento, abrir DevTools (F12)
4. Console → Executar:
   ```javascript
   fetch('/ibnm.online/sistema/helpers/ver_fingerprint.php')
     .then(r => r.json())
     .then(d => console.table(d));
   ```
5. **Resultado esperado:**
   ```json
   {
     "fingerprint": "abc123...",
     "user_agent": "Mozilla/5.0 ...",
     "ip": "::1",
     "fingerprint_salvo": "abc123...",
     "valido": true
   }
   ```
   ✅ `valido: true` confirma fingerprint correto

---

### **Teste 2: Alterar User-Agent (Simular Hijacking)**

#### **Opção A: Com Extensão User-Agent Switcher**

1. Instalar extensão:
   - Chrome: [User-Agent Switcher](https://chrome.google.com/webstore/detail/user-agent-switcher)
   - Firefox: [User-Agent Switcher](https://addons.mozilla.org/en-US/firefox/addon/user-agent-string-switcher/)

2. Após login (com sessão ativa):
   - Clicar no ícone da extensão
   - Selecionar User-Agent diferente (ex: "iPhone 12")
   - **NÃO** recarregar a página ainda

3. Verificar fingerprint novamente:
   ```javascript
   fetch('/ibnm.online/sistema/helpers/ver_fingerprint.php')
     .then(r => r.json())
     .then(d => console.table(d));
   ```
   
4. **Resultado esperado:**
   ```json
   {
     "fingerprint": "xyz789...",  // DIFERENTE
     "fingerprint_salvo": "abc123...",
     "valido": false  // ❌
   }
   ```

5. Tentar acessar área restrita:
   - Recarregar página do painel (`/painel-admin` ou `/painel-igreja`)
   
6. **Resultado esperado:**
   - ✅ Redirecionamento automático para `/sistema/index.php`
   - ✅ Mensagem: "Sessão inválida. Faça login novamente."
   - ✅ Sessão destruída (`$_SESSION` limpo)

#### **Opção B: Com DevTools (Chrome/Edge)**

1. Após login, abrir DevTools (F12)
2. Menu → More Tools → Network Conditions
3. Desmarcar "Use browser default"
4. Selecionar User-Agent diferente (ex: "Safari — iPad")
5. Recarregar página do painel
6. **Resultado esperado:** Igual ao Teste 2 (redirecionamento)

---

### **Teste 3: Mudar IP (Cenário Raro)**

**⚠️ Difícil de testar localmente** (requer proxy ou VPN)

**Alternativa (simulação no código):**

1. Editar temporariamente `sistema/helpers/SessionFingerprint.php`:
   ```php
   // Linha ~28 (getIP)
   public static function getIP(): string {
       return '192.168.1.999'; // IP falso para teste
   }
   ```

2. Fazer login
3. Restaurar código original:
   ```php
   return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
   ```

4. Tentar acessar painel
5. **Resultado esperado:** Sessão destruída (IP mudou)

---

## ✅ Critérios de Aceitação

| Teste | Condição | Resultado Esperado |
|-------|----------|-------------------|
| 1. Login normal | User-Agent original | ✅ Sessão válida |
| 2. Alterar User-Agent | User-Agent diferente | ✅ Sessão destruída |
| 3. Alterar IP | IP diferente | ✅ Sessão destruída |
| 4. Restaurar User-Agent | Voltar ao original | ✅ Sessão válida (novo login) |

---

## 🐛 Troubleshooting

### **Problema: Sessão não é destruída após mudar User-Agent**

**Verificar:**

1. `verificar.php` está chamando `SessionFingerprint::validar()`?
   ```php
   // Deve estar em painel-admin/verificar.php e painel-igreja/verificar.php
   if (!SessionFingerprint::validar()) {
       exit(); // Sessão destruída
   }
   ```

2. Fingerprint está sendo salvo no login?
   ```php
   // Em sistema/autenticar.php (após login bem-sucedido)
   SessionFingerprint::renovar();
   ```

3. Sessão está ativa?
   ```php
   // Verificar se session_start() foi chamado
   var_dump($_SESSION);
   ```

### **Problema: Fingerprint sempre retorna `valido: false`**

**Causa comum:** User-Agent contém caracteres especiais que mudam entre requests

**Solução:** Normalizar User-Agent antes de gerar hash
```php
// Em SessionFingerprint::gerar()
$user_agent = preg_replace('/[^a-zA-Z0-9\s\(\)\.\-_]/', '', $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown');
```

---

## 📊 Evidências de Teste

**Capturar screenshots:**

1. ✅ Login bem-sucedido (fingerprint válido)
2. ✅ Fingerprint JSON (`ver_fingerprint.php`)
3. ✅ Redirecionamento após mudança de User-Agent
4. ✅ Console do navegador mostrando `valido: false`

**Evidência no banco:**

```sql
-- Verificar sessões ativas (não há tabela de sessões, apenas fingerprint em $_SESSION)
-- Verificar se fingerprint mudou
SELECT * FROM failed_logins WHERE ip = '::1' ORDER BY updated_at DESC LIMIT 5;
```

---

## 🎯 Resultado Final Esperado

```
╔═══════════════════════════════════════════════════════════╗
║           TESTE MANUAL - SESSION FINGERPRINT             ║
╠═══════════════════════════════════════════════════════════╣
║  ✅ Login normal → Fingerprint válido                    ║
║  ✅ Alterar User-Agent → Sessão destruída                ║
║  ✅ Redirecionamento automático → Login                  ║
║  ✅ Novo login → Nova sessão válida                      ║
╠═══════════════════════════════════════════════════════════╣
║            SESSION FINGERPRINT: APROVADO ✅              ║
╚═══════════════════════════════════════════════════════════╝
```

---

**Tempo estimado:** 5-10 minutos  
**Última atualização:** 10/02/2026 01:25 GMT-3
