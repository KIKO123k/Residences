<?php
session_start();
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'etudiant') {
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accueil étudiant - Résidence universitaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>
<div class="container py-4">
    <div class="text-center mb-4">
        <h1 class="fw-bold text-success">Espace étudiant</h1>
        <p class="text-muted">Bonjour <?= htmlspecialchars($_SESSION['nom']) ?>, consultez les chambres disponibles et vos réservations.</p>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">Chambres disponibles</h5>
                    <p class="card-text">Découvrez les chambres libres et réservez facilement.</p>
                    <a href="chambres_disponibles.php" class="btn btn-primary">Voir les chambres</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">Mes réservations</h5>
                    <p class="card-text">Suivez le statut de vos demandes de réservation.</p>
                    <a href="mes_reservations.php" class="btn btn-success">Voir mes réservations</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">Déconnexion</h5>
                    <p class="card-text">Terminez votre session en toute sécurité.</p>
                    <a href="deconnexion.php" class="btn btn-danger">Se déconnecter</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
