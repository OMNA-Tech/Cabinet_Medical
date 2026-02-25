<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/db.php';

// Check if admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update settings
    $settings = [
        'site_title' => $_POST['site_title'],
        'doctor_name' => $_POST['doctor_name'],
        'phone' => $_POST['phone'],
        'email' => $_POST['email'],
        'address' => $_POST['address'],
        'hero_title' => $_POST['hero_title'],
        'hero_text' => $_POST['hero_text'],
        'about_text' => $_POST['about_text']
    ];

    // Handle File Uploads
    $upload_dir = '../assets/uploads/';
    
    // Helper function to handle upload
    function handle_upload($file_key, $upload_dir) {
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] == 0) {
            $file_ext = strtolower(pathinfo($_FILES[$file_key]['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($file_ext, $allowed)) {
                $new_filename = uniqid() . '.' . $file_ext;
                $dest_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES[$file_key]['tmp_name'], $dest_path)) {
                    return 'assets/uploads/' . $new_filename;
                }
            }
        }
        return null;
    }

    $hero_image = handle_upload('hero_image_file', $upload_dir);
    if ($hero_image) {
        $settings['hero_image'] = $hero_image;
    }

    $about_image = handle_upload('about_image_file', $upload_dir);
    if ($about_image) {
        $settings['about_image'] = $about_image;
    }

    foreach ($settings as $key => $value) {
        $stmt = $pdo->prepare("INSERT INTO settings (key_name, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = ?");
        $stmt->execute([$key, $value, $value]);
    }
    $success = "Paramètres mis à jour avec succès.";
}

// Fetch current settings
$current_settings = [];
$stmt = $pdo->query("SELECT * FROM settings");
while ($row = $stmt->fetch()) {
    $current_settings[$row['key_name']] = $row['value'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paramètres - OMNA Santé Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="../index.php"><i class="fas fa-heartbeat me-2"></i> OMNA Santé Admin</a>
        <div class="d-flex">
            <a href="dashboard.php" class="btn btn-light text-primary me-2">Rendez-vous</a>
            <a href="services.php" class="btn btn-light text-primary me-2">Services</a>
            <a href="../logout.php" class="btn btn-outline-light">Déconnexion</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="mb-0">Modifier les informations du site</h3>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <h5 class="mb-3 text-primary">Informations Générales</h5>
                        <div class="mb-3">
                            <label class="form-label">Titre du Site</label>
                            <input type="text" class="form-control" name="site_title" value="<?php echo htmlspecialchars($current_settings['site_title'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nom du Docteur</label>
                            <input type="text" class="form-control" name="doctor_name" value="<?php echo htmlspecialchars($current_settings['doctor_name'] ?? ''); ?>">
                        </div>

                        <h5 class="mb-3 mt-4 text-primary">Images</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Image Hero (Accueil)</label>
                                <input type="file" class="form-control" name="hero_image_file">
                                <?php if (!empty($current_settings['hero_image'])): ?>
                                    <div class="mt-2">
                                        <small class="text-muted">Actuelle :</small><br>
                                        <img src="../<?php echo htmlspecialchars($current_settings['hero_image']); ?>" class="img-thumbnail" style="height: 100px;">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Image "À propos"</label>
                                <input type="file" class="form-control" name="about_image_file">
                                <?php if (!empty($current_settings['about_image'])): ?>
                                    <div class="mt-2">
                                        <small class="text-muted">Actuelle :</small><br>
                                        <img src="../<?php echo htmlspecialchars($current_settings['about_image']); ?>" class="img-thumbnail" style="height: 100px;">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4 text-primary">Coordonnées</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Téléphone</label>
                                <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($current_settings['phone'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" name="email" value="<?php echo htmlspecialchars($current_settings['email'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Adresse</label>
                            <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($current_settings['address'] ?? ''); ?>">
                        </div>

                        <h5 class="mb-3 mt-4 text-primary">Contenu Page d'Accueil</h5>
                        <div class="mb-3">
                            <label class="form-label">Titre Principal (Hero)</label>
                            <input type="text" class="form-control" name="hero_title" value="<?php echo htmlspecialchars($current_settings['hero_title'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Texte Principal (Hero)</label>
                            <textarea class="form-control" name="hero_text" rows="3"><?php echo htmlspecialchars($current_settings['hero_text'] ?? ''); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Texte "À propos"</label>
                            <textarea class="form-control" name="about_text" rows="5"><?php echo htmlspecialchars($current_settings['about_text'] ?? ''); ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Enregistrer les modifications</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
