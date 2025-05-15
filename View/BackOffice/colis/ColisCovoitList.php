<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Ajouter un Colis</title>

  <!-- css Imports -->
  <link rel="stylesheet" href="assets/css/crud.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="../../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/colis.css">
  <link rel="stylesheet" href="../../assets/css/main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

</head>

<body style="margin: 0; padding: 0;">
  <div class="dashboard">
    <?php include 'sidebar.php'; ?>
    <main class="main-content">
      <section>
        <?php include 'ColisCovoitDisplay.php'; ?>
      </section>
    </main>
  </div>
</body>

</html>