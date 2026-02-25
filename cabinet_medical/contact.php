<?php 
include 'includes/header.php'; 

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && $email && $subject && $message) {
        $admin_email = get_setting($pdo, 'email');
        if (!$admin_email) {
            $admin_email = 'contact@omnasante.fr';
        }

        $mail_subject = 'Nouveau message depuis le site - ' . $subject;
        $mail_body = "Nom: " . $name . "\n";
        $mail_body .= "Email: " . $email . "\n\n";
        $mail_body .= "Message:\n" . $message . "\n";

        $headers = "From: \"" . $name . "\" <" . $email . ">\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (@mail($admin_email, $mail_subject, $mail_body, $headers)) {
            $success_message = 'Votre message a été envoyé avec succès.';
        } else {
            $error_message = "Une erreur s'est produite lors de l'envoi du message. Merci de réessayer plus tard.";
        }
    } else {
        $error_message = 'Merci de remplir tous les champs du formulaire.';
    }
}
?>

<!-- Page Header -->
<div class="bg-primary text-white py-5 text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Contactez-nous</h1>
        <p class="lead">Nous sommes à votre écoute pour toute question ou information.</p>
    </div>
</div>

<section class="section-padding">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Info -->
            <div class="col-lg-5">
                <h3 class="mb-4 text-primary">Nos Coordonnées</h3>
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0 text-primary display-6 me-3">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Adresse</h5>
                        <p class="text-muted">Kénitra, Maroc</p>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0 text-primary display-6 me-3">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Téléphone</h5>
                        <p class="text-muted">06 89 40 20 33</p>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0 text-primary display-6 me-3">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Email</h5>
                        <p class="text-muted">contact@omnasante.fr</p>
                    </div>
                </div>
                
                <h3 class="mb-4 mt-5 text-primary">Horaires d'ouverture</h3>
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td class="fw-bold">Lundi - Vendredi</td>
                            <td>8h00 - 16h00</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Samedi</td>
                            <td>9h00 - 13h00</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Dimanche</td>
                            <td class="text-danger">Fermé</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-lg p-4">
                    <div class="card-body">
                        <h3 class="mb-4 text-primary">Envoyez-nous un message</h3>

                        <?php if ($success_message): ?>
                            <div class="alert alert-success">
                                <?php echo $success_message; ?>
                            </div>
                        <?php elseif ($error_message): ?>
                            <div class="alert alert-danger">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nom complet</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Votre nom" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Votre email" required>
                                </div>
                                <div class="col-12">
                                    <label for="subject" class="form-label">Sujet</label>
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Sujet de votre message" required>
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="5" placeholder="Votre message..." required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100 py-3">Envoyer le message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section>
    <div class="ratio ratio-21x9">
        <iframe 
            src="https://www.google.com/maps?q=Kenitra+Morocco&output=embed" 
            width="600" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
