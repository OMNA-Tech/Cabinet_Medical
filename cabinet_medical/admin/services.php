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

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    if ($stmt->execute([$_GET['id']])) {
        $success = "Service supprimé avec succès.";
    } else {
        $error = "Erreur lors de la suppression du service.";
    }
}

// Handle Add/Edit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $icon = $_POST['icon'];
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $stmt = $pdo->prepare("UPDATE services SET title = ?, description = ?, icon = ? WHERE id = ?");
        if ($stmt->execute([$title, $description, $icon, $_POST['id']])) {
            $success = "Service mis à jour avec succès.";
        } else {
            $error = "Erreur lors de la mise à jour.";
        }
    } else {
        // Add
        $stmt = $pdo->prepare("INSERT INTO services (title, description, icon) VALUES (?, ?, ?)");
        if ($stmt->execute([$title, $description, $icon])) {
            $success = "Service ajouté avec succès.";
        } else {
            $error = "Erreur lors de l'ajout.";
        }
    }
}

// Fetch services
$stmt = $pdo->query("SELECT * FROM services ORDER BY created_at ASC");
$services = $stmt->fetchAll();

// Get service for edit
$edit_service = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $edit_service = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les Services - OMNA Santé Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="../index.php"><i class="fas fa-heartbeat me-2"></i> OMNA Santé Admin</a>
        <div class="d-flex">
            <a href="dashboard.php" class="btn btn-light text-primary me-2">Rendez-vous</a>
            <a href="settings.php" class="btn btn-light text-primary me-2">Paramètres</a>
            <a href="../logout.php" class="btn btn-outline-light">Déconnexion</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><?php echo $edit_service ? 'Modifier le service' : 'Ajouter un service'; ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="services.php">
                        <?php if ($edit_service): ?>
                            <input type="hidden" name="id" value="<?php echo $edit_service['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Titre</label>
                            <input type="text" class="form-control" name="title" required value="<?php echo $edit_service ? htmlspecialchars($edit_service['title']) : ''; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="4" required><?php echo $edit_service ? htmlspecialchars($edit_service['description']) : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Icône (FontAwesome)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="<?php echo $edit_service ? htmlspecialchars($edit_service['icon']) : 'fas fa-stethoscope'; ?>"></i></span>
                                <input type="text" class="form-control" name="icon" placeholder="fas fa-stethoscope" required value="<?php echo $edit_service ? htmlspecialchars($edit_service['icon']) : 'fas fa-stethoscope'; ?>">
                            </div>
                            <div class="form-text">Utilisez les classes FontAwesome (ex: fas fa-heartbeat)</div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary"><?php echo $edit_service ? 'Mettre à jour' : 'Ajouter'; ?></button>
                            <?php if ($edit_service): ?>
                                <a href="services.php" class="btn btn-outline-secondary">Annuler</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Liste des Services</h5>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">Icône</th>
                                    <th>Service</th>
                                    <th style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($services as $service): ?>
                                <tr>
                                    <td class="text-center text-primary">
                                        <i class="<?php echo htmlspecialchars($service['icon']); ?> fa-lg"></i>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($service['title']); ?></div>
                                        <div class="small text-muted"><?php echo htmlspecialchars($service['description']); ?></div>
                                    </td>
                                    <td>
                                        <a href="services.php?action=edit&id=<?php echo $service['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                        <a href="services.php?action=delete&id=<?php echo $service['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?');"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
