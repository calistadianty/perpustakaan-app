<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Pembaca | Rumah Baca</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .reveal { opacity: 0; transform: translateY(40px); transition: all 0.8s ease; }
        .reveal.active { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <!-- NAVBAR USER -->
    <!-- NAVBAR USER -->
    <!-- NAVBAR USER -->
    <x-navbar />

    <main>
        @yield('content')
    </main>

    <script>
        const reveal = document.querySelectorAll('.reveal');
        function showOnScroll() {
            reveal.forEach(el => {
                const windowHeight = window.innerHeight;
                const elementTop = el.getBoundingClientRect().top;
                if (elementTop < windowHeight - 120) {
                    el.classList.add('active');
                }
            });
        }
        window.addEventListener('scroll', showOnScroll);
        showOnScroll();
    </script>
</body>
</html>
