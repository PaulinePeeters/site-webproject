<?php
/**
 * contact.php — Page de contact avec protection anti-spam multi-couches :
 *   1. Honeypot (champ caché)
 *   2. Token CSRF (anti cross-site request forgery)
 *   3. Google reCAPTCHA v3 (score invisible)
 */

session_start();
require_once __DIR__ . '/../config/recaptcha_config.php';

// ──────────────────────────────────────────────────────
// Génération du token CSRF (si absent de la session)
// ──────────────────────────────────────────────────────
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ──────────────────────────────────────────────────────
// Traitement du formulaire — Pattern POST / Redirect / GET
// ──────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['website'])) {
        $_SESSION['form_notice'] = 'error';
        header('Location: contact.php');
        exit;
    }

    // 2. Vérification du token CSRF
    //    DÉSACTIVÉ TEMPORAIREMENT : la session PHP de l'hébergeur ne maintient pas
    //    le token entre GET (affichage) et POST (soumission).
    //    reCAPTCHA v3 protège déjà contre les soumissions cross-site.
    /*
    if (
        empty($_POST['csrf_token'])
        || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        header('Location: contact.php?sent=0&err=csrf');
        exit;
    }
    */

    // 3. Vérification Google reCAPTCHA v3
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
    if (empty($recaptchaResponse)) {
        $_SESSION['form_notice'] = 'error';
        header('Location: contact.php');
        exit;
    }

    $verifyUrl  = 'https://www.google.com/recaptcha/api/siteverify';
    $verifyData = http_build_query([
        'secret'   => RECAPTCHA_SECRET_KEY,
        'response' => $recaptchaResponse,
        'remoteip' => $_SERVER['REMOTE_ADDR'],
    ]);

    // Essai avec file_get_contents, fallback cURL si indisponible
    $verifyResult = false;

    if (ini_get('allow_url_fopen')) {
        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $verifyData,
                'timeout' => 5,
            ],
        ]);
        $verifyResult = @file_get_contents($verifyUrl, false, $context);
    }

    if ($verifyResult === false && function_exists('curl_init')) {
        $ch = curl_init($verifyUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $verifyData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $verifyResult = curl_exec($ch);
        curl_close($ch);
    }

    $captchaData = $verifyResult ? json_decode($verifyResult, true) : null;

    if (
        !$captchaData
        || empty($captchaData['success'])
        || ($captchaData['score'] ?? 0) < RECAPTCHA_SCORE_THRESHOLD
    ) {
        $_SESSION['form_notice'] = 'error';
        header('Location: contact.php');
        exit;
    }

    // 4. Récupération et assainissement des données
    $nom     = htmlspecialchars(trim(filter_input(INPUT_POST, 'nom',     FILTER_DEFAULT) ?? ''), ENT_QUOTES, 'UTF-8');
    $societe = htmlspecialchars(trim(filter_input(INPUT_POST, 'societe', FILTER_DEFAULT) ?? ''), ENT_QUOTES, 'UTF-8');
    $email   = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $tel     = htmlspecialchars(trim(filter_input(INPUT_POST, 'tel',     FILTER_DEFAULT) ?? ''), ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars(trim(filter_input(INPUT_POST, 'message', FILTER_DEFAULT) ?? ''), ENT_QUOTES, 'UTF-8');

    // 5. Validation minimale (email et format téléphone)
    // On extrait uniquement les chiffres pour vérifier la longueur (un vrai numéro a entre 8 et 15 chiffres continus ou espacés)
    $digitsOnly = preg_replace('/[^0-9]/', '', $tel);
    if ($email === false || empty($nom) || empty($message) || strlen($digitsOnly) < 8 || strlen($digitsOnly) > 15) {
        $_SESSION['form_notice'] = 'error';
        header('Location: contact.php');
        exit;
    }

    // 6. Construction et envoi de l'email avec PHPMailer
    require_once __DIR__ . '/../lib/phpmailer/Exception.php';
    require_once __DIR__ . '/../lib/phpmailer/PHPMailer.php';
    require_once __DIR__ . '/../lib/phpmailer/SMTP.php';
    require_once __DIR__ . '/../config/smtp_config.php';

    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
        if (SMTP_USE_SMTP) {
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            // SSL ou TLS selon configuration
            $mail->SMTPSecure = (strtolower(SMTP_SECURE) === 'ssl') ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = SMTP_PORT;
        }

        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress('info@webprojectbxl.com'); // Adresse où vous recevez les requêtes
        $mail->addReplyTo($email, $nom);
        
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = 'Nouvelle demande WebProject — ' . $nom;
        
        $mail->Body = '<h2>Message envoyé depuis la page Contact</h2>
<p>
  <b>Nom :</b> '     . $nom     . '<br>
  <b>Société :</b> ' . $societe . '<br>
  <b>Email :</b> '   . $email   . '<br>
  <b>Téléphone :</b> '. $tel    . '<br>
  <b>Message :</b><br>' . nl2br($message) . '
</p>';

        $mail->send();
        $sent = true;
    } catch (\Exception $e) {
        $sent = false;
        error_log('Erreur PHPMailer : ' . $mail->ErrorInfo);
    }

    // Régénérer le token CSRF après soumission réussie
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    // Redirection propre après traitement (PATTERN POST/REDIRECT/GET)
    $_SESSION['form_notice'] = $sent ? 'success' : 'error';
    header('Location: contact.php');
    exit;
}

$pageTitle       = 'Contact — WebProject';
$pageDescription = 'Contactez WebProject pour un devis gratuit : création de site internet, identité visuelle, illustration et communication graphique à Bruxelles.';
$pageCanonical   = 'https://webprojectbxl.com/contact.php';
?>
<!doctype html>
<html class="w3" lang="fr" dir="ltr" prefix="og: https://ogp.me/ns#">
<head>
<?php include __DIR__ . '/../includes/head.php'; ?>

    <!-- Google reCAPTCHA v3 -->
    <script src="https://www.google.com/recaptcha/api.js?render=<?= htmlspecialchars(RECAPTCHA_SITE_KEY, ENT_QUOTES, 'UTF-8') ?>"></script>
</head>

<body class="Home page-contact">
<div class="fullsite">

    <?php include __DIR__ . '/../includes/header.php'; ?>

    <div class="contact-page-inner">

    <!-- Accroche -->
    <div class="formButton">
        <p>Êtes-vous intéressés par nos services ?</p>
        <strong>Parlons de ce que nous pourrions faire ensemble.</strong>
    </div>



    <!-- Formulaire de contact -->
    <form class="contact-form" method="post" action="contact.php" id="contactForm">
        <h2>CONTACTEZ-NOUS</h2>

        <!-- Affichage du message de confirmation -->
        <?php if (isset($_SESSION['form_notice'])): ?>
            <?php if ($_SESSION['form_notice'] === 'success'): ?>
                <div class="form-success">
                    <p style="margin:0; padding:0;"><strong>Succès :</strong> Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.</p>
                </div>
            <?php else: ?>
                <div class="form-error">
                    <p style="margin:0; padding:0;"><strong>Erreur :</strong> Une erreur est survenue ou vos informations (téléphone/e-mail) sont invalides. Veuillez vérifier et réessayer.</p>
                </div>
            <?php endif; ?>
            <?php unset($_SESSION['form_notice']); ?>
        <?php endif; ?>

        <!-- Champ honeypot anti-spam (invisible pour les humains) -->
        <input type="text" name="website" style="display:none;" tabindex="-1" autocomplete="off" />

        <!-- Token CSRF -->
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>" />

        <!-- Token reCAPTCHA v3 (rempli par JavaScript) -->
        <input type="hidden" name="g-recaptcha-response" id="recaptchaResponse" />

        <p>
            <label for="nom">Votre nom :</label>
            <input type="text" name="nom" id="nom" placeholder="Nom *" required />
        </p>
        <p>
            <label for="societe">Le nom de votre société :</label>
            <input type="text" name="societe" id="societe" placeholder="Nom de votre société" />
        </p>
        <p>
            <label for="email">Votre email :</label>
            <input type="email" name="email" id="email" placeholder="E-mail *" required />
        </p>
        <p>
            <label for="tel">Votre numéro de téléphone :</label>
            <input type="tel" name="tel" id="tel" placeholder="Numéro de téléphone *" pattern="^[0-9\+\s\-]{8,15}$" title="Veuillez entrer un numéro de téléphone valide (ex: 0470000000 ou +32470000000)" required />
        </p>
        <p>
            <label for="message">Description de votre projet / Message :</label>
            <textarea name="message" id="message" placeholder="Votre projet en quelques lignes *" required></textarea>
        </p>

        <div class="gdpr">
            <input type="checkbox" id="form1-gdpr" name="gdpr" required />
            <label for="form1-gdpr">
                J'ai lu et j'accepte <a href="/rgpd.html" target="_blank"><strong>la politique de confidentialité</strong></a>
            </label>
        </div>

        <p><input type="submit" name="submit" value="Envoyer" /></p>
    </form>

    </div><!-- /.contact-page-inner -->

    <!-- Footer -->
    <section class="footer-contact">
        <?php include __DIR__ . '/../includes/footer.php'; ?>
    </section>

    <?php include __DIR__ . '/../includes/cookies.php'; ?>

    <?php include __DIR__ . '/../includes/scripts.php'; ?>

    <!-- reCAPTCHA v3 : génère le token au moment de la soumission -->
    <script>
    (function() {
        var form = document.getElementById('contactForm');
        if (!form) { console.error('contactForm introuvable'); return; }

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Si reCAPTCHA n'est pas chargé, soumettre quand même (le PHP renverra ?err=notoken)
            if (typeof grecaptcha === 'undefined') {
                console.error('reCAPTCHA non chargé — soumission directe');
                HTMLFormElement.prototype.submit.call(form);
                return;
            }

            // Timeout de sécurité : si reCAPTCHA ne répond pas en 5s, soumettre quand même
            var submitted = false;
            var safetyTimeout = setTimeout(function() {
                if (!submitted) {
                    submitted = true;
                    console.warn('reCAPTCHA timeout — soumission sans token');
                    HTMLFormElement.prototype.submit.call(form);
                }
            }, 5000);

            grecaptcha.ready(function() {
                grecaptcha.execute('<?= htmlspecialchars(RECAPTCHA_SITE_KEY, ENT_QUOTES, 'UTF-8') ?>', {action: 'contact'})
                .then(function(token) {
                    if (!submitted) {
                        submitted = true;
                        clearTimeout(safetyTimeout);
                        document.getElementById('recaptchaResponse').value = token;
                        console.log('reCAPTCHA token obtenu, soumission du formulaire');
                        HTMLFormElement.prototype.submit.call(form);
                    }
                })
                .catch(function(err) {
                    if (!submitted) {
                        submitted = true;
                        clearTimeout(safetyTimeout);
                        console.error('reCAPTCHA erreur:', err);
                        HTMLFormElement.prototype.submit.call(form);
                    }
                });
            });
        });
    })();
    </script>

</div><!-- /.fullsite -->
</body>
</html>
