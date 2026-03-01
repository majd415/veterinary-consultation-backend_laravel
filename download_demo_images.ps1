$baseUrl = "https://images.unsplash.com"
$publicPath = "c:\xampp\htdocs\dog_market_backend\backend\public\images"

# Ensure directories exist
New-Item -ItemType Directory -Force -Path "$publicPath\sliders"
New-Item -ItemType Directory -Force -Path "$publicPath\products"
New-Item -ItemType Directory -Force -Path "$publicPath\top_rated"
New-Item -ItemType Directory -Force -Path "$publicPath\logo"
New-Item -ItemType Directory -Force -Path "$publicPath\users"

# Function to download image
function Download-Image {
    param ($Url, $DestPath)
    Write-Host "Downloading $DestPath..."
    try {
        Invoke-WebRequest -Uri $Url -OutFile $DestPath
    } catch {
        Write-Host "Failed to download $DestPath"
    }
}

# Sliders
Download-Image "$baseUrl/photo-1548199973-03cce0bbc87b?q=80&w=800&fit=crop" "$publicPath\sliders\slider1.jpg"
Download-Image "$baseUrl/photo-1583337130417-3346a1be7dee?q=80&w=800&fit=crop" "$publicPath\sliders\slider2.jpg"
Download-Image "$baseUrl/photo-1599839575945-a9e5af0c3fa5?q=80&w=800&fit=crop" "$publicPath\sliders\slider3.jpg"

# Products
Download-Image "$baseUrl/photo-1589924691195-41432c84c161?q=80&w=400&fit=crop" "$publicPath\products\food.jpg"
Download-Image "$baseUrl/photo-1516734212186-a967f81ad0d7?q=80&w=400&fit=crop" "$publicPath\products\shampoo.jpg"
Download-Image "$baseUrl/photo-1576201836106-db1758fd1c97?q=80&w=400&fit=crop" "$publicPath\products\toy.jpg"

# Top Rated (reuse or new)
Copy-Item "$publicPath\products\food.jpg" "$publicPath\top_rated\food.jpg"
Copy-Item "$publicPath\products\shampoo.jpg" "$publicPath\top_rated\shampoo.jpg"
Copy-Item "$publicPath\products\toy.jpg" "$publicPath\top_rated\toy.jpg"
Download-Image "$baseUrl/photo-1560743173-567a3bdd585c?q=80&w=400&fit=crop" "$publicPath\top_rated\hotel.jpg"

# Logo
Download-Image "https://cdn-icons-png.flaticon.com/512/616/616408.png" "$publicPath\logo\logo.png"

# Users
Download-Image "$baseUrl/photo-1535713875002-d1d0cf377fde?q=80&w=200&fit=crop" "$publicPath\users\user1.jpg"

Write-Host "All images downloaded!"
