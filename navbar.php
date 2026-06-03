<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="index.php">Résidence U</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="espace_admin.php">Accueil admin</a></li>
                    <li class="nav-item"><a class="nav-link" href="ajouter_chambre.php">Ajouter Chambre</a></li>
                    <li class="nav-item"><a class="nav-link" href="reservations_attente.php">Réservations en attente</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="deconnexion.php">Déconnexion</a></li>
                <?php elseif (!empty($_SESSION['role']) && $_SESSION['role'] === 'etudiant'): ?>
                    <li class="nav-item"><a class="nav-link" href="chambres_disponibles.php">Chambres disponibles</a></li>
                    <li class="nav-item"><a class="nav-link" href="mes_reservations.php">Mes réservations</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="deconnexion.php">Déconnexion</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="index.php">Connexion</a></li>
                    <li class="nav-item"><a class="nav-link" href="nouveau_compte.php">S'inscrire</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
