<?php
session_start();
require_once 'connexion.php';

if (empty($_SESSION['role']) || $_SESSION['role'] !== 'etudiant') {
    header('Location: index.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['chambre_id'])) {
    $chambreId = intval($_POST['chambre_id']);

    $insert = $pdo->prepare('INSERT INTO reservations (utilisateur_id, chambre_id, date_reservation, statut) VALUES (:utilisateur_id, :chambre_id, :date_reservation, :statut)');
    $insert->execute([
        'utilisateur_id' => $_SESSION['user_id'],
        'chambre_id' => $chambreId,
        'date_reservation' => date('Y-m-d'),
        'statut' => 'en_attente',
    ]);
    $message = 'Votre réservation a bien été enregistrée et est en attente de validation.';
}

$stmt = $pdo->query('SELECT id, numero_chambre, capacite FROM chambres WHERE statut = "disponible" ORDER BY numero_chambre ASC');
$chambres = $stmt->fetchAll();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chambres disponibles - Étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 text-success">Chambres disponibles</h1>
            <p class="text-muted mb-0">Choisissez une chambre et envoyez une demande de réservation.</p>
        </div>
    </div>
    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Numéro</th>
                            <th>Capacité</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($chambres)): ?>
                            <tr><td colspan="5" class="text-center p-4">Aucune chambre disponible pour le moment.</td></tr>
                        <?php else: ?>
                            <?php foreach ($chambres as $chambre): ?>
                                <tr>
                                    <td><?= htmlspecialchars($chambre['id']) ?></td>
                                    <td><?= htmlspecialchars($chambre['numero_chambre']) ?></td>
                                    <td><?= htmlspecialchars($chambre['capacite']) ?></td>
                                    <td><span class="badge bg-success">Disponible</span></td>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" name="chambre_id" value="<?= htmlspecialchars($chambre['id']) ?>">
                                            <button type="submit" class="btn btn-sm btn-primary">Réserver</button>
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
