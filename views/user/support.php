<?php
$title = 'Support - MedLagbe';
include 'views/layouts/header.php';
?>

<div class="container" style="max-width: 800px; margin: 2rem auto; padding: 2rem 1rem;">
    <div class="card">
        <div class="card-header text-center">
            <h2 class="card-title">Contact Support</h2>
            <p class="card-text">We're here to help you with any questions or issues</p>
        </div>
        
        <div class="card-body">
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" data-validate>
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" id="subject" name="subject" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="message" class="form-label">Message</label>
                    <textarea id="message" name="message" class="form-control" rows="6" required 
                              placeholder="Please describe your issue or question in detail..."></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
            </form>
        </div>
        
        <div class="card-footer">
            <h4>Other Ways to Contact Us</h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <div>
                    <strong>📧 Email:</strong><br>
                    support@medlagbe.com
                </div>
                <div>
                    <strong>📞 Phone:</strong><br>
                    +880 1234-567890
                </div>
                <div>
                    <strong>🕒 Hours:</strong><br>
                    Mon-Sat: 9AM-6PM
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
