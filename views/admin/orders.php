<?php
$title = 'All Orders - MedLagbe';
include 'views/layouts/header.php';
?>

<div class="container" style="padding: 2rem 0;">
	<div class="d-flex justify-content-between align-items-center mb-4">
		<h1>All Orders</h1>
	</div>

	<div class="card">
		<div class="card-header">
			<h3 class="card-title mb-0">Orders</h3>
		</div>
		<div class="card-body">
			<?php if (empty($orders)): ?>
				<p style="color: var(--text-secondary);">No orders found.</p>
			<?php else: ?>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>Order #</th>
								<th>Customer</th>
								<th>Email</th>
								<th>Total</th>
								<th>Date</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($orders as $order): ?>
								<tr>
									<td><?= htmlspecialchars($order['order_number'] ?? ('#' . $order['id'])) ?></td>
									<td><?= htmlspecialchars($order['user_name'] ?? '') ?></td>
									<td><?= htmlspecialchars($order['user_email'] ?? '') ?></td>
									<td>৳<?= number_format((float)$order['total_amount'], 2) ?></td>
									<td><?= htmlspecialchars($order['created_at'] ?? '') ?></td>
									<td>
										<select class="form-control order-status" data-order-id="<?= $order['id'] ?>">
											<?php
												$currentStatus = strtolower($order['status'] ?? 'pending');
												$options = [
													'pending' => 'Pending',
													'verified' => 'Verified',
													'processing' => 'Processing',
													'shipped' => 'Shipped',
													'delivered' => 'Delivered',
													'cancelled' => 'Cancelled',
													'stock_out' => 'Stock Out',
												];
												foreach ($options as $value => $label):
											?>
												<option value="<?= $value ?>" <?= $currentStatus === $value ? 'selected' : '' ?>><?= $label ?></option>
											<?php endforeach; ?>
										</select>
									</td>
									<td>
										<button class="btn btn-sm btn-primary" onclick="saveStatus(<?= $order['id'] ?>, this)">Save</button>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<script>
function saveStatus(orderId, btn) {
	const row = btn.closest('tr');
	const select = row.querySelector('.order-status');
	const status = select.value;

	btn.disabled = true;
	const originalText = btn.textContent;
	btn.textContent = 'Saving...';

	fetch('<?= BASE_URL ?>admin/orders/update_status', {
		method: 'POST',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
		body: 'order_id=' + encodeURIComponent(orderId) + '&status=' + encodeURIComponent(status)
	}).then(r => r.json()).then(data => {
		if (data.success) {
			showNotification('Order status updated', 'success');
		} else {
			showNotification('Failed to update status', 'error');
		}
	}).catch(() => {
		showNotification('Error updating status', 'error');
	}).finally(() => {
		btn.disabled = false;
		btn.textContent = originalText;
	});
}
</script>

<?php include 'views/layouts/footer.php'; ?>


