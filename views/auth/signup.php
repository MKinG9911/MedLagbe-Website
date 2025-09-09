<?php
$title = 'Sign Up - MedLagbe';
$hideSearch = true;
include 'views/layouts/header.php';
?>

<div class="container" style="max-width: 500px; margin: 2rem auto; padding: 2rem 1rem;">
    <div class="card">
        <div class="card-header text-center">
            <h2 class="card-title">Join MedLagbe</h2>
            <p class="card-text">Create your account</p>
        </div>
        
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" data-validate>
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required minlength="6">
                    <div class="form-text">Password must be at least 6 characters long</div>
                </div>
                
                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="address" class="form-label">Address</label>
                    <textarea id="address" name="address" class="form-control" rows="3"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg">Create Account</button>
            </form>
        </div>
        
        <div class="card-footer text-center">
            <p>Already have an account? <a href="<?= BASE_URL ?>auth/login" style="color: var(--primary-color);">Login</a></p>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
