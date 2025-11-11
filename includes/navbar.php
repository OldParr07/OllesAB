<?php
// Kontrollera om sessionen redan är startad
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'auth.php';
?>
<nav class="navbar">
    <div class="navbar__container">
        <a href="index.php" class="navbar__logo"><img src="images/KSDK-banner.jpg" alt="KSDK Logo" class="navbar__logo-image"></a>
        <div class="navbar__toggle" id="mobile-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
        <div class="navbar__menu">
            <a href="index.php" class="navbar__link<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? ' active' : ''; ?>">Hem</a>
            <a href="about.php" class="navbar__link<?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? ' active' : ''; ?>">Om oss</a>
            <a href="calendar.php" class="navbar__link<?php echo basename($_SERVER['PHP_SELF']) == 'calendar.php' ? ' active' : ''; ?>">Aktiviteter</a>
            <a href="contact.php" class="navbar__link<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? ' active' : ''; ?>">Kontakt</a>
            <?php if (isLoggedIn()): ?>
                <a href="#" class="navbar__link" onclick="logout(); return false;">Logga ut</a>
            <?php else: ?>
                <a href="login.php" class="navbar__link<?php echo basename($_SERVER['PHP_SELF']) == 'login.php' ? ' active' : ''; ?>">Logga in</a>
            <?php endif; ?>
            <a href="#" class="btn btn--primary">Bli medlem</a>
        </div>
    </div>
</nav>

<script>
// När dokumentet är klart
document.addEventListener('DOMContentLoaded', function() {
    const menu = document.querySelector("#mobile-menu");
    const menuLinks = document.querySelector(".navbar__menu");
    menu?.addEventListener("click", function() {
        menu.classList.toggle("is-active");
        menuLinks.classList.toggle("active");
    });

    // Lägg till utloggningsfunktion om den behövs
    if (document.querySelector('a[onclick*="logout"]')) {
        window.logout = function() {
            fetch('includes/auth.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'action=logout'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            });
        };
    }
});</script>