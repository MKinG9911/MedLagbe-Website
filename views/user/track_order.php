<?php
$title = 'Track Order - MedLagbe';
include 'views/layouts/header.php';

// Get the first item to access order details
$order = $orderItems[0] ?? null;
if (!$order) {
    header('Location: ' . BASE_URL . 'user/orders');
    exit();
}
?>

<div class="container" style="max-width: 800px; margin: 2rem auto; padding: 0 1rem;">
    <div class="tracking-header">
        <a href="<?= BASE_URL ?>user/orders" class="back-link">← Back to Orders</a>
        <h1>Track Your Order</h1>
        <div class="order-info">
            <p class="order-number">Order #<?= $order['id'] ?></p>
            <p class="order-date">Placed on <?= date('F d, Y \a\t g:i A', strtotime($order['created_at'])) ?></p>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="card">
        <div class="card-header">
            <h2>Order Summary</h2>
        </div>
        <div class="card-body">
            <div class="order-summary-grid">
                <div class="summary-item">
                    <label>Total Amount:</label>
                    <span class="amount">৳<?= number_format($order['total_amount'], 2) ?></span>
                </div>
                <div class="summary-item">
                    <label>Items:</label>
                    <span><?= count($orderItems) ?> product<?= count($orderItems) > 1 ? 's' : '' ?></span>
                </div>
                <div class="summary-item">
                    <label>Current Status:</label>
                    <span class="status-badge status-<?= strtolower($order['status']) ?>">
                        <?= ucfirst($order['status']) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tracking Timeline -->
    <div class="card">
        <div class="card-header">
            <h2>Order Progress</h2>
        </div>
        <div class="card-body">
            <div class="tracking-timeline">
                <?php
                $statuses = [
                    'pending' => [
                        'title' => 'Order Placed',
                        'description' => 'Your order has been successfully placed and is awaiting verification',
                        'icon' => '📋',
                        'color' => '#007bff'
                    ],
                    'verified' => [
                        'title' => 'Order Verified',
                        'description' => 'Your order has been verified and confirmed by our team',
                        'icon' => '✅',
                        'color' => '#28a745'
                    ],
                    'processing' => [
                        'title' => 'Processing',
                        'description' => 'Your order is being prepared and packaged for shipment',
                        'icon' => '⚙️',
                        'color' => '#ffc107'
                    ],
                    'shipped' => [
                        'title' => 'Shipped',
                        'description' => 'Your order has been shipped and is on its way to you',
                        'icon' => '🚚',
                        'color' => '#17a2b8'
                    ],
                    'delivered' => [
                        'title' => 'Delivered',
                        'description' => 'Your order has been successfully delivered to your address',
                        'icon' => '📦',
                        'color' => '#28a745'
                    ],
                    'cancelled' => [
                        'title' => 'Cancelled',
                        'description' => 'Your order has been cancelled',
                        'icon' => '❌',
                        'color' => '#dc3545'
                    ]
                ];

                $currentStatus = strtolower($order['status'] ?? 'pending');
                $statusOrder = ['pending', 'verified', 'processing', 'shipped', 'delivered'];
                $cancelledIndex = array_search('cancelled', $statusOrder);
                
                if ($cancelledIndex !== false) {
                    unset($statusOrder[$cancelledIndex]);
                }

                foreach ($statusOrder as $index => $status):
                    $isCompleted = array_search($status, $statusOrder) <= array_search($currentStatus, $statusOrder);
                    $isCurrent = $status === $currentStatus;
                    $statusInfo = $statuses[$status] ?? $statuses['pending'];
                ?>
                    <div class="timeline-item <?= $isCompleted ? 'completed' : '' ?> <?= $isCurrent ? 'current' : '' ?>">
                        <div class="timeline-icon" style="background-color: <?= $isCompleted ? $statusInfo['color'] : '#e9ecef' ?>">
                            <span class="timeline-icon-text"><?= $statusInfo['icon'] ?></span>
                        </div>
                        <div class="timeline-content">
                            <h4 class="timeline-title"><?= $statusInfo['title'] ?></h4>
                            <p class="timeline-description"><?= $statusInfo['description'] ?></p>
                            <?php if ($isCurrent): ?>
                                <span class="timeline-status current">Current Status</span>
                            <?php elseif ($isCompleted): ?>
                                <span class="timeline-status completed">Completed</span>
                            <?php else: ?>
                                <span class="timeline-status pending">Pending</span>
                            <?php endif; ?>
                        </div>
                        <?php if ($index < count($statusOrder) - 1): ?>
                            <div class="timeline-connector <?= $isCompleted ? 'completed' : '' ?>"></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <?php if ($currentStatus === 'cancelled'): ?>
                    <div class="timeline-item completed current">
                        <div class="timeline-icon" style="background-color: <?= $statuses['cancelled']['color'] ?>">
                            <span class="timeline-icon-text"><?= $statuses['cancelled']['icon'] ?></span>
                        </div>
                        <div class="timeline-content">
                            <h4 class="timeline-title"><?= $statuses['cancelled']['title'] ?></h4>
                            <p class="timeline-description"><?= $statuses['cancelled']['description'] ?></p>
                            <span class="timeline-status cancelled">Order Cancelled</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Order Items Preview -->
    <div class="card">
        <div class="card-header">
            <h2>Order Items</h2>
        </div>
        <div class="card-body">
            <div class="order-items-preview">
                <?php foreach (array_slice($orderItems, 0, 3) as $item): ?>
                    <div class="item-preview">
                        <div class="item-image">
                            <?php if ($item['product_image']): ?>
                                <img src="<?= BASE_URL ?>public/images/products/<?= htmlspecialchars($item['product_image']) ?>" 
                                     alt="<?= htmlspecialchars($item['product_name']) ?>">
                            <?php else: ?>
                                <div class="no-image">📦</div>
                            <?php endif; ?>
                        </div>
                        <div class="item-info">
                            <h4><?= htmlspecialchars($item['product_name']) ?></h4>
                            <p>Qty: <?= $item['quantity'] ?> × ৳<?= number_format($item['item_price'], 2) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (count($orderItems) > 3): ?>
                    <div class="more-items">
                        <p>+<?= count($orderItems) - 3 ?> more items</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="tracking-actions">
        <a href="<?= BASE_URL ?>user/order_detail?id=<?= $order['id'] ?>" class="btn btn-primary">
            📋 View Full Order Details
        </a>
        <button onclick="downloadInvoice(<?= $order['id'] ?>)" class="btn btn-outline">
            📄 Download Invoice
        </button>
        <a href="<?= BASE_URL ?>user/support" class="btn btn-outline">
            🆘 Contact Support
        </a>
    </div>
</div>

<style>
.tracking-header {
    text-align: center;
    margin-bottom: 2rem;
}

.back-link {
    display: inline-block;
    color: var(--primary-color);
    text-decoration: none;
    margin-bottom: 1rem;
    font-weight: 500;
}

.back-link:hover {
    text-decoration: underline;
}

.tracking-header h1 {
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.order-info {
    margin-bottom: 1rem;
}

.order-number {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 0.5rem;
}

.order-date {
    color: var(--text-muted);
    margin: 0;
}

.order-summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.summary-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.summary-item label {
    font-weight: 600;
    color: var(--text-muted);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.summary-item span {
    font-size: 1.1rem;
    color: var(--text-color);
}

.amount {
    font-size: 1.5rem !important;
    font-weight: bold;
    color: var(--primary-color) !important;
}

/* Timeline Styles */
.tracking-timeline {
    position: relative;
    padding: 1rem 0;
}

.timeline-item {
    position: relative;
    display: flex;
    align-items: flex-start;
    margin-bottom: 2rem;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-right: 2rem;
    border: 3px solid #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

.timeline-icon-text {
    font-size: 1.5rem;
}

.timeline-item.completed .timeline-icon {
    transform: scale(1.1);
}

.timeline-item.current .timeline-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.timeline-content {
    flex: 1;
    padding-top: 0.5rem;
}

.timeline-title {
    margin: 0 0 0.5rem 0;
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--text-color);
}

.timeline-description {
    margin: 0 0 0.5rem 0;
    color: var(--text-muted);
    font-size: 1rem;
    line-height: 1.5;
}

.timeline-status {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
    background: #e9ecef;
    color: #6c757d;
}

.timeline-status.completed {
    background: #d4edda;
    color: #155724;
}

.timeline-status.current {
    background: #cce5ff;
    color: #004085;
    animation: pulse 2s infinite;
}

.timeline-status.pending {
    background: #fff3cd;
    color: #856404;
}

.timeline-status.cancelled {
    background: #f8d7da;
    color: #721c24;
}

.timeline-connector {
    position: absolute;
    left: 30px;
    top: 60px;
    width: 3px;
    height: calc(100% + 1rem);
    background: #e9ecef;
    z-index: -1;
}

.timeline-connector.completed {
    background: linear-gradient(to bottom, #28a745, #e9ecef);
}

.timeline-item:last-child .timeline-connector {
    display: none;
}

/* Order Items Preview */
.order-items-preview {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.item-preview {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    background: var(--bg-secondary);
}

.item-image {
    width: 60px;
    height: 60px;
    flex-shrink: 0;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
}

.no-image {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--border-color);
    border-radius: 8px;
    font-size: 1.5rem;
    color: var(--text-muted);
}

.item-info h4 {
    margin: 0 0 0.25rem 0;
    font-size: 1rem;
    color: var(--text-color);
}

.item-info p {
    margin: 0;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.more-items {
    text-align: center;
    padding: 1rem;
    color: var(--text-muted);
    font-style: italic;
}

/* Action Buttons */
.tracking-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .order-summary-grid {
        grid-template-columns: 1fr;
    }
    
    .timeline-item {
        flex-direction: column;
        text-align: center;
    }
    
    .timeline-icon {
        margin-right: 0;
        margin-bottom: 1rem;
        align-self: center;
    }
    
    .timeline-connector {
        left: 50%;
        transform: translateX(-50%);
    }
    
    .tracking-actions {
        flex-direction: column;
    }
    
    .item-preview {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script>
function downloadInvoice(orderId) {
    window.open(`<?= BASE_URL ?>user/download_invoice?id=${orderId}`, '_blank');
}
</script>

<?php include 'views/layouts/footer.php'; ?>
