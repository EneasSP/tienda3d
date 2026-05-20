<?php
/**
 * PÁGINA - Gestión de Clientes
 * tienda3d v2.1
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes - tienda3d</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        /* Apply dark class early to avoid flash */
        (function(){
            try {
                const key = 'tienda3d-dark-mode';
                const stored = localStorage.getItem(key);
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (stored === 'true' || (!stored && prefersDark)) {
                    document.documentElement.classList.add('dark');
                }
            } catch(e){}
        })();
    </script>
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    <link rel="stylesheet" href="assets/css/cruds.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <!-- Barra de navegación (copiada de index.php) -->
    <nav class="navbar">
        <div class="navbar-content">
            <a href="index.php" class="logo">
                <div class="logo-icon">🏪</div>
                <span>tienda3d</span>
            </a>
            <button id="hamburger-menu" class="hamburger-menu">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
            <div class="nav-tabs" id="nav-tabs">
                <button class="nav-tab active" data-tab="pedidos">📋 Pedidos</button>
                <button class="nav-tab" data-tab="catalogo">📦 Catálogo</button>
                <a href="clientes.php" class="nav-tab" data-tab="clientes">👥 Clientes</a>
                <a href="productos.php" class="nav-tab" data-tab="productos">🏭 Productos</a>
                <button class="nav-tab" data-tab="configuracion">⚙️ Configuración</button>
            </div>
            <div class="navbar-controls">
                <button id="dark-mode-toggle" class="dark-mode-toggle" title="Cambiar modo oscuro"></button>
            </div>
        </div>
    </nav>
    
    <div class="container mx-auto p-4 md:p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                👥 Gestión de Clientes
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Administra la cartera de clientes
            </p>
        </div>

        <!-- TOOLBAR -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <input 
                type="text" 
                id="searchCliente" 
                placeholder="Buscar cliente..." 
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-600 dark:text-white"
            >
            <button 
                onclick="abrirModalCliente()" 
                class="px-6 py-2 bg-gradient-to-r from-purple-600 to-sky-600 hover:from-purple-700 hover:to-sky-700 text-white font-semibold rounded-lg transition"
            >
                ➕ Nuevo Cliente
            </button>
        </div>

        <!-- TABLA DE CLIENTES -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <table class="tabla-responsive">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Empresa</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="clientesTabla">
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500">Cargando clientes...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL CLIENTE -->
    <div id="modalCliente" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full">
            <div class="border-b border-gray-200 dark:border-gray-700 p-6 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white" id="modalClienteTitulo">
                    Nuevo Cliente
                </h2>
                <button onclick="cerrarModalCliente()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400">
                    ✕
                </button>
            </div>

            <form id="formCliente" class="p-6 space-y-4" onsubmit="guardarCliente(event)">
                <input type="hidden" id="clienteId">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nombre Completo *
                    </label>
                    <input 
                        type="text" 
                        id="clienteNombre" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Email *
                    </label>
                    <input 
                        type="email" 
                        id="clienteEmail" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Teléfono
                    </label>
                    <input 
                        type="tel" 
                        id="clienteTelefono" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Empresa
                    </label>
                    <input 
                        type="text" 
                        id="clienteEmpresa" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                </div>

                <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button 
                        type="submit" 
                        class="flex-1 px-4 py-2 bg-gradient-to-r from-purple-600 to-sky-600 hover:from-purple-700 hover:to-sky-700 text-white font-semibold rounded-lg transition"
                    >
                        💾 Guardar
                    </button>
                    <button 
                        type="button"
                        onclick="cerrarModalCliente()"
                        class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg transition"
                    >
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
    <script src="assets/js/clientes.js"></script>
</body>
</html>
