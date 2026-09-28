<?php
/**
 * scripts.php — Inclusion partagée pour les scripts JS de toutes les pages.
 * À placer juste avant </body>.
 *
 * jQuery chargé en synchrone (Superfish en dépend).
 * Les autres scripts sont différés avec defer.
 */
?>
    <!-- jQuery (synchrone — requis par Superfish) -->
    <script src="/jquery/jquery-3.6.0.min.js"></script>
    <script defer src="/js/jquery/jquery-ui_v1.12.1.min.js"></script>
    <script defer src="/js/gallery_simple_slideshow.min.js"></script>
    <script defer src="/js/superfish.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Initialisation Superfish
            if (typeof $.fn.superfish !== 'undefined') {
                $('.sf-menu').superfish({
                    animation:    { opacity: 'show' },
                    animationOut: { opacity: 'hide' }
                });
            }

            // Menu mobile — ouverture
            var mobileNavLink = document.querySelector('.mobile-nav a');
            if (mobileNavLink) {
                mobileNavLink.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.body.classList.add('show-menu');
                });
            }

            // Menu mobile — fermeture (overlay + bouton close)
            document.querySelectorAll('.site-overlay, .menu-wrap .close-button').forEach(function (el) {
                el.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.body.classList.remove('show-menu');
                });
            });

            // Défilement fluide vers le bas (flèche header)
            var scrollArrow = document.querySelector('.header-scroll-arrow');
            if (scrollArrow) {
                scrollArrow.addEventListener('click', function () {
                    scrollArrow.scrollIntoView({ behavior: 'smooth' });
                });
            }

        });
    </script>
