<?php
/**
 * head.php — Inclusion partagée pour le <head> de toutes les pages.
 *
 * Variables optionnelles à définir AVANT l'include :
 *   $pageTitle       (string) — Titre de la page (remplace le titre par défaut)
 *   $pageDescription (string) — Meta description (remplace la description par défaut)
 *   $pageCanonical   (string) — URL canonique (remplace la canonique par défaut)
 */

$defaultTitle       = 'WebProject - Création de site internet et communication visuelle';
$defaultDescription = 'WebProject conçoit des sites internet à votre image pour développer votre visibilité digitale. WebProject propose également de réaliser votre communication visuelle.';
$defaultCanonical   = 'https://webprojectbxl.com/';

$title       = isset($pageTitle)       ? htmlspecialchars($pageTitle,       ENT_QUOTES, 'UTF-8') : $defaultTitle;
$description = isset($pageDescription) ? htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') : $defaultDescription;
$canonical   = isset($pageCanonical)   ? htmlspecialchars($pageCanonical,   ENT_QUOTES, 'UTF-8') : $defaultCanonical;
?>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="google" content="notranslate" />

    <title><?= $title ?></title>
    <meta name="description" content="<?= $description ?>" />

    <!-- Favicons -->
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />
    <link rel="canonical" href="<?= $canonical ?>" />

    <!-- Open Graph -->
    <meta property="og:title" content="<?= $title ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?= $canonical ?>" />
    <meta property="og:image" content="https://webprojectbxl.com/img/WebProject.png" />
    <meta property="og:description" content="<?= $description ?>" />
    <meta property="og:site_name" content="WebProject" />
    <meta property="og:locale" content="fr_BE" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?= $title ?>" />
    <meta name="twitter:description" content="<?= $description ?>" />
    <meta name="twitter:image" content="https://webprojectbxl.com/img/WebProject.png" />

    <!-- Données structurées : entreprise locale -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ProfessionalService",
        "name": "WebProject",
        "url": "https://webprojectbxl.com/",
        "logo": "https://webprojectbxl.com/img/logo.png",
        "image": "https://webprojectbxl.com/img/WebProject.png",
        "description": "Création de sites internet et communication visuelle : identité visuelle, logo, illustration, animation, social media et productions graphiques.",
        "slogan": "La réalité c'est vous",
        "telephone": "+32474784280",
        "email": "info@webprojectbxl.com",
        "address": {
            "@type": "PostalAddress",
            "postalCode": "1140",
            "addressLocality": "Evere",
            "addressRegion": "Bruxelles-Capitale",
            "addressCountry": "BE"
        },
        "areaServed": "Bruxelles et environs",
        "priceRange": "€€",
        "sameAs": [
            "https://www.facebook.com/WebProjectbxl",
            "https://www.instagram.com/webprojectbxl/"
        ]
    }
    </script>

    <!-- Polices -->
    <link rel="stylesheet" href="https://use.typekit.net/azc1wss.css" />

    <!-- CSS -->
    <link rel="stylesheet" href="/css/fontawesome.min.css" />
    <link rel="stylesheet" href="/css/gallery_simple_slideshow.min.css" />
    <link rel="stylesheet" href="/css/all.min.css" />
    <link rel="stylesheet" href="/css/v4-shims.min.css" />
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="/css/layout.css" />


    <!-- Font Awesome Kit -->
    <script defer src="https://kit.fontawesome.com/2d98867c3e.js" crossorigin="anonymous"></script>

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-FLL4G5CLD9"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-FLL4G5CLD9');
    </script>

    <!-- Cookie Law Info -->
    <script id="cookie-law-info-js-extra">
        var Cli_Data = {"nn_cookie_ids":[],"cookielist":[],"non_necessary_cookies":[],"ccpaEnabled":"","ccpaRegionBased":"","ccpaBarEnabled":"","strictlyEnabled":["necessary","obligatoire"],"ccpaType":"gdpr","js_blocking":"1","custom_integration":"","triggerDomRefresh":"","secure_cookies":""};
        var cli_cookiebar_settings = {"animate_speed_hide":"500","animate_speed_show":"500","background":"#fff","border":"#355571","border_on":"","button_1_button_colour":"#355571","button_1_button_hover":"#355571","button_1_link_colour":"#fff","button_1_as_button":"1","button_1_new_win":"","button_2_button_colour":"#355571","button_2_button_hover":"#355571","button_2_link_colour":"#355571","button_2_as_button":"","button_2_hidebar":"","button_3_button_colour":"#355571","button_3_button_hover":"#355571","button_3_link_colour":"#fff","button_3_as_button":"1","button_3_new_win":"","button_4_button_colour":"#000","button_4_button_hover":"#000000","button_4_link_colour":"#355571","button_4_as_button":"","button_7_button_colour":"#355571","button_7_button_hover":"#355571","button_7_link_colour":"#fff","button_7_as_button":"1","button_7_new_win":"","font_family":"inherit","header_fix":"","notify_animate_hide":"1","notify_animate_show":"","notify_div_id":"#cookie-law-info-bar","notify_position_horizontal":"right","notify_position_vertical":"bottom","scroll_close":"","scroll_close_reload":"","accept_close_reload":"","reject_close_reload":"","showagain_tab":"","showagain_background":"#fff","showagain_border":"#000","showagain_div_id":"#cookie-law-info-again","showagain_x_position":"100px","text":"#355571","show_once_yn":"","show_once":"10000","logging_on":"","as_popup":"","popup_overlay":"1","bar_heading_text":"","cookie_bar_as":"banner","popup_showagain_position":"bottom-right","widget_position":"left"};
        var log_object = {"ajax_url":"https:\/\/webprojectbxl.com\/wp-admin\/admin-ajax.php"};
    </script>
    <script defer src="/js/cookie-law-info.js?ver=3.0.5" id="cookie-law-info-js"></script>
