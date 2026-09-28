<?php
/**
 * mail.php — Utilitaire d'envoi d'email.
 *
 * Ce fichier contient la logique d'envoi séparée de l'affichage.
 * Il n'est pas destiné à être appelé directement depuis le navigateur.
 * La gestion du formulaire est faite dans contact.php.
 *
 * @param string $nom     Nom de l'expéditeur
 * @param string $societe Nom de la société
 * @param string $email   Adresse email de l'expéditeur (validée)
 * @param string $tel     Numéro de téléphone
 * @param string $message Message
 * @return bool           true si l'envoi a réussi, false sinon
 */
function sendContactEmail(string $nom, string $societe, string $email, string $tel, string $message): bool
{
    $to      = 'info@webprojectbxl.com';
    $subject = 'Nouvelle demande WebProject — ' . $nom;

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $headers .= 'From: postmaster@webprojectbxl.com' . "\r\n";
    $headers .= 'Reply-To: ' . $email . "\r\n";

    $body = '<h2>Message envoy&eacute; depuis la page Contact</h2>
<p>
  <b>Nom :</b> '      . $nom     . '<br>
  <b>Soci&eacute;t&eacute; :</b> ' . $societe . '<br>
  <b>Email :</b> '    . $email   . '<br>
  <b>T&eacute;l&eacute;phone :</b> ' . $tel . '<br>
  <b>Message :</b><br>' . nl2br($message) . '
</p>';

    return mail($to, $subject, $body, $headers);
}