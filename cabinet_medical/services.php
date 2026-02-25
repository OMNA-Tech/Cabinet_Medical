<?php 
require_once 'includes/db.php';
include 'includes/header.php'; 

// Fetch services from DB
$stmt = $pdo->query("SELECT * FROM services ORDER BY created_at ASC");
$services = $stmt->fetchAll();
?>

<!-- Page Header -->
<div class="bg-primary text-white py-5 text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Nos Services Médicaux</h1>
        <p class="lead">Une offre de soins complète pour répondre à tous vos besoins.</p>
    </div>
</div>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            
            <?php foreach ($services as $service): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm p-4 hover-effect">
                    <div class="card-body">
                        <div class="text-primary mb-3 display-5">
                            <i class="<?php echo htmlspecialchars($service['icon']); ?>"></i>
                        </div>
                        <h4 class="card-title fw-bold"><?php echo htmlspecialchars($service['title']); ?></h4>
                        <p class="card-text text-muted">
                            <?php echo htmlspecialchars($service['description']); ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if (empty($services)): ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Aucun service disponible pour le moment.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-light mt-5">
    <div class="container text-center">
        <h3 class="fw-bold mb-4">Besoin d'un avis médical ?</h3>
        <p class="lead mb-4 text-muted">Notre équipe est à votre disposition pour répondre à toutes vos questions.</p>
        <a href="contact.php" class="btn btn-outline-primary btn-lg rounded-pill px-5 me-3">Nous Contacter</a>
        <a href="appointment.php" class="btn btn-primary btn-lg rounded-pill px-5">Prendre Rendez-vous</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
