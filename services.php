<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>OllesAB - Tjänster</title>
        <link rel="stylesheet" href="style.css">
        <script src="https://kit.fontawesome.com/10235da58b.js" crossorigin="anonymous"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    </head>

    <body>
        <?php include 'includes/navbar.php'; ?>

        <main class="container">
            <header class="page-header">
                <h1>Våra tjänster</h1>
                <p class="lead">Upptäck vad vi kan erbjuda dig</p>
            </header>
            </main>

        <?php include 'includes/footer.php'; ?>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menu = document.querySelector("#mobile-menu");
            const menuLinks = document.querySelector(".navbar__menu");
            menu?.addEventListener("click", function() {
                menu.classList.toggle("is-active");
                menuLinks.classList.toggle("active");
            });
        });
        </script>
    </body>
</html>