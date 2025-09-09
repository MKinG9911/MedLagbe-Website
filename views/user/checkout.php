<?php
$title = 'Checkout - MedLagbe';
include 'views/layouts/header.php';
?>

<div class="container" style="padding: 2rem 0;">
    <h1 class="mb-4">Checkout</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <div style="display: grid; grid-template-columns: 1fr 400px; gap: 2rem; align-items: start;">
        <!-- Checkout Form -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Order Details</h3>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" data-validate>
                    <div class="form-group">
                        <label for="address" class="form-label">Delivery Address *</label>
                        <textarea id="address" name="address" class="form-control" rows="4" required 
                                  placeholder="Enter your full delivery address..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="payment_method" class="form-label">Payment Method *</label>
                        <select id="payment_method" name="payment_method" class="form-control" required>
                            <option value="">Select Payment Method</option>
                            <option value="cod">Cash on Delivery</option>
                            <option value="online">Online Payment (Demo)</option>
                        </select>
                    </div>
                    
                    <!-- Check if any items require prescription -->
                    <?php 
                    $requiresPrescription = false;
                    foreach ($cartItems as $item) {
                        if ($item['prescription_required']) {
                            $requiresPrescription = true;
                            break;
                        }
                    }
                    ?>
                    
                    <?php if ($requiresPrescription): ?>
                        <div class="form-group">
                            <label for="prescription" class="form-label">Upload Prescription *</label>
                            <input type="file" id="prescription" name="prescription" class="form-control" 
                                   accept=".jpg,.jpeg,.png,.pdf" required>
                            <div class="form-text">Required for prescription medicines. Accepted formats: JPG, PNG, PDF</div>
                            <div id="prescription-preview"></div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="discount_code" class="form-label">Discount Code (Optional)</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="text" id="discount_code" name="discount_code" class="form-control" 
                                   placeholder="Enter discount code">
                            <button type="button" class="btn btn-outline" onclick="applyDiscount()">Apply</button>
                        </div>
                    </div>
                    
                    <input type="hidden" name="discount_amount" id="discount_amount" value="0">
                    
                    <button type="submit" class="btn btn-primary btn-lg">Place Order</button>
                </form>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Order Summary</h3>
            </div>
            <div class="card-body">
                <!-- Cart Items -->
                <?php foreach ($cartItems as $item): ?>
                    <div style="display: flex; align-items: center; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                        <img src="<?= BASE_URL ?>public/images/products/<?= $item['image'] ?>" 
                             alt="<?= htmlspecialchars($item['name']) ?>" 
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: var(--border-radius);"
                             onerror="this.src='<?= BASE_URL ?>public/images/logo1.png'">
                        
                        <div style="flex: 1; margin-left: 1rem;">
                            <div style="font-weight: 500; margin-bottom: 0.25rem;"><?= htmlspecialchars($item['name']) ?></div>
                            <div style="color: var(--text-secondary); font-size: 0.875rem;">Qty: <?= $item['quantity'] ?></div>
                        </div>
                        
                        <div style="font-weight: 600;">
                            ৳<?= number_format($item['price'] * $item['quantity'], 2) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <!-- Totals -->
                <div style="border-top: 1px solid var(--border-color); padding-top: 1rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span>Subtotal:</span>
                        <span>৳<?= number_format($total, 2) ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span>Delivery:</span>
                        <span>৳50.00</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;" id="discount-row" style="display: none;">
                        <span>Discount:</span>
                        <span id="discount-display">-৳0.00</span>
                    </div>
                    <hr>
                    <div style="display: flex; justify-content: space-between; font-size: 1.125rem; font-weight: 600;">
                        <span>Total:</span>
                        <span id="final-total">৳<?= number_format($total + 50, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// File preview for prescription upload
document.getElementById('prescription')?.addEventListener('change', function() {
    previewFile(this, 'prescription-preview');
});

// Discount code functionality
function applyDiscount() {
    const code = document.getElementById('discount_code').value.trim();
    const discountCodes = {
        'WELCOME10': 0.10, // 10% discount
        'SAVE50': 50,      // ৳50 flat discount
        
    };
    
    if (discountCodes[code]) {
        const subtotal = <?= $total ?>;
        const delivery = 50;
        let discountAmount = 0;
        
        if (discountCodes[code] < 1) {
            // Percentage discount
            discountAmount = subtotal * discountCodes[code];
        } else {
            // Flat discount
            discountAmount = discountCodes[code];
        }
        
        const finalTotal = subtotal + delivery - discountAmount;
        
        document.getElementById('discount_amount').value = discountAmount;
        document.getElementById('discount-display').textContent = `-৳${discountAmount.toFixed(2)}`;
        document.getElementById('final-total').textContent = `৳${finalTotal.toFixed(2)}`;
        document.getElementById('discount-row').style.display = 'flex';
        
        showNotification(`Discount applied: ৳${discountAmount.toFixed(2)} off!`, 'success');
    } else {
        showNotification('Invalid discount code', 'error');
    }
}
</script>

<?php include 'views/layouts/footer.php'; ?>
