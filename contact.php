<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Contact RH - SIGRH</title>
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
        <h2 class="text-center mb-4">📨 Contacter le service RH</h2>
        <div class="card card-primary">
          <div class="card-body">
            <form method="post">
              <div class="form-group">
                <label>Nom complet</label>
                <input type="text" name="nom" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Message</label>
                <textarea name="message" class="form-control" rows="4" required></textarea>
              </div>
              <button type="submit" class="btn btn-primary">📤 Envoyer</button>
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
