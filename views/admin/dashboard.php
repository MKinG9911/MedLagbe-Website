<?php
$title = 'Admin Dashboard - MedLagbe';
include 'views/layouts/header.php';
?>

<div class="container" style="padding: 2rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1>Admin Dashboard</h1>
        <div style="color: var(--text-secondary);">
            Welcome, <?= htmlspecialchars($_SESSION['admin_name']) ?>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size: 3rem; margin-bottom: 1rem; color: var(--primary-color);">📦</div>
                <h3><?= $stats['total_products'] ?? 0 ?></h3>
                <p style="color: var(--text-secondary);">Total Products</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size: 3rem; margin-bottom: 1rem; color: var(--success-color);">📋</div>
                <h3><?= $stats['total_orders'] ?? 0 ?></h3>
                <p style="color: var(--text-secondary);">Total Orders</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size: 3rem; margin-bottom: 1rem; color: var(--info-color);">👥</div>
                <h3><?= $stats['total_users'] ?? 0 ?></h3>
                <p style="color: var(--text-secondary);">Registered Users</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size: 3rem; margin-bottom: 1rem; color: var(--warning-color);">💰</div>
                <h3>৳<?= number_format($stats['total_revenue'] ?? 0, 2) ?></h3>
                <p style="color: var(--text-secondary);">Total Revenue</p>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title mb-0">Quick Actions</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <a href="<?= BASE_URL ?>admin/products/add" class="btn btn-primary">+ Add New Product</a>
                <a href="<?= BASE_URL ?>admin/orders" class="btn btn-success">View All Orders</a>
                <a href="<?= BASE_URL ?>admin/users" class="btn btn-info">Manage Users</a>
                <a href="<?= BASE_URL ?>admin/prescriptions" class="btn btn-warning">Review Prescriptions</a>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Recent Orders</h3>
            </div>
            <div class="card-body">
                <?php if (empty($recentOrders)): ?>
                    <p style="color: var(--text-secondary);">No recent orders</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($recentOrders, 0, 5) as $order): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($order['order_number']) ?></td>
                                        <td><?= htmlspecialchars($order['user_name']) ?></td>
                                        <td>৳<?= number_format($order['total_amount'], 2) ?></td>
                                        <td>
                                            <span class="badge badge-<?= getStatusClass($order['status']) ?>">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="<?= BASE_URL ?>admin/orders" class="btn btn-outline">View All Orders</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Low Stock Products -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Low Stock Alert</h3>
            </div>
            <div class="card-body">
                <?php if (empty($lowStockProducts)): ?>
                    <p style="color: var(--text-secondary);">All products have sufficient stock</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Stock</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lowStockProducts as $product): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($product['name']) ?></td>
                                        <td>
                                            <span class="badge badge-danger"><?= $product['stock_quantity'] ?></span>
                                        </td>
                                        <td>
                                            <a href="<?= BASE_URL ?>admin/products/edit?id=<?= $product['id'] ?>" 
                                               class="btn btn-sm btn-outline">Update</a>
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
</div>

<?php
function getStatusClass($status) {
    switch ($status) {
        case 'pending': return 'warning';
        case 'verified': return 'info';
        case 'shipped': return 'primary';
        case 'delivered': return 'success';
        case 'cancelled': return 'danger';
        default: return 'secondary';
    }
}
?>

<?php include 'views/layouts/footer.php'; ?>
