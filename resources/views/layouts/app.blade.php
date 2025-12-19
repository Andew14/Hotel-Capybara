<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Capybara - @yield('title')</title>
    <!-- Favicon (Capybara) -->
    <link rel="icon" href="https://img.icons8.com/color/48/capybara.png" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @yield('content')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function alertDelete(e, message) {
            if (!message) message = "¿Estás seguro de eliminar este registro?";
            e.preventDefault();
            const form = e.target;
            Swal.fire({
                title: '¿Estás seguro?',
                text: message,
                icon: 'warning',
                iconColor: '#D4A373',
                showCancelButton: true,
                confirmButtonColor: '#D4A373',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: '#272c28',
                color: '#F1F2F6',
                customClass: {
                    popup: 'glass-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Bypass the onsubmit handler to avoid loop if needed, but since we removed return confirm, form.submit() works
                }
            });
            return false;
        }
    </script>
</body>
</html>
