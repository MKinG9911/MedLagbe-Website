<?php
$title = 'Login - MedLagbe';
$hideSearch = true;
include 'views/layouts/header.php';
?>

<div class="container" style="max-width: 400px; margin: 2rem auto; padding: 2rem 1rem;">
    <div class="card">
        <div class="card-header text-center">
            <h2 class="card-title">Login to MedLagbe</h2>
            <p class="card-text">Access your account</p>
        </div>
        
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" data-validate>
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg">Login</button>
            </form>
        </div>
        
        <div class="card-footer text-center">
            <p>Don't have an account? <a href="<?= BASE_URL ?>auth/signup" style="color: var(--primary-color);">Sign up</a></p>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
