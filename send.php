<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = strip_tags(trim($_POST["nom"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Validation simple
    if (empty($nom) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Merci de remplir le formulaire correctement.";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // Paramètres SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mzwalid05@gmail.com';      
        $mail->Password = 'ldodkocougtbqewr';  // App Password (ne jamais partager en public)
        $mail->SMTPSecure = 'tls';   // TLS est correct avec port 587
        $mail->Port = 587;

        // Expéditeur
        $mail->setFrom('mzwalid05@gmail.com', 'Portfolio Contact');
        $mail->addReplyTo($email, $nom);

        // Destinataire
        $mail->addAddress('mzwalid05@gmail.com');

        // Contenu du mail
        $mail->isHTML(false);
        $mail->Subject = "Nouveau message de $nom depuis ton portfolio";
        $mail->Body = "Nom: $nom\nEmail: $email\n\nMessage:\n$message";

        $mail->send();

        // Redirection vers la page merci.html après envoi
        header("Location: merci.html");
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo "Erreur lors de l'envoi du message: {$mail->ErrorInfo}";
        exit;
    }
} else {
    http_response_code(403);
    echo "Méthode non autorisée.";
    exit;
}
?>
