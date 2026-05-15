<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$success = '';
$error = '';

if (is_post_request()) {
    $type = trim((string) ($_POST['type'] ?? ''));
    $nom = trim((string) ($_POST['nom'] ?? ''));
    $prenom = trim((string) ($_POST['prenom'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $message = trim((string) ($_POST['message'] ?? ''));

    if ($type === '' || $nom === '' || $email === '' || $message === '') {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse email invalide.';
    } else {
        $uploadError = null;
        $cvName = upload_public_file($_FILES['cv'] ?? [], 'candidatures', ['pdf'], 'cv_', $uploadError);
        if ($cvName === null) {
            $error = $uploadError ?? 'CV obligatoire.';
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO candidatures (type, nom, prenom, email, message, cv, statut, date_soumission)
                VALUES (?, ?, ?, ?, ?, ?, 'recu', NOW())
            ");
            $stmt->execute([$type, $nom, $prenom, $email, $message, $cvName]);
            $success = 'Candidature envoyee avec succes.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Candidature - SIGRH</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?= h(base_url('assets/plugins/fontawesome-free/css/all.min.css')) ?>">
  <link rel="stylesheet" href="<?= h(base_url('assets/plugins/bootstrap/css/bootstrap.min.css')) ?>">
  <link rel="stylesheet" href="<?= h(base_url('assets/dist/css/adminlte.min.css')) ?>">
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">
  <div class="content-wrapper">
    <div class="content p-4">
      <div class="container">
        <h2 class="text-center mb-4">Deposer une candidature</h2>

        <?php if ($success !== ''): ?>
          <div class="alert alert-success"><?= h($success) ?></div>
        <?php elseif ($error !== ''): ?>
          <div class="alert alert-danger"><?= h($error) ?></div>
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
                <label>Nom</label>
                <input type="text" name="nom" class="form-control" required>
              </div>

              <div class="form-group">
                <label>Prenom</label>
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
                <label>Joindre un CV (PDF)</label>
                <input type="file" name="cv" class="form-control-file" accept=".pdf" required>
              </div>

              <button type="submit" class="btn btn-info">Envoyer la candidature</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?= h(base_url('assets/plugins/jquery/jquery.min.js')) ?>"></script>
<script src="<?= h(base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')) ?>"></script>
<script src="<?= h(base_url('assets/dist/js/adminlte.min.js')) ?>"></script>
</body>
</html>
