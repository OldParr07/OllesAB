<?php require_once 'includes/auth.php'; ?>

<footer class="site-footer">
    <div class="footer-content">
        <div class="footer-grid">
            <div class="footer-section">
                <h3>Om OllesAB</h3>
                <p>OllesAB grundades summer of 69 och VDn över OllesAB är Glenn Klapfventrikel.</p>
            </div>
            
            <div class="footer-section">
                <h3>Kontakt</h3>
                <ul class="footer-contact">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        Gruvövägen<br>
                        66433, Grums
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:info@ollesab.se">info@ollesab.se</a>
                    </li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Viktiga länkar</h3>
                <ul class="footer-links">
                    <li><a href="about.php">Om oss</a></li>
                    <li><a href="calendar.php">Aktiviteter</a></li>
                    <li><a href="contact.php">Kontakt</a></li>
                    <li><a href="#">Bli medlem</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Medlemsinloggning</h3>
                <div class="login-form" id="loginForm">
                    <?php if (!isLoggedIn()): ?>
                        <form id="loginFormElement" class="footer-login-form">
                            <div class="form-group">
                                <input type="text" id="username" name="username" placeholder="Användarnamn" required>
                            </div>
                            <div class="form-group">
                                <input type="password" id="password" name="password" placeholder="Lösenord" required>
                            </div>
                            <button type="submit" class="btn btn--primary btn--small">Logga in</button>
                        </form>
                        <div id="loginMessage" class="login-message"></div>
                    <?php else: ?>
                        <div class="logged-in-info">
                            <p>Inloggad som: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                            <button id="logoutBtn" class="btn btn--secondary btn--small">Logga ut</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> OllesAB</p>
            <div class="social-links">
                <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('loginFormElement');
        const loginMessage = document.getElementById('loginMessage');
        const logoutBtn = document.getElementById('logoutBtn');

        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;

                fetch('includes/auth.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=login&username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        loginMessage.textContent = data.message;
                        loginMessage.classList.add('error');
                    }
                })
                .catch(error => {
                    loginMessage.textContent = 'Ett fel uppstod. Försök igen.';
                    loginMessage.classList.add('error');
                });
            });
        }

        if (logoutBtn) {
            logoutBtn.addEventListener('click', function() {
                fetch('includes/auth.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=logout'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                });
            });
        }
    });
    </script>
</footer>