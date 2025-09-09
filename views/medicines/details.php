<?php
<?php
$page_title = $medicine['name'];
require_once 'includes/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-5">
            <div class="card mb-4">
                <img src="<?php echo !empty($medicine['image_url']) ? htmlspecialchars($medicine['image_url']) : DEFAULT_IMAGE; ?>" alt="<?php echo htmlspecialchars($medicine['name']); ?>" class="card-img-top">
            </div>
        </div>
        
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title"><?php echo htmlspecialchars($medicine['name']); ?></h2>
                    
                    <?php if (!empty($medicine['brand'])): ?>
                        <p class="text-muted">Brand: <?php echo htmlspecialchars($medicine['brand']); ?></p>
                    <?php endif; ?>
                    
                    <h3 class="text-primary">৳<?php echo number_format($medicine['price'], 2); ?></h3>
                    
                    <?php if ($medicine['requires_prescription']): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle"></i> Prescription required for this medicine
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <div class="quantity-control">
                            <button class="btn btn-outline quantity-minus">-</button>
                            <input type="number" class="quantity-input" value="1" min="1" max="<?php echo $medicine['stock']; ?>">
                            <button class="btn btn-outline quantity-plus">+</button>
                        </div>
                        
                        <div>
                            <span class="badge badge-success">In Stock: <?php echo $medicine['stock']; ?></span>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mb-4">
                        <button class="btn btn-primary btn-lg flex-grow-1 add-to-cart" data-medicine-id="<?php echo $medicine['id']; ?>">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button class="btn btn-outline btn-lg add-to-wishlist" data-medicine-id="<?php echo $medicine['id']; ?>">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                    
                    <div class="medicine-details">
                        <h4>Details</h4>
                        <table class="table">
                            <tr>
                                <th>Category</th>
                                <td><?php echo htmlspecialchars(ucfirst($medicine['category'])); ?></td>
                            </tr>
                            <tr>
                                <th>Dosage</th>
                                <td><?php echo !empty($medicine['dosage']) ? htmlspecialchars($medicine['dosage']) : 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <th>Ingredients</th>
                                <td><?php echo !empty($medicine['ingredients']) ? htmlspecialchars($medicine['ingredients']) : 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <th>Expiry Date</th>
                                <td><?php echo !empty($medicine['expiry_date']) ? date('F Y', strtotime($medicine['expiry_date'])) : 'N/A'; ?></td>
                            </tr>
                        </table>
                    </div>
                    
                    <?php if (!empty($medicine['description'])): ?>
                        <div class="medicine-description">
                            <h4>Description</h4>
                            <p><?php echo htmlspecialchars($medicine['description']); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Reviews</h3>
                    
                    <?php if (empty($reviews)): ?>
                        <p>No reviews yet. Be the first to review!</p>
                    <?php else: ?>
                        <div class="reviews-list">
                            <?php foreach ($reviews as $review): ?>
                                <div class="review-item mb-3">
                                    <div class="d-flex justify-content-between">
                                        <h5><?php echo htmlspecialchars($review['username']); ?></h5>
                                        <div class="rating">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star<?php echo $i > $review['rating'] ? '-empty' : ''; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <small class="text-muted"><?php echo date('F j, Y', strtotime($review['review_date'])); ?></small>
                                    <?php if (!empty($review['comment'])): ?>
                                        <p class="mt-2"><?php echo htmlspecialchars($review['comment']); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="add-review mt-4">
                            <h4>Add Your Review</h4>
                            <form id="review-form">
                                <input type="hidden" name="medicine_id" value="<?php echo $medicine['id']; ?>">
                                
                                <div class="form-group">
                                    <label class="form-label">Rating</label>
                                    <div class="rating-input">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="far fa-star" data-rating="<?php echo $i; ?>"></i>
                                        <?php endfor; ?>
                                        <input type="hidden" name="rating" id="rating-value" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="comment" class="form-label">Comment</label>
                                    <textarea id="comment" name="comment" class="form-control" rows="3"></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            Please <a href="<?php echo BASE_URL; ?>login">login</a> to leave a review.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<!-- JavaScript Section -->
<script>
$(document).ready(function() {
    $('.add-to-cart').click(function() {
        const medicineId = $(this).data('medicine-id');
        const quantity = $('.quantity-input').val();

        $.ajax({
            url: '<?php echo BASE_URL; ?>add-to-cart',
            method: 'POST',
            data: {
                medicine_id: medicineId,
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    $('.cart-count').text(response.cartCount);
                    alert('Item added to cart successfully!');
                    // window.location.href = '<?php echo BASE_URL; ?>cart';
                } else {
                    alert(response.message);
                }
            }
        });
    });

    $('.add-to-wishlist').click(function() {
        const medicineId = $(this).data('medicine-id');

        $.ajax({
            url: '<?php echo BASE_URL; ?>add-to-wishlist',
            method: 'POST',
            data: {
                medicine_id: medicineId
            },
            success: function(response) {
                if (response.success) {
                    alert('Item added to wishlist successfully!');
                } else {
                    alert(response.message);
                }
            }
        });
    });

    // Optional: Quantity plus/minus logic
    $('.quantity-plus').click(function() {
        let $input = $('.quantity-input');
        let val = parseInt($input.val());
        let max = parseInt($input.attr('max'));
        if (val < max) $input.val(val + 1);
    });
    $('.quantity-minus').click(function() {
        let $input = $('.quantity-input');
        let val = parseInt($input.val());
        if (val > 1) $input.val(val - 1);
    });
});
</script>