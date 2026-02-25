<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$booking_success = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $reason = $_POST['reason'];
    $message = $_POST['message'];

    if (empty($date) || empty($time) || empty($reason)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } else {
        // Basic check if slot is taken (optional improvement)
        $stmt = $pdo->prepare("INSERT INTO appointments (user_id, appointment_date, appointment_time, reason, message) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$user_id, $date, $time, $reason, $message])) {
            $booking_success = true;
        } else {
            $error = "Une erreur est survenue lors de la réservation.";
        }
    }
}
include 'includes/header.php';
?>

<!-- Page Header -->
<div class="bg-primary text-white py-5 text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Prendre Rendez-vous</h1>
        <p class="lead">Choisissez le créneau qui vous convient le mieux.</p>
    </div>
</div>

<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <?php if ($booking_success): ?>
                <div class="alert alert-success shadow-sm p-4 mb-5 rounded-3 text-center" role="alert">
                    <div class="display-1 text-success mb-3"><i class="fas fa-check-circle"></i></div>
                    <h2 class="alert-heading fw-bold">Demande envoyée !</h2>
                    <p class="lead">Votre demande de rendez-vous a bien été enregistrée. Elle est en attente de confirmation par le docteur.</p>
                    <hr>
                    <div class="mt-4">
                        <a href="index.php" class="btn btn-success">Retour à l'accueil</a>
                        <a href="my_appointments.php" class="btn btn-outline-success">Voir mes rendez-vous</a>
                    </div>
                </div>
                <?php else: ?>

                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <h3 class="card-title text-center text-primary mb-4 fw-bold">Formulaire de réservation</h3>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form action="appointment.php" method="POST" id="bookingForm">
                            <div class="row g-3">
                                <!-- Personal Info (Read-only or just hidden as we use session) -->
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-user me-2"></i> Vous êtes connecté en tant que <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong>.
                                    </div>
                                </div>

                                <!-- Medical Info -->
                                <div class="col-12 mt-2">
                                    <h5 class="mb-3 border-bottom pb-2">Détails du rendez-vous</h5>
                                </div>
                                <div class="col-md-6">
                                    <label for="reason" class="form-label">Motif de la visite</label>
                                    <select class="form-select" id="reason" name="reason" required>
                                        <option value="" selected disabled>Sélectionnez un motif</option>
                                        <option value="consultation">Consultation classique</option>
                                        <option value="urgence">Urgence mineure</option>
                                        <option value="suivi">Suivi régulier</option>
                                        <option value="vaccination">Vaccination</option>
                                        <option value="certificat">Certificat médical</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="date" class="form-label">Date souhaitée</label>
                                    <input type="date" class="form-control" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="time" class="form-label">Heure souhaitée</label>
                                    <input type="time" class="form-control" id="time" name="time" required min="08:00" max="19:00">
                                    <small class="text-muted">Nos horaires: 8h - 19h</small>
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label">Informations complémentaires (facultatif)</label>
                                    <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                                </div>
                                
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm">Confirmer la demande</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
