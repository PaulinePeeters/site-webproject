<!doctype html>
<html class="w3" lang="fr" dir="ltr" prefix="og: https://ogp.me/ns#">
<head>
<?php
$pageTitle     = 'WebProject - Création de site internet et communication visuelle';
$pageCanonical = 'https://webprojectbxl.com/';
include __DIR__ . '/../includes/head.php';
?>
</head>

<body class="Home page-accueil">
<div class="fullsite">

    <?php include __DIR__ . '/../includes/header.php'; ?>

    <!-- Slider / Vidéo bannière -->
    <section class="banner">
        <video autoplay loop muted playsinline>
            <source src="/img/accueil.mp4" type="video/mp4">
        </video>
    </section>

    <a class="servicesButton" href="/services.php">
        <p>CONSULTEZ</p>
        <strong>nos différents services</strong>
    </a>

    <!-- Corps de la page -->
    <section class="corps">

        <!-- Notre offre -->
        <section class="offer">

            <div class="offre">
                <h2>NOTRE OFFRE</h2>
                <p>un site internet <strong>responsive</strong><br />
                    qui répond aux attentes de <strong>vos clients</strong>
                    qui <strong>vous ressemble</strong> tout ça pour un <strong class="prix">prix raisonnable</strong>
                </p>
                <a class="devisButton" href="/contact.php">
                    <p>DEMANDEZ</p>
                    <strong>votre devis</strong>
                </a>
            </div>

            <div class="accompagnement">
                <h2>AC<br>COM<br>PA<br>GNE<br>MENT</h2>
                <p>nous vous <strong>accompagnons</strong> tout au long de
                    la construction de votre projet
                    nous vous <strong>conseillons</strong>
                    afin de rendre votre <strong>visibilité efficace</strong>
                </p>
                <a class="contactButton" href="/contact.php">
                    <p>CONTACTEZ-NOUS</p>
                    <strong>dès maintenant</strong>
                </a>
            </div>

        </section>

        <!-- Site web et objectifs -->
        <section class="schema">
            <h2>SITE WEB <strong>et objectifs </strong></h2>
            <div class="obj">
                <div class="comm"><img src="/img/comm.png" alt="Schéma communication"></div>
                <div class="tourbillon"><img src="/img/schema.png" alt="Schéma objectifs web"></div>
            </div>
        </section>

        <!-- À propos -->
        <section class="aboutus">

            <div class="flechebas">
                <img src="/img/logofleche.png" alt="Logo WebProject">
                <div class="deux"><p>2</p></div>
                <div class="spec">
                    <p>SPÉCIALISTES <br>À <br>VOTRE <br>SERVICE</p>
                </div>
            </div>

            <div class="presentation">

                <div class="Virg">
                    <h2><strong>Virginie</strong> MICHAUX</h2>
                    <h3>UI et UX Designer</h3>
                    <span class="portrait1"><img src="/img/Img2.png" alt="Portrait de Virginie Michaux"></span>
                    <p><strong>Passionnée</strong> par les <strong>arts visuels</strong>, Virginie
                        développe ses compétences dans les domaines
                        du <strong>web design</strong>, du <strong>graphisme</strong>
                        et de <strong>l'illustration</strong> depuis 20 ans.
                    </p>
                </div>

                <div class="Pau">
                    <h2><strong>Pauline</strong> PEETERS</h2>
                    <h3>WEB Developer</h3>
                    <span class="portrait2"><img src="/img/Img1.png" alt="Portrait de Pauline Peeters"></span>
                    <p>Passionnée par le monde numérique,
                        Pauline a d'abord suivi des études en <strong>communication visuelle</strong> et en
                        <strong>arts numériques</strong>. Aujourd'hui <strong> développeuse web</strong>,
                        elle continue d'évoluer et d'affiner ses compétences techniques
                        (front-end, référencement naturel, responsive)
                        afin de garantir un <strong>service technique de qualité</strong>.
                    </p>
                </div>

            </div>

        </section>

    </section>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <?php include __DIR__ . '/../includes/cookies.php'; ?>

    <?php include __DIR__ . '/../includes/scripts.php'; ?>

</div><!-- /.fullsite -->
</body>
</html>