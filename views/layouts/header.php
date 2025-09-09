<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <script>
        (function() {
            try {
                var savedTheme = localStorage.getItem('theme');
                if (savedTheme) {
                    document.documentElement.setAttribute('data-theme', savedTheme);
                }
            } catch (e) {}
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'MedLagbe - Online Pharmacy' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="<?= BASE_URL ?>" class="logo">MedLagbe</a>
                
                <?php if (!isset($hideSearch)): ?>
                <div class="search-container">
                    <form method="GET" action="<?= BASE_URL ?>user/products">
                        <input type="text" name="search" class="search-input" 
                               placeholder="Search medicines..." 
                               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <span class="search-icon">🔍</span>
                    </form>
                </div>
                <?php endif; ?>
                
                <div class="header-actions">
                    <button class="theme-toggle" title="Toggle Theme">🌙</button>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <nav class="nav">
                            <a href="<?= BASE_URL ?>user/home" class="nav-link">Home</a>
                            <a href="<?= BASE_URL ?>user/products" class="nav-link">Products</a>
                            <a href="<?= BASE_URL ?>user/orders" class="nav-link">Orders</a>
                            <a href="<?= BASE_URL ?>user/profile" class="nav-link"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Profile') ?></a>
                            <a href="<?= BASE_URL ?>cart" class="nav-link cart-icon">
                                🛒
                                <?php if (isset($cartCount) && $cartCount > 0): ?>
                                    <span class="cart-count"><?= $cartCount ?></span>
                                <?php endif; ?>
                            </a>
                            <a href="<?= BASE_URL ?>auth/logout" class="nav-link">Logout</a>
                        </nav>
                    <?php elseif (isset($_SESSION['admin_id'])): ?>
                        <nav class="nav">
                            <a href="<?= BASE_URL ?>admin/dashboard" class="nav-link">Dashboard</a>
                            <a href="<?= BASE_URL ?>admin/products" class="nav-link">Products</a>
                            <a href="<?= BASE_URL ?>admin/orders" class="nav-link">Orders</a>
                            <a href="<?= BASE_URL ?>admin/users" class="nav-link">Users</a>
                            <a href="<?= BASE_URL ?>auth/admin_logout" class="nav-link">Logout</a>
                        </nav>
                    <?php else: ?>
                        <nav class="nav">
                            <a href="<?= BASE_URL ?>" class="nav-link">Home</a>
                            <a href="<?= BASE_URL ?>user/products" class="nav-link">Products</a>
                            <a href="<?= BASE_URL ?>auth/login" class="nav-link">Login</a>
                            <a href="<?= BASE_URL ?>auth/signup" class="nav-link">Sign Up</a>
                            <a href="<?= BASE_URL ?>auth/admin_login" class="nav-link">Admin</a>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    
    <main>
