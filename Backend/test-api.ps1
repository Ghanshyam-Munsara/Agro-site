# AgroSite API Testing Script for PowerShell
# Run this script to quickly test all API endpoints

$baseUrl = "http://localhost:8000/api"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "AgroSite API Testing Script" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Test 1: Get All Products
Write-Host "Test 1: GET All Products" -ForegroundColor Green
try {
    $response = Invoke-RestMethod -Uri "$baseUrl/products" -Method Get
    Write-Host "✓ Success! Found $($response.meta.total) products" -ForegroundColor Yellow
    Write-Host ""
} catch {
    Write-Host "✗ Failed: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host ""
}

# Test 2: Get Products with Filters
Write-Host "Test 2: GET Products with Filters (category=seeds)" -ForegroundColor Green
try {
    $response = Invoke-RestMethod -Uri "$baseUrl/products?category=seeds" -Method Get
    Write-Host "✓ Success! Found $($response.meta.total) products in seeds category" -ForegroundColor Yellow
    Write-Host ""
} catch {
    Write-Host "✗ Failed: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host ""
}

# Test 3: Create Product
Write-Host "Test 3: POST Create Product" -ForegroundColor Green
try {
    $productData = @{
        name = "Test Product - PowerShell"
        description = "This is a test product created via PowerShell script"
        category = "seeds"
        price = 19.99
        currency = "USD"
        stock_quantity = 50
        status = "active"
    } | ConvertTo-Json

    $response = Invoke-RestMethod -Uri "$baseUrl/products" -Method Post -Body $productData -ContentType "application/json"
    $productId = $response.data.id
    Write-Host "✓ Success! Product created with ID: $productId" -ForegroundColor Yellow
    Write-Host ""
} catch {
    Write-Host "✗ Failed: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host ""
    $productId = $null
}

# Test 4: Get Single Product
if ($productId) {
    Write-Host "Test 4: GET Single Product (ID: $productId)" -ForegroundColor Green
    try {
        $response = Invoke-RestMethod -Uri "$baseUrl/products/$productId" -Method Get
        Write-Host "✓ Success! Product: $($response.data.name)" -ForegroundColor Yellow
        Write-Host ""
    } catch {
        Write-Host "✗ Failed: $($_.Exception.Message)" -ForegroundColor Red
        Write-Host ""
    }
}

# Test 5: Get All Services
Write-Host "Test 5: GET All Services" -ForegroundColor Green
try {
    $response = Invoke-RestMethod -Uri "$baseUrl/services" -Method Get
    Write-Host "✓ Success! Found $($response.meta.total) services" -ForegroundColor Yellow
    Write-Host ""
} catch {
    Write-Host "✗ Failed: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host ""
}

# Test 6: Create Service
Write-Host "Test 6: POST Create Service" -ForegroundColor Green
try {
    $serviceData = @{
        name = "Test Service - PowerShell"
        description = "This is a test service created via PowerShell script"
        category = "Testing"
        icon = "fa-test"
        price = 99.00
        price_type = "monthly"
        status = "active"
    } | ConvertTo-Json

    $response = Invoke-RestMethod -Uri "$baseUrl/services" -Method Post -Body $serviceData -ContentType "application/json"
    $serviceId = $response.data.id
    Write-Host "✓ Success! Service created with ID: $serviceId (Service ID: $($response.data.service_id))" -ForegroundColor Yellow
    Write-Host ""
} catch {
    Write-Host "✗ Failed: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host ""
    $serviceId = $null
}

# Test 7: Submit Contact Form
Write-Host "Test 7: POST Submit Contact Form" -ForegroundColor Green
try {
    $contactData = @{
        name = "Test User"
        email = "test@example.com"
        phone = "+1234567890"
        subject = "general"
        message = "This is a test message from PowerShell API testing script."
    } | ConvertTo-Json

    $response = Invoke-RestMethod -Uri "$baseUrl/contacts" -Method Post -Body $contactData -ContentType "application/json"
    $contactId = $response.data.id
    Write-Host "✓ Success! Contact submitted with ID: $contactId" -ForegroundColor Yellow
    Write-Host "  Message: $($response.message)" -ForegroundColor Gray
    Write-Host ""
} catch {
    Write-Host "✗ Failed: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host ""
    $contactId = $null
}

# Test 8: Get All Contacts (Admin)
Write-Host "Test 8: GET All Contacts" -ForegroundColor Green
try {
    $response = Invoke-RestMethod -Uri "$baseUrl/contacts" -Method Get
    Write-Host "✓ Success! Found $($response.meta.total) contacts" -ForegroundColor Yellow
    Write-Host ""
} catch {
    Write-Host "✗ Failed: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host ""
}

# Test 9: Test Validation (Invalid Data)
Write-Host "Test 9: POST Create Product (Invalid Data - Testing Validation)" -ForegroundColor Green
try {
    $invalidData = @{
        name = ""
        category = "invalid_category"
        price = -10
    } | ConvertTo-Json

    Invoke-RestMethod -Uri "$baseUrl/products" -Method Post -Body $invalidData -ContentType "application/json"
    Write-Host "✗ Validation should have failed!" -ForegroundColor Red
    Write-Host ""
} catch {
    Write-Host "✓ Success! Validation caught errors (as expected)" -ForegroundColor Yellow
    Write-Host ""
}

# Cleanup (Optional - Uncomment to delete test data)
# if ($productId) {
#     Write-Host "Cleaning up: Deleting test product..." -ForegroundColor Gray
#     try {
#         Invoke-RestMethod -Uri "$baseUrl/products/$productId" -Method Delete
#         Write-Host "✓ Test product deleted" -ForegroundColor Yellow
#     } catch {
#         Write-Host "✗ Failed to delete test product" -ForegroundColor Red
#     }
# }

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Testing Complete!" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "For detailed testing guide, see: API_TESTING_GUIDE.md" -ForegroundColor Gray

