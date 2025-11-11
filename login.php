<?php
session_start();
require_once 'includes/auth.php';

// Om användaren redan är inloggad, omdirigera till kontaktsidan
if (isLoggedIn()) {
    header('Location: contact.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OllesAB - Medlemsinloggning</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        .error-message {
            color: #f44336;
            margin-top: 1rem;
            display: none;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar__container">
            <a href="index.php" class="navbar__logo">
                <img src="images/OllLogo.gif" alt="OllesAB Logo" class="navbar__logo-image">
            </a>
            <div class="navbar__toggle" id="mobile-menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
            <div class="navbar__menu">
                <a href="index.php" class="navbar__link">Hem</a>
                <a href="about.php" class="navbar__link">Om oss</a>
                <a href="calendar.php" class="navbar__link">Aktiviteter</a>
                <a href="contact.php" class="navbar__link">Kontakt</a>
                <a href="#" class="btn btn--primary">Bli medlem</a>
            </div>
        </div>
    </nav>

    <main class="container">
        <div class="login-container">
            <h1>Medlemsinloggning</h1>
            <form id="loginForm" method="post">
                <div class="form-group">
                    <label for="username">Användarnamn:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Lösenord:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn--primary">Logga in</button>
                <div id="errorMessage" class="error-message"></div>
            </form>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'login');
            
            fetch('includes/auth.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(formData),
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'contact.php';
                } else {
                    const errorMessage = document.getElementById('errorMessage');
                    errorMessage.textContent = data.message || 'Ett fel uppstod vid inloggning';
                    errorMessage.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const errorMessage = document.getElementById('errorMessage');
                errorMessage.textContent = 'Ett fel uppstod vid inloggning';
                errorMessage.style.display = 'block';
            });
        });

        // Mobile menu toggle
        const menu = document.querySelector('#mobile-menu');
        const menuLinks = document.querySelector('.navbar__menu');
        menu?.addEventListener('click', function() {
            menu.classList.toggle('is-active');
            menuLinks.classList.toggle('active');
        });
    </script>
</body>
</html>