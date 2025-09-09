<?php
$title = 'My Orders - MedLagbe';
include 'views/layouts/header.php';
?>

<div class="container" style="padding: 2rem 0;">
	<h1>My Orders</h1>

	<?php if (isset($flash) && !empty($flash['message'])): ?>
		<div class="alert alert-<?= htmlspecialchars($flash['type'] ?? 'success') ?>">
			<?= htmlspecialchars($flash['message']) ?>
		</div>
		<script>
			window.addEventListener('DOMContentLoaded', function() {
				if (typeof showNotification === 'function') {
					showNotification('<?= htmlspecialchars($flash['message'], ENT_QUOTES) ?>', '<?= htmlspecialchars($flash['type'] ?? 'success', ENT_QUOTES) ?>');
				}
			});
		</script>
	<?php endif; ?>

	<?php if (empty($orders)): ?>
		<p style="color: var(--text-secondary);">You have no orders yet.</p>
	<?php else: ?>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
					    <th>Products</th>
						<th>Order #</th>
						<th>Date</th>
						<th>Total</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($orders as $order): ?>
						<tr>
						    <td><?= htmlspecialchars($order['product_names'] ?? '') ?></td>
							<td><?= htmlspecialchars($order['order_number'] ?? ('#' . $order['id'])) ?></td>
							<td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
							<td>৳<?= number_format((float)$order['total_amount'], 2) ?></td>
							<td>
								<span class="status-badge status-<?= strtolower($order['status'] ?? 'pending') ?>">
									<?= ucfirst($order['status'] ?? 'pending') ?>
								</span>
							</td>
							<td>
								<a href="<?= BASE_URL ?>user/order_detail?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline">
									View Details
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>

<style>
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-verified { background: #d1ecf1; color: #0c5460; }
.status-processing { background: #cce5ff; color: #004085; }
.status-shipped { background: #d1ecf1; color: #0c5460; }
.status-delivered { background: #d4edda; color: #155724; }
.status-completed { background: #d4edda; color: #155724; }
.status-cancelled { background: #f8d7da; color: #721c24; }
.status-stock_out { background: #f8d7da; color: #721c24; }

.table-responsive {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.table {
    margin-bottom: 0;
}

.table th {
    background: var(--primary-color);
    color: white;
    border: none;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
    border-color: var(--border-color);
}

.btn-sm {
    padding: 0.25rem 0.75rem;
    font-size: 0.8rem;
}
</style>

<?php include 'views/layouts/footer.php'; ?>


