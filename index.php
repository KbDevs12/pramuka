<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eduzillen | Registrasi</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <script src="js/tailwind.config.js"></script>

    <!-- swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <style>
        body {
            scroll-behavior: smooth !important;
        }
    </style>
</head>

<body class="font-poppins antialiased">

    <?php include('includes/navbar.php'); ?>
    <!-- hero section -->
    <div id="top"></div>
    <?php include('includes/hero.php'); ?>
    <!-- pendaftaran section -->
    <?php include('includes/pendaftaran.php'); ?>
    <!-- Transaction Status Section -->
    <?php include('includes/status.php'); ?>
    <!-- about section -->
    <?php include('includes/about.php'); ?>
    <!-- footer -->
    <?php include('includes/footer.php'); ?>
    <!-- script -->
    <script src="js/menu.js"></script>
    <script src="js/validator.js"></script>
    <script src="js/main.js"></script>
    <script src="js/swiper.config.js"></script>
</body>

</html>