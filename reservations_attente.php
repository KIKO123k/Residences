<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

require_once 'connexion.php';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'], $_POST['action'])) {
    $reservationId = intval($_POST['reservation_id']);
    $action = $_POST['action'] === 'valider' ? 'confirmee' : 'refusee';

    try {
        $pdo->beginTransaction();

        $update = $pdo->prepare('UPDATE reservations SET statut = :statut WHERE id = :id');
        $update->execute(['statut' => $action, 'id' => $reservationId]);

        if ($action === 'confirmee') {
            $selectChambre = $pdo->prepare('SELECT chambre_id FROM reservations WHERE id = :id');
            $selectChambre->execute(['id' => $reservationId]);
            $chambreData = $selectChambre->fetch();

            if ($chambreData) {
                $chambreUpdate = $pdo->prepare('UPDATE chambres SET statut = :statut WHERE id = :chambre_id');
                $chambreUpdate->execute(['statut' => 'occupee', 'chambre_id' => $chambreData['chambre_id']]);
            }
        }

        $pdo->commit();
        $message = 'La réservation a été mise à jour.';
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = 'Erreur lors de la mise à jour : ' . htmlspecialchars($e->getMessage());
    }
}

$stmt = $pdo->query('SELECT r.id, u.nom AS etudiant, c.numero_chambre, r.date_reservation FROM reservations r JOIN utilisateurs u ON r.utilisateur_id = u.id JOIN chambres c ON r.chambre_id = c.id WHERE r.statut = "en_attente" ORDER BY r.date_reservation DESC');
$reservations = $stmt->fetchAll();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Réservations en attente - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>
<div class="container py-5">
    <div class="d-flex align-items-center gap-3 mb-4">
        <i class="bi bi-clock-fill fs-2 text-primary"></i>
        <div>
            <h1 class="h3 mb-1 text-primary">Réservations en attente</h1>
            <p class="text-muted mb-0">Liste des demandes de réservation à traiter.</p>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Étudiant</th>
                            <th>Chambre</th>
                            <th>Date de réservation</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reservations)): ?>
                            <tr>
                                <td colspan="5" class="text-center p-4">Aucune réservation en attente.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reservations as $reservation): ?>
                                <tr>
                                    <td><?= htmlspecialchars($reservation['id']) ?></td>
                                    <td><?= htmlspecialchars($reservation['etudiant']) ?></td>
                                    <td><?= htmlspecialchars($reservation['numero_chambre']) ?></td>
                                    <td><?= htmlspecialchars($reservation['date_reservation']) ?></td>
                                    <td>
                                        <form method="post" class="d-inline">
                                            <input type="hidden" name="reservation_id" value="<?= htmlspecialchars($reservation['id']) ?>">
                                            <input type="hidden" name="action" value="valider">
                                            <button type="submit" class="btn btn-sm btn-success me-1">
                                                <i class="bi bi-check2"></i> Valider
                                            </button>
                                        </form>
                                        <form method="post" class="d-inline">
                                            <input type="hidden" name="reservation_id" value="<?= htmlspecialchars($reservation['id']) ?>">
                                            <input type="hidden" name="action" value="refuser">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-x-lg"></i> Refuser
                                            </button>
                                        </form>
                                    </td>
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
