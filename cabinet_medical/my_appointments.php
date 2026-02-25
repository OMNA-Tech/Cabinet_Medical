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

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM appointments WHERE user_id = ? ORDER BY appointment_date DESC, appointment_time DESC");
$stmt->execute([$user_id]);
$appointments = $stmt->fetchAll();

include 'includes/header.php';
?>

<!-- Page Header -->
<div class="bg-primary text-white py-5 text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Mes Rendez-vous</h1>
        <p class="lead">Consultez l'historique et le statut de vos demandes.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4">
                    <?php if (empty($appointments)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times text-muted display-1 mb-3"></i>
                            <h3>Aucun rendez-vous</h3>
                            <p class="text-muted">Vous n'avez pas encore pris de rendez-vous.</p>
                            <a href="appointment.php" class="btn btn-primary mt-3">Prendre rendez-vous</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Motif</th>
                                        <th>Statut</th>
                                        <th>Détails</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($appointments as $appt): ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo date('d/m/Y', strtotime($appt['appointment_date'])); ?></td>
                                        <td><?php echo substr($appt['appointment_time'], 0, 5); ?></td>
                                        <td><?php echo htmlspecialchars($appt['reason']); ?></td>
                                        <td>
                                            <?php if($appt['status'] == 'confirmed'): ?>
                                                <span class="badge bg-success rounded-pill px-3">Confirmé</span>
                                            <?php elseif($appt['status'] == 'cancelled'): ?>
                                                <span class="badge bg-danger rounded-pill px-3">Annulé</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark rounded-pill px-3">En attente</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal<?php echo $appt['id']; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modal<?php echo $appt['id']; ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Détails du rendez-vous</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p><strong>Date:</strong> <?php echo date('d/m/Y', strtotime($appt['appointment_date'])); ?></p>
                                                            <p><strong>Heure:</strong> <?php echo substr($appt['appointment_time'], 0, 5); ?></p>
                                                            <p><strong>Motif:</strong> <?php echo htmlspecialchars($appt['reason']); ?></p>
                                                            <?php if (!empty($appt['message'])): ?>
                                                                <p><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($appt['message'])); ?></p>
                                                            <?php endif; ?>
                                                            <p><strong>Statut:</strong> <?php echo ucfirst($appt['status']); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
