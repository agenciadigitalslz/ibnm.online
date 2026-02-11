# Teste de Honeypot Anti-Bot - Sprint 2
Write-Host "=== TESTE DE HONEYPOT ANTI-BOT ===" -ForegroundColor Cyan

$url = "http://localhost/ibnm.online/sistema/autenticar.php"

# Teste 1: SEM honeypot (comportamento normal)
Write-Host "`n--- Teste 1: Login SEM honeypot (normal) ---" -ForegroundColor Yellow
$body1 = @{
    usuario = "teste_honeypot@normal.com"
    senha = "senha123"
    website = ""  # Honeypot VAZIO
}

try {
    $resp1 = Invoke-WebRequest -Uri $url -Method POST -Body $body1 -MaximumRedirection 0 -ErrorAction SilentlyContinue
    Write-Host "✅ Requisição aceita (Status: $($resp1.StatusCode))" -ForegroundColor Green
} catch {
    Write-Host "Redirecionamento normal" -ForegroundColor Gray
}

Start-Sleep -Seconds 2

# Teste 2: COM honeypot (simular bot)
Write-Host "`n--- Teste 2: Login COM honeypot PREENCHIDO (bot) ---" -ForegroundColor Yellow
$body2 = @{
    usuario = "bot@malicioso.com"
    senha = "bot_password"
    website = "http://bot-spammer.com"  # Honeypot PREENCHIDO (bot detected!)
}

try {
    $resp2 = Invoke-WebRequest -Uri $url -Method POST -Body $body2 -MaximumRedirection 0 -ErrorAction SilentlyContinue
    Write-Host "Status: $($resp2.StatusCode)" -ForegroundColor Yellow
    if ($resp2.Content -match "Erro no sistema") {
        Write-Host "✅ BOT DETECTADO! Honeypot funcionando!" -ForegroundColor Green
    } else {
        Write-Host "⚠️ Bot não foi bloqueado" -ForegroundColor Red
    }
} catch {
    Write-Host "Redirecionamento (possível bloqueio)" -ForegroundColor Gray
}

# Verificar registros no banco
Write-Host "`n--- Verificando Banco (bots detectados) ---" -ForegroundColor Cyan
$queryBot = @"
USE ``ibnm.online``;
SELECT * FROM failed_logins WHERE ultimo_erro = 'BOT_DETECTED';
"@

Set-Content -Path "C:\xampp\htdocs\ibnm.online\query_bots.sql" -Value $queryBot
$result = cmd /c "C:\xampp\mysql\bin\mysql.exe -u root < C:\xampp\htdocs\ibnm.online\query_bots.sql"
if ($result) {
    Write-Host $result
} else {
    Write-Host "Nenhum bot detectado registrado" -ForegroundColor Yellow
}

Write-Host "`n=== FIM DO TESTE HONEYPOT ===" -ForegroundColor Cyan
