<?php 
include 'includes/header.php'; 

// Fetch dynamic content
$hero_title = get_setting($pdo, 'hero_title');
$hero_text = get_setting($pdo, 'hero_text');
$about_text = get_setting($pdo, 'about_text');
$doctor_name = get_setting($pdo, 'doctor_name');
$hero_image = get_setting($pdo, 'hero_image');
$about_image = get_setting($pdo, 'about_image');
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4 text-primary"><?php echo nl2br(htmlspecialchars($hero_title)); ?></h1>
                <p class="lead mb-4 text-secondary">
                    <?php echo nl2br(htmlspecialchars($hero_text)); ?>
                </p>
                <div class="d-flex gap-3">
                    <a href="appointment.php" class="btn btn-primary btn-lg">Prendre Rendez-vous</a>
                    <a href="contact.php" class="btn btn-secondary btn-lg">Contactez-nous</a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <img src="<?php echo htmlspecialchars($hero_image); ?>" alt="Médecin" class="img-fluid rounded-4 shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Introduction -->
<section class="section-padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="<?php echo htmlspecialchars($about_image); ?>" alt="Cabinet" class="img-fluid rounded-4 shadow">
            </div>
            <div class="col-lg-6">
                <h2 class="mb-4 text-primary">Le <?php echo htmlspecialchars($doctor_name); ?> à votre service</h2>
                <p class="mb-4">
                    <?php echo nl2br(htmlspecialchars($about_text)); ?>
                </p>
                <ul class="list-unstyled mb-4">
                    <li class="mb-2"><i class="fas fa-check-circle text-secondary me-2"></i> Écoute attentive</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-secondary me-2"></i> Suivi personnalisé</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-secondary me-2"></i> Disponibilité</li>
                </ul>
                <a href="about.php" class="btn btn-outline-primary rounded-pill">En savoir plus</a>
            </div>
        </div>
    </div>
</section>

<!-- Services Overview -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="text-primary">Nos Services</h2>
            <p class="text-muted">Une prise en charge complète</p>
        </div>
        <div class="row g-4">
            <?php
            $stmt = $pdo->query("SELECT * FROM services ORDER BY created_at ASC LIMIT 3");
            while ($service = $stmt->fetch()):
            ?>
            <div class="col-md-4">
                <div class="card h-100 p-4 text-center">
                    <div class="card-body">
                        <i class="<?php echo htmlspecialchars($service['icon']); ?> service-icon"></i>
                        <h4 class="card-title mb-3"><?php echo htmlspecialchars($service['title']); ?></h4>
                        <p class="card-text text-muted"><?php echo htmlspecialchars($service['description']); ?></p>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="text-center mt-5">
            <a href="services.php" class="btn btn-primary">Voir tous nos services</a>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="text-primary">Avis Patients</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm bg-light">
                    <div class="card-body p-4">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="fst-italic">"Très bon médecin, à l'écoute et prend le temps nécessaire. Je recommande."</p>
                        <h6 class="fw-bold mt-3">- Sophie L.</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm bg-light">
                    <div class="card-body p-4">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="fst-italic">"Cabinet très propre et prise de rendez-vous facile sur le site."</p>
                        <h6 class="fw-bold mt-3">- Thomas D.</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm bg-light">
                    <div class="card-body p-4">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <p class="fst-italic">"Le docteur est très professionnel et ponctuel."</p>
                        <h6 class="fw-bold mt-3">- Marie P.</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container">
        <h2 class="mb-3">Besoin d'un avis médical ?</h2>
        <p class="lead mb-4">Prenez rendez-vous dès maintenant.</p>
        <a href="appointment.php" class="btn btn-light btn-lg text-primary fw-bold">Prendre Rendez-vous</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
