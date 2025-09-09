<?php
$title = 'Edit User - MedLagbe';
include 'views/layouts/header.php';
?>

<div class="container" style="padding: 2rem 0;">
	<div class="d-flex justify-content-between align-items-center mb-4">
		<h1>Edit User</h1>
		<a href="<?= BASE_URL ?>admin/users" class="btn btn-secondary">Back to Users</a>
	</div>

	<?php if (!empty($error)): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
	<?php endif; ?>

	<div class="card">
		<div class="card-body">
			<form method="POST" data-validate>
				<div class="grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
					<div class="form-group">
						<label class="form-label">Name</label>
						<input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
					</div>

					<div class="form-group">
						<label class="form-label">Phone</label>
						<input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>">
					</div>

					<div class="form-group" style="grid-column: 1 / -1;">
						<label class="form-label">Address</label>
						<input type="text" name="address" class="form-control" value="<?= htmlspecialchars($user['address']) ?>">
					</div>
				</div>

				<hr>
				<h3 class="mb-2">Change Password</h3>
				<div class="grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
					<div class="form-group">
						<label class="form-label">New Password</label>
						<input type="password" name="password" class="form-control" placeholder="Leave empty to keep current">
					</div>
					<div class="form-group">
						<label class="form-label">Confirm Password</label>
						<input type="password" name="password_confirm" class="form-control" placeholder="Repeat new password">
					</div>
				</div>

				<button type="submit" class="btn btn-primary">Save Changes</button>
			</form>
		</div>
	</div>
</div>

<?php include 'views/layouts/footer.php'; ?>


