# Teste de Rate Limiting - Sprint 2
Write-Host "=== TESTE DE RATE LIMITING ===" -ForegroundColor Cyan

$url = "http://localhost/ibnm.online/sistema/autenticar.php"
$body = @{
    usuario = "teste_rate@limit.com"
    senha = "senha_incorreta_456"
    website = ""
}

# Tentativa 1
Write-Host "`n--- Tentativa 1 ---" -ForegroundColor Yellow
try {
    $resp1 = Invoke-WebRequest -Uri $url -Method POST -Body $body -MaximumRedirection 0 -ErrorAction SilentlyContinue
    Write-Host "Status: $($resp1.StatusCode)" -ForegroundColor Green
    if ($resp1.Content -match "bloqueado|muitas tentativas") {
        Write-Host "BLOQUEADO!" -ForegroundColor Red
    }
} catch {
    Write-Host "Redirecionamento ou erro: $_" -ForegroundColor Gray
}

Start-Sleep -Seconds 2

# Tentativa 2
Write-Host "`n--- Tentativa 2 ---" -ForegroundColor Yellow
try {
    $resp2 = Invoke-WebRequest -Uri $url -Method POST -Body $body -MaximumRedirection 0 -ErrorAction SilentlyContinue
    Write-Host "Status: $($resp2.StatusCode)" -ForegroundColor Green
    if ($resp2.Content -match "bloqueado|muitas tentativas") {
        Write-Host "BLOQUEADO!" -ForegroundColor Red
    }
} catch {
    Write-Host "Redirecionamento ou erro: $_" -ForegroundColor Gray
}

Start-Sleep -Seconds 2

# Tentativa 3 (esperado bloqueio)
Write-Host "`n--- Tentativa 3 (esperado bloqueio 1min) ---" -ForegroundColor Yellow
try {
    $resp3 = Invoke-WebRequest -Uri $url -Method POST -Body $body -MaximumRedirection 0 -ErrorAction SilentlyContinue
    Write-Host "Status: $($resp3.StatusCode)" -ForegroundColor Green
    if ($resp3.Content -match "bloqueado|muitas tentativas") {
        Write-Host "✅ BLOQUEIO DETECTADO!" -ForegroundColor Green
    } else {
        Write-Host "⚠️ Bloqueio NÃO detectado" -ForegroundColor Red
    }
} catch {
    Write-Host "Redirecionamento ou erro: $_" -ForegroundColor Gray
}

# Verificar banco
Write-Host "`n--- Consultando Banco de Dados ---" -ForegroundColor Cyan
$queryFile = "C:\xampp\htdocs\ibnm.online\query_failed_logins.sql"
$result = cmd /c "C:\xampp\mysql\bin\mysql.exe -u root < $queryFile"
Write-Host $result

Write-Host "`n=== FIM DO TESTE ===" -ForegroundColor Cyan
