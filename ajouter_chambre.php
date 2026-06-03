<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

require_once 'connexion.php';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = trim($_POST['numero_chambre'] ?? '');
    $capacite = intval($_POST['capacite'] ?? 0);
    $statut = $_POST['statut'] ?? 'disponible';

    if ($numero === '' || $capacite <= 0) {
        $error = 'Veuillez renseigner un numéro de chambre et une capacité valides.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO chambres (numero_chambre, capacite, statut) VALUES (:numero, :capacite, :statut)');
            $stmt->execute([
                'numero' => $numero,
                'capacite' => $capacite,
                'statut' => $statut,
            ]);
            $message = 'La chambre a bien été enregistrée.';
        } catch (PDOException $e) {
            $error = 'Erreur lors de l’enregistrement : ' . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter une chambre - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex align-items-center gap-2">
                    <i class="bi bi-door-closed fs-4"></i>
                    <h4 class="mb-0">Ajouter une chambre</h4>
                </div>
                <div class="card-body">
                    <?php if ($message): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="post" novalidate>
                        <div class="mb-3">
                            <label for="numero_chambre" class="form-label">Numéro de chambre</label>
                            <input type="text" class="form-control" id="numero_chambre" name="numero_chambre" required>
                        </div>
                        <div class="mb-3">
                            <label for="capacite" class="form-label">Capacité</label>
                            <input type="number" class="form-control" id="capacite" name="capacite" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="statut" class="form-label">Statut</label>
                            <select class="form-select" id="statut" name="statut">
                                <option value="disponible" selected>Disponible</option>
                                <option value="occupee">Occupée</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success px-5">
                                <i class="bi bi-check2 me-1"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-white text-center">
                    <a href="espace_admin.php" class="text-primary text-decoration-none">Retour à l'accueil administrateur</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
