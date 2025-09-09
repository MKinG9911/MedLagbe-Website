<?php
$title = 'Edit Product - MedLagbe';
include 'views/layouts/header.php';
?>

<div class="container" style="padding: 2rem 0;">
	<div class="d-flex justify-content-between align-items-center mb-4">
		<h1>Edit Product</h1>
		<a href="<?= BASE_URL ?>admin/products" class="btn btn-secondary">Back to Products</a>
	</div>

	<?php if (!empty($error)): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
	<?php endif; ?>

	<div class="card">
		<div class="card-body">
			<form method="POST" enctype="multipart/form-data" data-validate>
				<input type="hidden" name="current_image" value="<?= htmlspecialchars($product['image']) ?>">
				<div class="grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
					<div class="form-group">
						<label class="form-label">Name</label>
						<input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
					</div>

					<div class="form-group">
						<label class="form-label">Brand</label>
						<input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($product['brand']) ?>" required>
					</div>

					<div class="form-group">
						<label class="form-label">Category</label>
						<input type="number" name="category_id" class="form-control" min="1" value="<?= htmlspecialchars($product['category_id']) ?>" required>
					</div>

					<div class="form-group">
						<label class="form-label">Price (৳)</label>
						<input type="number" name="price" class="form-control" step="0.01" min="0" value="<?= htmlspecialchars($product['price']) ?>" required>
					</div>

					<div class="form-group">
						<label class="form-label">Stock Quantity</label>
						<input type="number" name="stock_quantity" class="form-control" min="0" value="<?= htmlspecialchars($product['stock_quantity']) ?>" required>
					</div>

					<div class="form-group">
						<label class="form-label">Expiry Date</label>
						<input type="date" name="expiry_date" class="form-control" value="<?= htmlspecialchars($product['expiry_date']) ?>" required>
					</div>
				</div>

				<div class="form-group">
					<label class="form-label">Dosage</label>
					<input type="text" name="dosage" class="form-control" value="<?= htmlspecialchars($product['dosage']) ?>">
				</div>

				<div class="form-group">
					<label class="form-label">Ingredients</label>
					<input type="text" name="ingredients" class="form-control" value="<?= htmlspecialchars($product['ingredients']) ?>">
				</div>

				<div class="form-group">
					<label class="form-label">Description</label>
					<textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
				</div>

				<div class="form-group">
					<label class="form-label">Current Image</label>
					<div class="d-flex align-items-center gap-2">
						<img src="<?= BASE_URL ?>public/images/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;" onerror="this.src='<?= BASE_URL ?>public/images/logo1.png'">
					</div>
				</div>

				<div class="form-group">
					<label class="form-label">Change Image</label>
					<input type="file" name="image" class="form-control" accept="image/*">
				</div>

				<div class="form-group">
					<label class="form-checkbox">
						<input type="checkbox" name="prescription_required" value="1" <?= $product['prescription_required'] ? 'checked' : '' ?>> Requires Prescription
					</label>
				</div>

				<div class="form-group">
					<label class="form-label">Status</label>
					<select name="status" class="form-control">
						<option value="active" <?= ($product['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
						<option value="inactive" <?= ($product['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
					</select>
				</div>

				<button type="submit" class="btn btn-primary">Save Changes</button>
			</form>
		</div>
	</div>
</div>

<?php include 'views/layouts/footer.php'; ?>


