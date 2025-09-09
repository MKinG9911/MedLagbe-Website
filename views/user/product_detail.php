<?php
$title = htmlspecialchars($product['name']) . ' - MedLagbe';
include 'views/layouts/header.php';
?>

<div class="container" style="padding: 2rem 0;">
    <div style="display: grid; grid-template-columns: 400px 1fr; gap: 3rem; align-items: start;">
        <!-- Product Image -->
        <div class="card">
            <img src="<?= BASE_URL ?>public/images/products/<?= $product['image'] ?>" 
                 alt="<?= htmlspecialchars($product['name']) ?>" 
                 style="width: 100%; height: 400px; object-fit: cover;"
                 onerror="this.src='<?= BASE_URL ?>public/images/logo1.png'">
        </div>
        
        <!-- Product Info -->
        <div>
            <h1 class="mb-2"><?= htmlspecialchars($product['name']) ?></h1>
            <p class="text-secondary mb-2">Brand: <?= htmlspecialchars($product['brand']) ?></p>
            <p class="text-secondary mb-3">Category: <?= htmlspecialchars($product['category_name']) ?></p>
            
            <!-- Rating -->
            <?php if ($product['review_count'] > 0): ?>
                <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                    <div style="color: #fbbf24; margin-right: 0.5rem;">
                        <?php
                        $rating = round($product['avg_rating']);
                        for ($i = 1; $i <= 5; $i++) {
                            echo $i <= $rating ? '★' : '☆';
                        }
                        ?>
                    </div>
                    <span style="color: var(--text-secondary);">
                        <?= number_format($product['avg_rating'], 1) ?> (<?= $product['review_count'] ?> reviews)
                    </span>
                </div>
            <?php endif; ?>
            
            <!-- Price -->
            <div style="font-size: 2rem; font-weight: 700; color: var(--primary-color); margin-bottom: 1rem;">
                ৳<?= number_format($product['price'], 2) ?>
            </div>
            
            <!-- Prescription Required -->
            <?php if ($product['prescription_required']): ?>
                <div class="alert alert-warning">
                    <strong>⚠️ Prescription Required</strong><br>
                    This medicine requires a valid prescription from a doctor.
                </div>
            <?php endif; ?>
            
            <!-- Stock Status -->
            <div style="margin-bottom: 1.5rem;">
                <?php if ($product['stock_quantity'] > 0): ?>
                    <span class="badge badge-success">In Stock (<?= $product['stock_quantity'] ?> available)</span>
                <?php else: ?>
                    <span class="badge badge-danger">Out of Stock</span>
                <?php endif; ?>
            </div>
            
            <!-- Add to Cart Form -->
            <?php if ($product['stock_quantity'] > 0): ?>
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                    <div class="quantity-control">
                        <button type="button" class="decrease">-</button>
                        <input type="number" id="quantity" value="1" min="1" max="<?= $product['stock_quantity'] ?>">
                        <button type="button" class="increase">+</button>
                    </div>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button onclick="addToCartWithQuantity(<?= $product['id'] ?>)" class="btn btn-primary btn-lg">
                            Add to Cart
                        </button>
                    
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>auth/login" class="btn btn-primary btn-lg">Login to Buy</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Product Details -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Product Details</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td><strong>Dosage:</strong></td>
                            <td><?= htmlspecialchars($product['dosage']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Ingredients:</strong></td>
                            <td><?= htmlspecialchars($product['ingredients']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Expiry Date:</strong></td>
                            <td><?= date('M Y', strtotime($product['expiry_date'])) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Prescription Required:</strong></td>
                            <td><?= $product['prescription_required'] ? 'Yes' : 'No' ?></td>
                        </tr>
                    </table>
                    
                    <?php if ($product['description']): ?>
                        <h4>Description</h4>
                        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reviews Section -->
    <div style="margin-top: 3rem;">
        <h2>Customer Reviews</h2>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Add Review Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title mb-0">Write a Review</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>review/add" data-validate>
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        
                        <div class="form-group">
                            <label class="form-label">Rating</label>
                            <div class="rating-input">
                                <input type="radio" name="rating" value="5" id="star5">
                                <label for="star5">★</label>
                                <input type="radio" name="rating" value="4" id="star4">
                                <label for="star4">★</label>
                                <input type="radio" name="rating" value="3" id="star3">
                                <label for="star3">★</label>
                                <input type="radio" name="rating" value="2" id="star2">
                                <label for="star2">★</label>
                                <input type="radio" name="rating" value="1" id="star1">
                                <label for="star1">★</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="comment" class="form-label">Comment</label>
                            <textarea id="comment" name="comment" class="form-control" rows="4" 
                                      placeholder="Share your experience with this product..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Existing Reviews -->
        <?php if (empty($reviews)): ?>
            <p style="color: var(--text-secondary);">No reviews yet. Be the first to review this product!</p>
        <?php else: ?>
            <?php foreach ($reviews as $review): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <div>
                                <strong><?= htmlspecialchars($review['user_name']) ?></strong>
                                <div style="color: #fbbf24; margin: 0.25rem 0;">
                                    <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $review['rating'] ? '★' : '☆';
                                    }
                                    ?>
                                </div>
                            </div>
                            <small style="color: var(--text-secondary);">
                                <?= date('M j, Y', strtotime($review['created_at'])) ?>
                            </small>
                        </div>
                        
                        <?php if ($review['comment']): ?>
                            <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function addToCartWithQuantity(productId) {
    const quantity = document.getElementById('quantity').value;
    addToCart(productId, quantity);
}

function addToWishlist(productId) {
    fetch('<?= BASE_URL ?>wishlist/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Added to wishlist!', 'success');
        } else {
            showNotification(data.message || 'Failed to add to wishlist', 'error');
        }
    })
    .catch(error => {
        showNotification('Error adding to wishlist', 'error');
    });
}

// Rating input functionality
document.querySelectorAll('.rating-input input').forEach(input => {
    input.addEventListener('change', function() {
        const value = this.value;
        const labels = document.querySelectorAll('.rating-input label');
        labels.forEach((label, index) => {
            if (index < value) {
                label.style.color = '#fbbf24';
            } else {
                label.style.color = '#d1d5db';
            }
        });
    });
});
</script>

<style>
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    gap: 0.25rem;
}

.rating-input input {
    display: none;
}

.rating-input label {
    font-size: 2rem;
    color: #d1d5db;
    cursor: pointer;
    transition: color 0.2s;
}

.rating-input label:hover,
.rating-input label:hover ~ label {
    color: #fbbf24;
}

.rating-input input:checked ~ label {
    color: #fbbf24;
}
</style>

<?php include 'views/layouts/footer.php'; ?>
