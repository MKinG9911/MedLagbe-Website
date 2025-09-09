<?php
$title = 'Order Details - MedLagbe';
include 'views/layouts/header.php';

// Get the first item to access order details
$order = $orderItems[0] ?? null;
if (!$order) {
    header('Location: ' . BASE_URL . 'user/orders');
    exit();
}
?>

<div class="container" style="max-width: 1000px; margin: 2rem auto; padding: 0 1rem;">
    <div class="order-detail-header">
        <a href="<?= BASE_URL ?>user/orders" class="back-link">← Back to Orders</a>
        <h1>Order Details</h1>
        <p>Order #<?= $order['id'] ?></p>
    </div>

    <!-- Order Summary Card -->
    <div class="card">
        <div class="card-header">
            <h2>Order Summary</h2>
        </div>
        <div class="card-body">
            <div class="order-info-grid">
                <div class="order-info-item">
                    <label>Order ID:</label>
                    <span>#<?= $order['id'] ?></span>
                </div>
                <div class="order-info-item">
                    <label>Order Date:</label>
                    <span><?= date('F d, Y \a\t g:i A', strtotime($order['created_at'])) ?></span>
                </div>
                <div class="order-info-item">
                    <label>Status:</label>
                    <span class="status-badge status-<?= strtolower($order['status']) ?>">
                        <?= ucfirst($order['status']) ?>
                    </span>
                </div>
                <div class="order-info-item">
                    <label>Total Amount:</label>
                    <span class="total-amount">৳<?= number_format($order['total_amount'], 2) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Tracking Timeline -->
    <div class="card">
        <div class="card-header">
            <h2>Order Tracking</h2>
        </div>
        <div class="card-body">
            <div class="tracking-timeline">
                <?php
                $statuses = [
                    'pending' => [
                        'title' => 'Order Placed',
                        'description' => 'Your order has been successfully placed',
                        'icon' => '📋',
                        'color' => '#007bff'
                    ],
                    'verified' => [
                        'title' => 'Order Verified',
                        'description' => 'Your order has been verified and confirmed',
                        'icon' => '✅',
                        'color' => '#28a745'
                    ],
                    'processing' => [
                        'title' => 'Processing',
                        'description' => 'Your order is being prepared for shipment',
                        'icon' => '⚙️',
                        'color' => '#ffc107'
                    ],
                    'shipped' => [
                        'title' => 'Shipped',
                        'description' => 'Your order has been shipped and is on its way',
                        'icon' => '🚚',
                        'color' => '#17a2b8'
                    ],
                    'delivered' => [
                        'title' => 'Delivered',
                        'description' => 'Your order has been successfully delivered',
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
                                <span class="timeline-status">Current Status</span>
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
                            <span class="timeline-status">Order Cancelled</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="card">
        <div class="card-header">
            <h2>Order Items</h2>
        </div>
        <div class="card-body">
            <div class="order-items">
                <?php foreach ($orderItems as $item): ?>
                    <div class="order-item">
                        <div class="item-image">
                            <?php if ($item['product_image']): ?>
                                <img src="<?= BASE_URL ?>public/images/products/<?= htmlspecialchars($item['product_image']) ?>" 
                                     alt="<?= htmlspecialchars($item['product_name']) ?>">
                            <?php else: ?>
                                <div class="no-image">📦</div>
                            <?php endif; ?>
                        </div>
                        <div class="item-details">
                            <h3><?= htmlspecialchars($item['product_name']) ?></h3>
                            <div class="item-meta">
                                <span class="quantity">Quantity: <?= $item['quantity'] ?></span>
                                <span class="price">৳<?= number_format($item['item_price'], 2) ?> each</span>
                            </div>
                        </div>
                        <div class="item-total">
                            <strong>৳<?= number_format($item['item_price'] * $item['quantity'], 2) ?></strong>
                        </div>
                        <div class="item-reminder">
                            <button class="btn btn-sm btn-outline" onclick="toggleReminderForm(<?= (int)$order['id'] ?>, <?= (int)$item['product_id'] ?>, this)">⏰ Set Reminder</button>
                            <form class="reminder-form" style="display:none" onsubmit="return submitReminder(event, <?= (int)$order['id'] ?>, <?= (int)$item['product_id'] ?>)">
                                <div class="reminder-grid">
                                    <label>
                                        Times/day
                                        <select name="times_per_day">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                    </label>
                                    <label>
                                        Specific times (HH:MM, comma-separated)
                                        <input type="text" name="specific_times" placeholder="08:00, 14:00, 20:00">
                                    </label>
                                    <label>
                                        Start date
                                        <input type="date" name="start_date" value="<?= date('Y-m-d') ?>">
                                    </label>
                                    <label>
                                        End date (optional)
                                        <input type="date" name="end_date">
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Save Reminder</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Order Actions -->
    <div class="card">
        <div class="card-header">
            <h2>Order Actions</h2>
        </div>
        <div class="card-body">
            <div class="order-actions">
                <button onclick="downloadInvoice(<?= $order['id'] ?>)" class="btn btn-primary">
                    📄 Download Invoice
                </button>
                <a href="<?= BASE_URL ?>user/support" class="btn btn-outline">
                    🆘 Contact Support
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.order-detail-header {
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

.order-detail-header h1 {
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.order-detail-header p {
    color: var(--text-muted);
    font-size: 1.1rem;
}

.order-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.order-info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.order-info-item label {
    font-weight: 600;
    color: var(--text-muted);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.order-info-item span {
    font-size: 1.1rem;
    color: var(--text-color);
}

.total-amount {
    font-size: 1.5rem !important;
    font-weight: bold;
    color: var(--primary-color) !important;
}

.status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    text-transform: uppercase;
    width: fit-content;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-processing { background: #cce5ff; color: #004085; }
.status-shipped { background: #d1ecf1; color: #0c5460; }
.status-delivered { background: #d4edda; color: #155724; }
.status-completed { background: #d4edda; color: #155724; }
.status-cancelled { background: #f8d7da; color: #721c24; }

.order-items {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    background: var(--bg-secondary);
}

.item-image {
    width: 80px;
    height: 80px;
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
    font-size: 2rem;
    color: var(--text-muted);
}

.item-details {
    flex: 1;
}

.item-details h3 {
    margin: 0 0 0.5rem 0;
    color: var(--text-color);
    font-size: 1.1rem;
}

.item-meta {
    display: flex;
    gap: 1rem;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.item-total {
    font-size: 1.2rem;
    color: var(--primary-color);
    font-weight: 600;
}

.item-reminder {
    margin-left: auto;
}

.reminder-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 0.5rem 1rem;
    margin: 0.5rem 0;
}

.order-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
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
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-right: 1.5rem;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.timeline-icon-text {
    font-size: 1.2rem;
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
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-color);
}

.timeline-description {
    margin: 0 0 0.5rem 0;
    color: var(--text-muted);
    font-size: 0.9rem;
    line-height: 1.4;
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

.timeline-status.pending {
    background: #fff3cd;
    color: #856404;
}

.timeline-connector {
    position: absolute;
    left: 25px;
    top: 50px;
    width: 2px;
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

@media (max-width: 768px) {
    .order-info-grid {
        grid-template-columns: 1fr;
    }
    
    .order-item {
        flex-direction: column;
        text-align: center;
    }
    
    .item-meta {
        justify-content: center;
    }
    
    .order-actions {
        flex-direction: column;
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
}
</style>

<script>
function downloadInvoice(orderId) {
    window.open(`<?= BASE_URL ?>user/download_invoice?id=${orderId}`, '_blank');
}

function toggleReminderForm(orderId, productId, btn) {
    const form = btn.nextElementSibling;
    if (form) {
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
}

async function submitReminder(e, orderId, productId) {
    e.preventDefault();
    const form = e.target;
    const fd = new FormData(form);
    fd.append('order_id', orderId);
    fd.append('product_id', productId);
    try {
        const res = await fetch('<?= BASE_URL ?>reminder/create', { method: 'POST', body: fd });
        const data = await res.json();
        if (data && data.success) {
            if (typeof showNotification === 'function') {
                showNotification('Reminder saved successfully', 'success');
            }
            form.style.display = 'none';
            form.reset();
        } else {
            if (typeof showNotification === 'function') {
                showNotification('Failed to save reminder', 'danger');
            }
        }
    } catch (err) {
        if (typeof showNotification === 'function') {
            showNotification('Network error while saving reminder', 'danger');
        }
    }
    return false;
}
</script>

<?php include 'views/layouts/footer.php'; ?>
