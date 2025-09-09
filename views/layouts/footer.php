</main>
    
    <footer class="footer" style="background-color: var(--bg-secondary); border-top: 1px solid var(--border-color); margin-top: 4rem; padding: 3rem 0 1rem;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">MedLagbe</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 1rem;">Your trusted online pharmacy for quality medicines and healthcare products.</p>
                    <div style="display: flex; gap: 1rem;">
                        <a href="#" style="color: var(--text-secondary); text-decoration: none;">📘</a>
                        <a href="#" style="color: var(--text-secondary); text-decoration: none;">📷</a>
                        <a href="#" style="color: var(--text-secondary); text-decoration: none;">🐦</a>
                    </div>
                </div>
                
                <div>
                    <h4 style="margin-bottom: 1rem;">Quick Links</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 0.5rem;"><a href="<?= BASE_URL ?>user/products" style="color: var(--text-secondary); text-decoration: none;">Products</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="<?= BASE_URL ?>user/support" style="color: var(--text-secondary); text-decoration: none;">Support</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="#" style="color: var(--text-secondary); text-decoration: none;">About Us</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="#" style="color: var(--text-secondary); text-decoration: none;">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 style="margin-bottom: 1rem;">Categories</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 0.5rem;"><a href="#" style="color: var(--text-secondary); text-decoration: none;">Tablets</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="#" style="color: var(--text-secondary); text-decoration: none;">Syrups</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="#" style="color: var(--text-secondary); text-decoration: none;">Supplements</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="#" style="color: var(--text-secondary); text-decoration: none;">Pain Relief</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 style="margin-bottom: 1rem;">Contact Info</h4>
                    <p style="color: var(--text-secondary); margin-bottom: 0.5rem;">📞 +880-1234-567890</p>
                    <p style="color: var(--text-secondary); margin-bottom: 0.5rem;">✉️ info@medlagbe.com</p>
                    <p style="color: var(--text-secondary); margin-bottom: 0.5rem;">📍 Dhaka, Bangladesh</p>
                </div>
            </div>
            
            <div style="border-top: 1px solid var(--border-color); padding-top: 2rem; text-align: center;">
                <p style="color: var(--text-muted); margin: 0;">&copy; <?= date('Y') ?> MedLagbe. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script src="<?= BASE_URL ?>public/js/script.js"></script>
</body>
</html>
