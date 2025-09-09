<?php
$title = 'Shopping Cart - MedLagbe';
include 'views/layouts/header.php';
?>

<div class="container" style="padding: 2rem 0;">
    <h1 class="mb-4">Shopping Cart</h1>
    
    <?php if (empty($cartItems)): ?>
        <div class="text-center" style="padding: 3rem 0;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🛒</div>
            <h3>Your cart is empty</h3>
            <p style="color: var(--text-secondary); margin-bottom: 2rem;">Add some medicines to get started.</p>
            <a href="<?= BASE_URL ?>user/products" class="btn btn-primary btn-lg">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem; align-items: start;">
            <!-- Cart Items -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Cart Items (<?= count($cartItems) ?>)</h3>
                </div>
                <div class="card-body p-0">
                    <?php foreach ($cartItems as $item): ?>
                        <div style="display: flex; align-items: center; padding: 1.5rem; border-bottom: 1px solid var(--border-color);">
                            <img src="<?= BASE_URL ?>public/images/products/<?= $item['image'] ?>" 
                                 alt="<?= htmlspecialchars($item['name']) ?>" 
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: var(--border-radius);"
                                 onerror="this.src='<?= BASE_URL ?>public/images/logo1.png'">
                            
                            <div style="flex: 1; margin-left: 1rem;">
                                <h4 style="margin-bottom: 0.5rem;"><?= htmlspecialchars($item['name']) ?></h4>
                                <p style="color: var(--text-secondary); margin-bottom: 0.5rem;">৳<?= number_format($item['price'], 2) ?> each</p>
                                
                                <?php if ($item['prescription_required']): ?>
                                    <span class="badge badge-warning">Prescription Required</span>
                                <?php endif; ?>
                                
                                <div class="quantity-control mt-2">
                                    <button type="button" class="decrease">-</button>
                                    <input type="number" value="<?= $item['quantity'] ?>" min="1" 
                                           data-product-id="<?= $item['product_id'] ?>">
                                    <button type="button" class="increase">+</button>
                                </div>
                            </div>
                            
                            <div style="text-align: right;">
                                <div style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">
                                    ৳<?= number_format($item['price'] * $item['quantity'], 2) ?>
                                </div>
                                <button onclick="removeFromCart(<?= $item['product_id'] ?>)" 
                                        class="btn btn-danger btn-sm">Remove</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Order Summary</h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <span>Subtotal:</span>
                        <span>৳<?= number_format($total, 2) ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <span>Delivery:</span>
                        <span>৳50.00</span>
                    </div>
                    <hr>
                    <div style="display: flex; justify-content: space-between; font-size: 1.125rem; font-weight: 600; margin-bottom: 2rem;">
                        <span>Total:</span>
                        <span>৳<?= number_format($total + 50, 2) ?></span>
                    </div>
                    
                    <a href="<?= BASE_URL ?>order/checkout" class="btn btn-primary btn-lg">Proceed to Checkout</a>
                    <a href="<?= BASE_URL ?>user/products" class="btn btn-outline mt-2">Continue Shopping</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>
