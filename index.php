<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$error = '';
if (is_post_request() && isset($_POST['login_submit'])) {
    $email = trim((string) ($_POST['email'] ?? ''));
    $motDePasse = (string) ($_POST['mot_de_passe'] ?? '');

    $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && verify_user_password($user, $motDePasse, $pdo)) {
        login_user($user);
        refresh_current_user($pdo);

        $role = current_user()['role'] ?? null;
        if ($role === 'rh') {
            redirect('back-office/dashboard.php');
        }
        if ($role === 'responsable') {
            redirect('responsable/dashboard.php');
        }
        if ($role === 'employe') {
            redirect('front-office/dashboard.php');
        }

        logout_user();
        $error = "Role inconnu, acces refuse.";
    } else {
        $error = "Identifiants incorrects.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>SIGRH - Accueil</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?= h(base_url('assets/plugins/fontawesome-free/css/all.min.css')) ?>">
  <link rel="stylesheet" href="<?= h(base_url('assets/plugins/bootstrap/css/bootstrap.min.css')) ?>">
  <link rel="stylesheet" href="<?= h(base_url('assets/dist/css/adminlte.min.css')) ?>">
  <style>
    body, html {
      height: 100%;
      margin: 0;
      padding: 0;
    }

    .bg-hero {
      background: url('<?= h(base_url('assets/dist/img/pigier1.jpg')) ?>') center center/cover no-repeat;
      min-height: 100vh;
      position: relative;
    }

    .bg-overlay {
      position: absolute;
      inset: 0;
      background: rgba(30, 40, 44, 0.65);
      z-index: 1;
    }

    .hero-content {
      position: relative;
      z-index: 2;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .welcome-block {
      color: #fff;
      max-width: 500px;
      margin-right: 40px;
    }

    .welcome-block h1 {
      font-size: 2.8rem;
      font-weight: 700;
      margin-bottom: 1rem;
    }

    .welcome-block p {
      font-size: 1.2rem;
      margin-bottom: 2rem;
    }

    .btn-action {
      font-size: 1.1em;
      padding: 10px 28px;
      margin: 8px 8px 8px 0;
      border-radius: 25px;
      font-weight: 500;
      box-shadow: 0 2px 8px rgba(20, 46, 63, 0.08);
      transition: background 0.2s, color 0.2s;
    }

    .login-card {
      background: rgba(19, 16, 16, 0.25);
      border-radius: 18px;
      box-shadow: 0 4px 24px rgba(60, 140, 188, 0.13);
      padding: 2.5rem 2rem 2rem 2rem;
      min-width: 320px;
      max-width: 350px;
    }

    .main-footer {
      background: rgba(19, 16, 16, 0.25);
      color: #fff;
      padding: 18px 0 10px 0;
      font-size: 1em;
      border-radius: 30px 30px 0 0;
      margin-top: 0;
      box-shadow: 0 -2px 12px rgba(60, 140, 188, 0.08);
      position: relative;
      z-index: 3;
    }

    @media (max-width: 991px) {
      .hero-content {
        flex-direction: column;
        justify-content: flex-start;
        padding-top: 60px;
      }

      .welcome-block {
        margin-right: 0;
        margin-bottom: 40px;
        text-align: center;
      }
    }
  </style>
</head>
<body>
  <div class="bg-hero">
    <div class="bg-overlay"></div>
    <div class="container hero-content">
      <div class="welcome-block">
        <h1>Bienvenue sur SIGRH</h1>
        <p>La plateforme RH pour gerer vos demandes, bulletins de paie, candidatures et contacts en un seul endroit.</p>
        <a href="<?= h(base_url('candidature.php')) ?>" class="btn btn-info btn-action"><i class="fas fa-file-alt"></i> Deposer une candidature</a>
        <a href="<?= h(base_url('contact.php')) ?>" class="btn btn-outline-light btn-action"><i class="fas fa-envelope"></i> Contact RH</a>
      </div>
      <div class="login-card ml-auto">
        <?php if ($error !== ''): ?>
          <div class="alert alert-danger"><?= h($error) ?></div>
        <?php endif; ?>
        <form method="post">
          <div class="form-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
          </div>
          <div class="form-group mb-3">
            <input type="password" name="mot_de_passe" class="form-control" placeholder="Mot de passe" required>
          </div>
          <button type="submit" name="login_submit" class="btn btn-primary btn-block">Connexion</button>
        </form>
      </div>
    </div>
    <footer class="main-footer text-center">
      <strong>SIGRH</strong> - Projet GESTION RH &copy; <?= date('Y') ?>
    </footer>
  </div>

  <script src="<?= h(base_url('assets/plugins/jquery/jquery.min.js')) ?>"></script>
  <script src="<?= h(base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')) ?>"></script>
  <script src="<?= h(base_url('assets/dist/js/adminlte.min.js')) ?>"></script>
</body>
</html>
