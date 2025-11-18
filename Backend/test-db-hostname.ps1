# Test different Render PostgreSQL hostname formats
# This script helps you find the correct hostname for your Render database

$baseHost = "dpg-d4e16cv5r7bs73ff3osg-a"
$regions = @(
    "oregon-postgres.render.com",
    "singapore-postgres.render.com", 
    "frankfurt-postgres.render.com",
    "ohio-postgres.render.com",
    "virginia-postgres.render.com"
)

Write-Host "Testing common Render PostgreSQL hostname formats..." -ForegroundColor Cyan
Write-Host "Base hostname: $baseHost" -ForegroundColor Yellow
Write-Host ""

foreach ($region in $regions) {
    $fullHost = "$baseHost.$region"
    Write-Host "Testing: $fullHost" -ForegroundColor Gray
    
    # Test DNS resolution
    try {
        $result = Resolve-DnsName -Name $fullHost -ErrorAction Stop -Type A
        Write-Host "  ✓ DNS resolution successful!" -ForegroundColor Green
        Write-Host "  IP Address: $($result[0].IPAddress)" -ForegroundColor Green
        Write-Host ""
        Write-Host "SUCCESS! Use this hostname in your .env file:" -ForegroundColor Green
        Write-Host "DB_HOST=$fullHost" -ForegroundColor Yellow
        Write-Host ""
        break
    } catch {
        Write-Host "  ✗ DNS resolution failed" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "If none of these worked, check your Render dashboard:" -ForegroundColor Cyan
Write-Host "1. Go to https://dashboard.render.com" -ForegroundColor White
Write-Host "2. Click on your PostgreSQL database service" -ForegroundColor White
Write-Host "3. Look in the 'Connections' section" -ForegroundColor White
Write-Host "4. Copy the full Host value (should include the full domain)" -ForegroundColor White
Write-Host ""

