<?php
$title = 'Add Product - MedLagbe';
include 'views/layouts/header.php';
?>

<div class="container" style="padding: 2rem 0;">
	<div class="d-flex justify-content-between align-items-center mb-4">
		<h1>Add New Product</h1>
		<a href="<?= BASE_URL ?>admin/products" class="btn btn-secondary">Back to Products</a>
	</div>

	<?php if (!empty($error)): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
	<?php endif; ?>

	<div class="card">
		<div class="card-body">
			<form method="POST" enctype="multipart/form-data" data-validate>
				<div class="grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
					<div class="form-group">
						<label class="form-label">Name</label>
						<input type="text" name="name" class="form-control" required>
					</div>

					<div class="form-group">
						<label class="form-label">Brand</label>
						<input type="text" name="brand" class="form-control" required>
					</div>

					<div class="form-group">
						<label class="form-label">Category</label>
						<input type="number" name="category_id" class="form-control" min="1" required>
					</div>

					<div class="form-group">
						<label class="form-label">Price (৳)</label>
						<input type="number" name="price" class="form-control" step="0.01" min="0" required>
					</div>

					<div class="form-group">
						<label class="form-label">Stock Quantity</label>
						<input type="number" name="stock_quantity" class="form-control" min="0" required>
					</div>

					<div class="form-group">
						<label class="form-label">Expiry Date</label>
						<input type="date" name="expiry_date" class="form-control" required>
					</div>
				</div>

				<div class="form-group">
					<label class="form-label">Dosage</label>
					<input type="text" name="dosage" class="form-control">
				</div>

				<div class="form-group">
					<label class="form-label">Ingredients</label>
					<input type="text" name="ingredients" class="form-control">
				</div>

				<div class="form-group">
					<label class="form-label">Description</label>
					<textarea name="description" class="form-control" rows="4"></textarea>
				</div>

				<div class="form-group">
					<label class="form-label">Product Image</label>
					<input type="file" name="image" class="form-control" accept="image/*">
				</div>

				<div class="form-group">
					<label class="form-checkbox">
						<input type="checkbox" name="prescription_required" value="1"> Requires Prescription
					</label>
				</div>

				<button type="submit" class="btn btn-primary">Create Product</button>
			</form>
		</div>
	</div>
</div>

<?php include 'views/layouts/footer.php'; ?>


