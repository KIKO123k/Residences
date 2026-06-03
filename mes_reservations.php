<?php
session_start();
require_once 'connexion.php';

if (empty($_SESSION['role']) || $_SESSION['role'] !== 'etudiant') {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT r.id, c.numero_chambre, r.date_reservation, r.statut FROM reservations r JOIN chambres c ON r.chambre_id = c.id WHERE r.utilisateur_id = :user_id ORDER BY r.date_reservation DESC');
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$reservations = $stmt->fetchAll();

function badgeClass($statut) {
    return $statut === 'confirmee' ? 'success' : ($statut === 'en_attente' ? 'warning' : 'danger');
}

function statutLabel($statut) {
    return $statut === 'confirmee' ? 'Confirmée' : ($statut === 'en_attente' ? 'En attente' : 'Refusée');
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes réservations - Étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 text-success">Mes réservations</h1>
            <p class="text-muted mb-0">Historique de vos demandes de réservation.</p>
        </div>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Chambre</th>
                            <th>Date de réservation</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reservations)): ?>
                            <tr><td colspan="4" class="text-center p-4">Aucune réservation trouvée.</td></tr>
                        <?php else: ?>
                            <?php foreach ($reservations as $reservation): ?>
                                <tr>
                                    <td><?= htmlspecialchars($reservation['id']) ?></td>
                                    <td><?= htmlspecialchars($reservation['numero_chambre']) ?></td>
                                    <td><?= htmlspecialchars($reservation['date_reservation']) ?></td>
                                    <td><span class="badge bg-<?= badgeClass($reservation['statut']) ?>"><?= htmlspecialchars(statutLabel($reservation['statut'])) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
