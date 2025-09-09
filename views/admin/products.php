<?php
$title = 'Manage Products - MedLagbe';
include 'views/layouts/header.php';
?>

<div class="container" style="padding: 2rem 0;">
	<div class="d-flex justify-content-between align-items-center mb-4">
		<h1>Products</h1>
		<a href="<?= BASE_URL ?>admin/products/add" class="btn btn-primary">+ Add New Product</a>
	</div>

	<div class="card">
		<div class="card-header">
			<h3 class="card-title mb-0">All Products</h3>
		</div>
		<div class="card-body">
			<?php if (empty($products)): ?>
				<p style="color: var(--text-secondary);">No products found. Click "Add New Product" to create one.</p>
			<?php else: ?>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>Image</th>
								<th>Name</th>
								<th>Brand</th>
								<th>Price</th>
								<th>Stock</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($products as $product): ?>
								<tr>
									<td style="width: 60px;">
										<img src="<?= BASE_URL ?>public/images/products/<?= htmlspecialchars($product['image']) ?>"
											alt="<?= htmlspecialchars($product['name']) ?>"
											style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;"
											onerror="this.src='<?= BASE_URL ?>public/images/logo1.png'">
									</td>
									<td><?= htmlspecialchars($product['name']) ?></td>
									<td><?= htmlspecialchars($product['brand']) ?></td>
									<td>৳<?= number_format((float)$product['price'], 2) ?></td>
									<td>
										<span class="badge badge-<?= (int)$product['stock_quantity'] > 0 ? 'success' : 'danger' ?>">
											<?= (int)$product['stock_quantity'] ?>
										</span>
									</td>
									<td>
										<span class="badge badge-<?= ($product['status'] ?? 'active') === 'active' ? 'primary' : 'secondary' ?>">
											<?= ucfirst($product['status'] ?? 'active') ?>
										</span>
									</td>
									
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="card mt-3">
		<div class="card-header">
			<h3 class="card-title mb-0">Actions</h3>
		</div>
		<div class="card-body">
			<?php if (!empty($products)): ?>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>Name</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($products as $product): ?>
								<tr>
									<td><?= htmlspecialchars($product['name']) ?></td>
									<td>
										<a href="<?= BASE_URL ?>admin/products/edit?id=<?= $product['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
										<button class="btn btn-sm btn-danger" onclick="deleteProduct(<?= $product['id'] ?>)">Delete</button>
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
function deleteProduct(id) {
	if (!confirm('Are you sure you want to delete this product?')) return;
	fetch('<?= BASE_URL ?>admin/products/delete', {
		method: 'POST',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
		body: 'id=' + encodeURIComponent(id)
	}).then(r => r.json()).then(data => {
		if (data.success) {
			location.reload();
		} else {
			alert('Failed to delete product');
		}
	}).catch(() => alert('Error deleting product'));
}
</script>

<?php include 'views/layouts/footer.php'; ?>

								</tr>

						</tbody>
					</table>
				</div>

		</div>
	</div>
</div>

<?php include 'views/layouts/footer.php'; ?>

