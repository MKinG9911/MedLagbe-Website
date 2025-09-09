<?php
$title = 'Profile - MedLagbe';
include 'views/layouts/header.php';
?>

<div class="container" style="max-width: 1200px; margin: 2rem auto; padding: 0 1rem;">
    <div class="profile-header">
        <h1>My Profile</h1>
        <p>Welcome back, <?= htmlspecialchars($user['name']) ?>!</p>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="profile-tabs">
        <button class="tab-button active" onclick="showTab('profile')">Profile Information</button>
        <button class="tab-button" onclick="showTab('orders')">My Orders</button>
        <button class="tab-button" onclick="showTab('invoices')">Invoices</button>
    </div>

    <!-- Profile Information Tab -->
    <div id="profile" class="tab-content active">
        <div class="card">
            <div class="card-header">
                <h2>Personal Information</h2>
                <p>Update your profile information</p>
            </div>
            <div class="card-body">
                <form method="POST" data-validate>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" id="name" name="name" class="form-control" 
                                   value="<?= htmlspecialchars($user['name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" class="form-control" 
                                   value="<?= htmlspecialchars($user['email']) ?>" readonly>
                            <small class="form-text">Email cannot be changed</small>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="form-control" 
                                   value="<?= htmlspecialchars($user['phone']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="address" class="form-label">Delivery Address</label>
                            <textarea id="address" name="address" class="form-control" rows="3" required><?= htmlspecialchars($user['address']) ?></textarea>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Orders Tab -->
    <div id="orders" class="tab-content">
        <div class="card">
            <div class="card-header">
                <h2>Order History</h2>
                <p>Track your past orders and their status</p>
            </div>
            <div class="card-body">
                <?php if (empty($orders)): ?>
                    <div class="empty-state">
                        <div class="empty-icon">📦</div>
                        <h3>No Orders Yet</h3>
                        <p>You haven't placed any orders yet. Start shopping to see your order history here.</p>
                        <a href="<?= BASE_URL ?>user/products" class="btn btn-primary">Browse Products</a>
                    </div>
                <?php else: ?>
                    <div class="orders-list">
                        <?php foreach ($orders as $order): ?>
                            <div class="order-item">
                                <div class="order-header">
                                    <div class="order-info">
                                        <h4>Order #<?= $order['id'] ?></h4>
                                        <p class="order-date"><?= date('M d, Y', strtotime($order['created_at'])) ?></p>
                                        <p class="order-products"><?= htmlspecialchars($order['product_names']) ?></p>
                                    </div>
                                    <div class="order-status">
                                        <span class="status-badge status-<?= strtolower($order['status']) ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                        <div class="order-actions">
                                            <a href="<?= BASE_URL ?>user/order_detail?id=<?= $order['id'] ?>" 
                                               class="btn btn-sm btn-outline">View Details</a>
                                            <button onclick="downloadInvoice(<?= $order['id'] ?>)" 
                                                    class="btn btn-sm btn-outline">Download Invoice</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="order-summary">
                                    <div class="order-total">
                                        <strong>Total: ৳<?= number_format($order['total_amount'], 2) ?></strong>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Invoices Tab -->
    <div id="invoices" class="tab-content">
        <div class="card">
            <div class="card-header">
                <h2>Invoice Downloads</h2>
                <p>Download invoices for your completed orders</p>
            </div>
            <div class="card-body">
                <?php if (empty($orders)): ?>
                    <div class="empty-state">
                        <div class="empty-icon">📄</div>
                        <h3>No Invoices Available</h3>
                        <p>Invoices will be available here once you complete your first order.</p>
                    </div>
                <?php else: ?>
                    <div class="invoices-list">
                        <?php foreach ($orders as $order): ?>
                            <?php if ($order['status'] === 'completed' || $order['status'] === 'delivered'): ?>
                                <div class="invoice-item">
                                    <div class="invoice-info">
                                        <h4>Invoice #<?= $order['id'] ?></h4>
                                        <p class="invoice-date"><?= date('M d, Y', strtotime($order['created_at'])) ?></p>
                                        <p class="invoice-amount">৳<?= number_format($order['total_amount'], 2) ?></p>
                                    </div>
                                    <div class="invoice-actions">
                                        <button onclick="downloadInvoice(<?= $order['id'] ?>)" 
                                                class="btn btn-primary">Download PDF</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.profile-header {
    text-align: center;
    margin-bottom: 2rem;
}

.profile-header h1 {
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.profile-tabs {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    border-bottom: 2px solid var(--border-color);
}

.tab-button {
    background: none;
    border: none;
    padding: 1rem 2rem;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 500;
    color: var(--text-muted);
    border-bottom: 3px solid transparent;
    transition: all 0.3s ease;
}

.tab-button.active {
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .profile-tabs {
        flex-direction: column;
        gap: 0;
    }
    
    .tab-button {
        border-bottom: 1px solid var(--border-color);
        border-radius: 0;
    }
}

.orders-list, .invoices-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-item, .invoice-item {
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 1.5rem;
    background: var(--bg-secondary);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.order-info h4 {
    margin: 0 0 0.5rem 0;
    color: var(--primary-color);
}

.order-date, .order-products {
    margin: 0.25rem 0;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.order-status {
    text-align: right;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75em;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-processing { background: #cce5ff; color: #004085; }
.status-shipped { background: #d1ecf1; color: #0c5460; }
.status-delivered { background: #d4edda; color: #155724; }
.status-completed { background: #d4edda; color: #155724; }
.status-cancelled { background: #f8d7da; color: #721c24; }

.order-actions {
    margin-top: 0.5rem;
    display: flex;
    gap: 0.5rem;
    flex-direction: column;
}

.order-summary {
    border-top: 1px solid var(--border-color);
    padding-top: 1rem;
    text-align: right;
}

.invoice-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.invoice-info h4 {
    margin: 0 0 0.5rem 0;
    color: var(--primary-color);
}

.invoice-date, .invoice-amount {
    margin: 0.25rem 0;
    color: var(--text-muted);
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    margin-bottom: 0.5rem;
    color: var(--text-muted);
}

.empty-state p {
    color: var(--text-muted);
    margin-bottom: 1.5rem;
}
</style>

<script>
function showTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.remove('active');
    });
    
    // Remove active class from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('active');
    });
    
    // Show selected tab content
    document.getElementById(tabName).classList.add('active');
    
    // Add active class to clicked button
    event.target.classList.add('active');
}

function downloadInvoice(orderId) {
    // For now, we'll redirect to a download endpoint
    // You can implement actual PDF generation later
    window.open(`<?= BASE_URL ?>user/download_invoice?id=${orderId}`, '_blank');
}
</script>

<?php include 'views/layouts/footer.php'; ?>
