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

// Handle Status Update
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
    $status = ($action == 'accept') ? 'confirmed' : 'cancelled';
    
    $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    
    header("Location: dashboard.php");
    exit;
}

// Fetch appointments
$stmt = $pdo->query("SELECT a.*, u.name as patient_name, u.email, u.phone 
                     FROM appointments a 
                     JOIN users u ON a.user_id = u.id 
                     ORDER BY a.appointment_date DESC, a.appointment_time ASC");
$appointments = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - OMNA Santé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="../index.php"><i class="fas fa-heartbeat me-2"></i> OMNA Santé Admin</a>
        <div class="d-flex">
            <a href="services.php" class="btn btn-light text-primary me-2">Services</a>
            <a href="settings.php" class="btn btn-light text-primary me-2">Paramètres</a>
            <a href="../logout.php" class="btn btn-outline-light">Déconnexion</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <h2 class="mb-4">Gestion des Rendez-vous</h2>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date & Heure</th>
                            <th>Patient</th>
                            <th>Motif</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appt): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?php echo date('d/m/Y', strtotime($appt['appointment_date'])); ?></div>
                                <div class="small text-muted"><?php echo substr($appt['appointment_time'], 0, 5); ?></div>
                            </td>
                            <td>
                                <div><?php echo htmlspecialchars($appt['patient_name']); ?></div>
                                <div class="small text-muted"><?php echo htmlspecialchars($appt['phone']); ?></div>
                            </td>
                            <td><?php echo htmlspecialchars($appt['reason']); ?></td>
                            <td><?php echo htmlspecialchars($appt['message']); ?></td>
                            <td>
                                <?php if($appt['status'] == 'confirmed'): ?>
                                    <span class="badge bg-success">Confirmé</span>
                                <?php elseif($appt['status'] == 'cancelled'): ?>
                                    <span class="badge bg-danger">Annulé</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">En attente</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($appt['status'] == 'pending'): ?>
                                    <a href="dashboard.php?action=accept&id=<?php echo $appt['id']; ?>" class="btn btn-sm btn-success" title="Accepter"><i class="fas fa-check"></i></a>
                                    <a href="dashboard.php?action=refuse&id=<?php echo $appt['id']; ?>" class="btn btn-sm btn-danger" title="Refuser"><i class="fas fa-times"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($appointments)): ?>
                            <tr><td colspan="6" class="text-center py-4">Aucun rendez-vous trouvé.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
