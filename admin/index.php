<?php
session_start();
require('../config/app.php');

if (!isset($_SESSION['key'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Eduzille</title>
    <link rel="icon" type="image/png" href="../images/logo.PNG">
    <link rel="apple-touch-icon" href="../images/logo.PNG">
    <link rel="shortcut icon" type="image/png" href="../images/logo.PNG">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="font-poppins bg-gray-100 text-gray-800">

    <!-- Mobile Header -->
    <header class="lg:hidden bg-white p-4 flex items-center justify-between shadow-md fixed w-full top-0 z-50">
        <button id="mobile-menu-button" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <h1 class="text-xl font-bold">Admin Panel</h1>
        <div></div>
    </header>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="w-64 bg-white h-screen fixed left-0 top-0 shadow-lg transform -translate-x-full lg:transform-none lg:translate-x-0 transition-transform duration-300 z-40">
        <div class="p-4 border-b flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-800">Eduzille</h1>
            <button id="sidebar-close" class="lg:hidden text-gray-600 hover:text-gray-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav class="mt-4">
            <a href="index.php" class="px-4 py-2 hover:bg-gray-100 rounded-md transition flex items-center">
                <i class="fas fa-home w-6"></i>
                <span class="ml-2">Dashboard</span>
            </a>
            <div class="px-4 py-2 font-semibold text-gray-600 mt-6">Data Master</div>
            <a href="#" onclick="loadPage('kategori.php')"
                class="px-4 py-2 hover:bg-gray-100 rounded-md transition flex items-center">
                <i class="fas fa-tags w-6"></i>
                <span class="ml-2">Kategori</span>
            </a>
            <a href="#" onclick="loadPage('perlombaan.php')"
                class="px-4 py-2 hover:bg-gray-100 rounded-md transition flex items-center">
                <i class="fas fa-trophy w-6"></i>
                <span class="ml-2">Perlombaan</span>
            </a>

            <div class="px-4 py-2 font-semibold text-gray-600 mt-6">Transaksi</div>
            <a href="#" onclick="loadPage('transaksi.php')"
                class="px-4 py-2 hover:bg-gray-100 rounded-md transition flex items-center">
                <i class="fas fa-exchange-alt w-6"></i>
                <span class="ml-2">Data Transaksi</span>
            </a>
            <a href="#" onclick="loadPage('peserta.php')"
                class="px-4 py-2 hover:bg-gray-100 rounded-md transition flex items-center">
                <i class="fas fa-users w-6"></i>
                <span class="ml-2">Data Peserta</span>
            </a>

            <div class="px-4 py-2 mt-6">
                <a href="logout.php"
                    class="bg-red-500 text-white px-4 py-2 rounded-md text-center hover:bg-red-600 transition flex items-center justify-center">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    <span>Logout</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="lg:ml-64 p-8 mt-16 lg:mt-0 transition-all duration-300">
        <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            include($page . '.php');
        } else {
            include('dashboard.php');
        }
        ?>
    </main>

    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black opacity-50 z-30 hidden lg:hidden"></div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebarClose = document.getElementById('sidebar-close');
        const overlay = document.getElementById('overlay');
        const mainContent = document.querySelector('main');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }

        mobileMenuButton.addEventListener('click', toggleSidebar);
        sidebarClose.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Page Loading
        function loadPage(page) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', page, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    mainContent.innerHTML = xhr.responseText;

                    const scripts = mainContent.getElementsByTagName('script');
                    for (let script of scripts) {
                        const newScript = document.createElement('script');
                        if (script.src) {
                            newScript.src = script.src;
                        } else {
                            newScript.textContent = script.textContent;
                        }
                        mainContent.appendChild(newScript);
                        script.remove();
                    }
                }
            };
            xhr.send();
        }

        // Add active state to current page
        const navLinks = document.querySelectorAll('nav a');
        navLinks.forEach(link => {
            link.addEventListener('click', function () {
                navLinks.forEach(l => l.classList.remove('bg-gray-100'));
                this.classList.add('bg-gray-100');
            });
        });
    </script>

</body>

</html>