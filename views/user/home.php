<?php
$title = 'MedLagbe - Your Trusted Online Pharmacy';
include 'views/layouts/header.php';
?>

<!-- Hero Section -->
<section style="background: linear-gradient(135deg, var(--primary-color), var(--primary-hover)); color: white; padding: 4rem 0; text-align: center;">
    <div class="container">
        <h1 style="font-size: 3rem; margin-bottom: 1rem; color: white;">Welcome to MedLagbe</h1>
        <p style="font-size: 1.25rem; margin-bottom: 2rem; color: rgba(255,255,255,0.9);">Your trusted online pharmacy for quality medicines and healthcare products</p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="<?= BASE_URL ?>user/products" class="btn btn-lg" style="background: white; color: var(--primary-color);">Shop Now</a>
            <a href="<?= BASE_URL ?>user/support" class="btn btn-lg btn-outline" style="border-color: white; color: white;">Contact Support</a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section style="padding: 2rem 0;">
    <div class="container">
        <h2 class="text-center mb-4">Why Choose MedLagbe?</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin: 3rem 0;">
            <div class="card text-center">
                <div class="card-body">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🏥</div>
                    <h3 class="card-title">Quality Medicines</h3>
                    <p class="card-text">Genuine medicines from trusted manufacturers and suppliers.</p>
                </div>
            </div>
            
            <div class="card text-center">
                <div class="card-body">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🚚</div>
                    <h3 class="card-title">Fast Delivery</h3>
                    <p class="card-text">Quick and reliable delivery to your doorstep.</p>
                </div>
            </div>
            
            <div class="card text-center">
                <div class="card-body">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">👩‍⚕️</div>
                    <h3 class="card-title">Expert Support</h3>
                    <p class="card-text">Professional pharmacist consultation and support.</p>
                </div>
            </div>
            
            <div class="card text-center">
                <div class="card-body">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">💳</div>
                    <h3 class="card-title">Secure Payment</h3>
                    <p class="card-text">Multiple secure payment options for your convenience.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section style="padding: 4rem 0; background-color: var(--bg-secondary);">
    <div class="container">
        <h2 class="text-center mb-4">Featured Products</h2>
        
        <div class="product-grid">
            <?php foreach (array_slice($products, 0, 6) as $product): ?>
                <div class="product-card">
                    <img src="<?= BASE_URL ?>public/images/products/<?= $product['image'] ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>" 
                         class="product-image"
                         onerror="this.src='<?= BASE_URL ?>public/images/logo1.png'">
                    
                    <div class="product-info">
                        <h3 class="product-name"><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="product-brand"><?= htmlspecialchars($product['brand']) ?></p>
                        <p class="product-price">৳<?= number_format($product['price'], 2) ?></p>
                        
                        <?php if ($product['prescription_required']): ?>
                            <span class="product-prescription">Prescription Required</span>
                        <?php endif; ?>
                        
                        <div class="product-actions">
                            <a href="<?= BASE_URL ?>user/product_detail?id=<?= $product['id'] ?>" class="btn btn-outline btn-sm">View Details</a>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <button onclick="addToCart(<?= $product['id'] ?>)" class="btn btn-primary btn-sm">Add to Cart</button>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>auth/login" class="btn btn-primary btn-sm">Login to Buy</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>user/products" class="btn btn-primary btn-lg">View All Products</a>
        </div>
    </div>
</section>

<!-- How It Works -->
<section style="padding: 4rem 0;">
    <div class="container">
        <h2 class="text-center mb-4">How It Works</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin: 3rem 0;">
            <div class="text-center">
                <div style="background: var(--primary-color); color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin: 0 auto 1rem;">1</div>
                <h3>Browse & Search</h3>
                <p style="color: var(--text-secondary);">Find the medicines you need using our search and category filters.</p>
            </div>
            
            <div class="text-center">
                <div style="background: var(--primary-color); color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin: 0 auto 1rem;">2</div>
                <h3>Add to Cart</h3>
                <p style="color: var(--text-secondary);">Add your selected medicines to cart and upload prescription if required.</p>
            </div>
            
            <div class="text-center">
                <div style="background: var(--primary-color); color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin: 0 auto 1rem;">3</div>
                <h3>Place Order</h3>
                <p style="color: var(--text-secondary);">Confirm your delivery address and choose your preferred payment method.</p>
            </div>
            
            <div class="text-center">
                <div style="background: var(--primary-color); color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin: 0 auto 1rem;">4</div>
                <h3>Get Delivered</h3>
                <p style="color: var(--text-secondary);">Track your order and receive your medicines at your doorstep.</p>
            </div>
        </div>
    </div>
</section>

<?php include 'views/layouts/footer.php'; ?>
