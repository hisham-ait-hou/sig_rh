<?php
require_once 'includes/db.php';
// Connexion BDD si tu veux l'enregistrer (optionnel)
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $type = $_POST['type'];
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'] ?? '';
  $email = $_POST['email'];
  $message = $_POST['message'];
  $cv_name = null;

  if (!empty($_FILES['cv']['name'])) {
    $cv_name = uniqid('cv_') . '.' . pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
    move_uploaded_file($_FILES['cv']['tmp_name'], 'candidatures/' . $cv_name);
  }

  // Insertion en base
  $stmt = $pdo->prepare("INSERT INTO candidatures (nom, prenom, email, cv, statut, date_soumission) VALUES (?, ?, ?, ?, 'reçu', NOW())");
  $stmt->execute([$nom, $prenom, $email, $cv_name]);
  $success = "📥 Candidature envoyée avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Candidature - SIGRH</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">
  <div class="content-wrapper">
    <div class="content p-4">
      <div class="container">
        <h2 class="text-center mb-4">📄 Déposer une candidature</h2>

        <?php if ($success): ?>
          <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <div class="card card-info">
          <div class="card-body">
            <form method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label>Type de demande</label>
                <select name="type" class="form-control" required>
                  <option value="">-- Choisir --</option>
                  <option value="emploi">Demande d'emploi</option>
                  <option value="stage">Demande de stage</option>
                </select>
              </div>

              <div class="form-group">
                <label>Nom complet</label>
                <input type="text" name="nom" class="form-control" required>
              </div>

              <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="prenom" class="form-control">
              </div>

              <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>

              <div class="form-group">
                <label>Lettre de motivation</label>
                <textarea name="message" class="form-control" rows="4" required></textarea>
              </div>

              <div class="form-group">
                <label>📎 Joindre un CV (PDF)</label>
                <input type="file" name="cv" class="form-control-file" accept=".pdf" required>
              </div>

              <button type="submit" class="btn btn-info">📤 Envoyer la candidature</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/dist/js/adminlte.min.js"></script>
</body>
</html>
