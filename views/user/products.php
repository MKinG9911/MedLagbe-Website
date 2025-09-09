<?php
$title = 'Products - MedLagbe';
include 'views/layouts/header.php';
?>

<div class="container" style="padding: 2rem 0;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Our Products</h1>
        <div style="color: var(--text-secondary);">
            <?= count($products) ?> products found
            <?php if ($search): ?>
                for "<?= htmlspecialchars($search) ?>"
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="filters">
        <form method="GET" class="filters-row">
            <div class="form-group">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" 
                       value="<?= htmlspecialchars($search) ?>" 
                       placeholder="Search medicines...">
            </div>
            
            <div class="form-group">
                <label class="form-label">Category</label>
                <select name="category" class="form-control">
                    <option value="">All Categories</option>
                    <option value="1" <?= $filters['category'] == '1' ? 'selected' : '' ?>>Tablets</option>
                    <option value="2" <?= $filters['category'] == '2' ? 'selected' : '' ?>>Syrups</option>
                    <option value="3" <?= $filters['category'] == '3' ? 'selected' : '' ?>>Supplements</option>
                    <option value="4" <?= $filters['category'] == '4' ? 'selected' : '' ?>>Pain Relief</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Min Price</label>
                <input type="number" name="min_price" class="form-control" 
                       value="<?= htmlspecialchars($filters['min_price']) ?>" 
                       placeholder="Min Price">
            </div>
            
            <div class="form-group">
                <label class="form-label">Max Price</label>
                <input type="number" name="max_price" class="form-control" 
                       value="<?= htmlspecialchars($filters['max_price']) ?>" 
                       placeholder="Max Price">
            </div>
            
            <div class="form-group">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="<?= BASE_URL ?>user/products" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Products Grid -->
    <?php if (empty($products)): ?>
        <div class="text-center" style="padding: 3rem 0;">
            <h3>No products found</h3>
            <p style="color: var(--text-secondary);">Try adjusting your search criteria.</p>
            <a href="<?= BASE_URL ?>user/products" class="btn btn-primary">View All Products</a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
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
    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>
