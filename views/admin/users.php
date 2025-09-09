<?php
$title = 'Manage Users - MedLagbe';
include 'views/layouts/header.php';
?>

<div class="container" style="padding: 2rem 0;">
	<div class="d-flex justify-content-between align-items-center mb-4">
		<h1>Users</h1>
	</div>

	<div class="card">
		<div class="card-header">
			<h3 class="card-title mb-0">All Users</h3>
		</div>
		<div class="card-body">
			<?php if (empty($users)): ?>
				<p style="color: var(--text-secondary);">No users found.</p>
			<?php else: ?>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>Name</th>
								<th>Email</th>
								<th>Phone</th>
								<th>Address</th>
								<th>Joined</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($users as $user): ?>
								<tr>
									<td><?= htmlspecialchars($user['name']) ?></td>
									<td><?= htmlspecialchars($user['email']) ?></td>
									<td><?= htmlspecialchars($user['phone']) ?></td>
									<td><?= htmlspecialchars($user['address']) ?></td>
									<td><?= htmlspecialchars($user['created_at'] ?? '') ?></td>
									<td>
										<a class="btn btn-sm btn-outline" href="<?= BASE_URL ?>admin/users/edit?id=<?= $user['id'] ?>">Edit</a>
										<button class="btn btn-sm btn-danger" onclick="deleteUser(<?= $user['id'] ?>)">Delete</button>
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
function deleteUser(id) {
	if (!confirm('Are you sure you want to delete this user?')) return;
	fetch('<?= BASE_URL ?>admin/users/delete', {
		method: 'POST',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
		body: 'id=' + encodeURIComponent(id)
	}).then(r => r.json()).then(data => {
		if (data.success) {
			showNotification('User deleted', 'success');
			location.reload();
		} else {
			showNotification('Failed to delete user', 'error');
		}
	}).catch(() => showNotification('Error deleting user', 'error'));
}
</script>

<?php include 'views/layouts/footer.php'; ?>


