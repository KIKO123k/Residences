<?php
session_start();
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accueil Admin - Résidence universitaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>
<div class="container py-4">
    <div class="text-center mb-4">
        <h1 class="fw-bold text-primary">Espace administrateur</h1>
        <p class="text-muted">Bonjour <?= htmlspecialchars($_SESSION['nom']) ?>, gérez les chambres et les réservations.</p>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">Ajouter une chambre</h5>
                    <p class="card-text">Créez une nouvelle chambre disponible pour les étudiants.</p>
                    <a href="ajouter_chambre.php" class="btn btn-primary">Ajouter Chambre</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">Réservations en attente</h5>
                    <p class="card-text">Validez ou refusez les demandes de réservation des étudiants.</p>
                    <a href="reservations_attente.php" class="btn btn-success">Voir les demandes</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">Déconnexion</h5>
                    <p class="card-text">Terminez votre session de manière sécurisée.</p>
                    <a href="deconnexion.php" class="btn btn-danger">Se déconnecter</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
