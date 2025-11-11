<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>OllesAB</title>
        <link rel="stylesheet" href="style.css">
        <script src="https://kit.fontawesome.com/10235da58b.js" crossorigin="anonymous"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    </head>

     <body>
        <!-- Navbar Section -->
        <nav class="navbar">
            <div class="navbar__container">
                <a href="index.php" class="navbar__logo">
                    <img src="images/Olllogo.gif" alt="OllesAB Logo" class="navbar__logo-image" href="index.php">
                </a>
                <div class="navbar__toggle" id="mobile-menu">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
                <div class="navbar__menu">
                    <a href="index.php" class="navbar__link active">Hem</a>
                    <a href="contact.php" class="navbar__link">Våra kollegor</a>
                    <a href="about.php" class="navbar__link">Om oss</a>
                </div>
            </div>
        </nav>
        <!-- Hero Section -->
        <header class="hero">
            <div class="hero__content">
                <h1>Välkommen till OllesAB</h1>
                <p class="hero__text">För framtiden redan idag!</p>
                <div class="hero__cta">
                </div>
            </div>
        </header>

        <div class="page-layout">
            <main class="main-content">
                <!-- Senaste artiklarna -->
                <section class="latest-news">
                    <h2 class="section-title">Om oss</h2>
                    <div class="news-grid">
                        <article class="news-card">
                            <img src="images/frontbild.png" alt="OllesAB bild" class="news-card__image">
                            <div class="news-card__content">
                                <h3>Vad gör vi?</h3>
                                <p>Vi säljer, servar och tillverkar slutväxlar, planetväxlar till över 800 modeller i minigrävare och grävmaskiner i viktklasser från 0,8 till 45 ton.
Varför renovera dina slutväxlar och planetväxlar när en ny är billigare? Det är säkert billigare att byta till en nytt slutväxel eller planetväxel i det långa loppet, och väldigt ofta på kort sikt! Kvaliteten på slutväxlarna och planetväxlarna är trots de låga priserna höga. Vi gör delarna till våra slutväxlar och planetväxlar samt distribuerar dem direkt. Det ger dig en ny slutväxel och planetväxel till bästa pris och högsta kvalitet. Nya kunder uppger att de använde ofta renoverade slutväxlar och planetväxlar. Men det är helt enkelt inte lönsamt med våra låga priser.
Därför att köper i stort sett alla våra kunder en ny slutväxel eller planetväxel när den gamla ger upp. Vi kan serva och konfigurera slutväxlar och planetväxlar för att möta dina krav. Vi kan hjälpa till om du behöver en utbytes eller service på din slutväxel eller planetväxel från växellådorna till populära märken som Bonfiglioli, Brevini, Kayaba, Nachi, Oil Motor, Orbit/Eaton, Sumitomo, SOM, Tong Myung, Teijin Seiki eller Nabtesco.
Har du specifika applikationer som behöver en planetväxel eller en slutväxel kan vi fixa det också. Vi har en egen konstruktionsavdelning med lång erfarenhet i branschen. Tillverkningen sker på plats med några av de allra bästa maskinerna på marknaden. Operatörerna är välutbildade och har lång erfarenhet av tillverkning.</p>

                            </div>
                        </article>
                    </div>
                </section>
            </main>

            <!-- Sidopanel -->
            <aside class="sidebar">
                <div class="sidebar__widget payment-info">
                    <h3>Swish-betalning</h3>
                    <div class="swish-details">
                        <img src="swish-logo.png" alt="Swish" class="swish-logo">
                        <p>OllesAB´s nummer är:</p>
                        <div class="swish-number">(+46) 070-915 08 14</div>
                    </div>
                </div>

                <div class="sidebar__widget important-info">
                    <h3>Viktig information</h3>
                    <ul class="info-list">
                        <li><a href="#">Alkohol-, drog-, tobak- och dopingpolicy</a></li>
                    </ul>
                </div>
                <div class="sidebar__widget onlyfans-widget">
                    <h3>Följ oss på Onlyfans</h3>
                    <div class="fb-page-preview">
                        <!-- Onlyfans Page Plugin eller egen implementation -->
                    </div>
                </div>
            </aside>
        </div>

        <?php include 'includes/footer.php'; ?>

        <script>
            // Toggle mobile menu
            const menu = document.querySelector('#mobile-menu');
            const menuLinks = document.querySelector('.navbar__menu');
            
            menu.addEventListener('click', function() {
                menu.classList.toggle('is-active');
                menuLinks.classList.toggle('active');
            });
        </script>
    </body>