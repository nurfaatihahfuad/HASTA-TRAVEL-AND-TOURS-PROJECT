<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-gray-800 shadow-md">
            <div class="p-6 text-lg font-bold text-gray-800 dark:text-gray-200">
                HASTA
            </div>
            <nav class="mt-6 space-y-2">
                <a href="#" class="block px-6 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Dashboard</a>
                <a href="#" class="block px-6 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Car Inspection Checklist</a>
                <a href="#" class="block px-6 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Blacklisted Record</a>
                <a href="#" class="block px-6 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Sales Record</a>
                <a href="#" class="block px-6 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Payment Record</a>
                <a href="#" class="block px-6 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Pending Payment</a>
                <a href="#" class="block px-6 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Damage Case</a>
                <a href="#" class="block px-6 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
                <a href="#" class="block px-6 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Settings</a>
                <form method="POST" action="{{ route('logout') }}" class="px-6 py-2">
                    @csrf
                    <button type="submit" class="w-full text-left text-red-600 hover:bg-red-100 dark:hover:bg-red-700 rounded">
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Staff Dashboard</h2>
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                {{ __("You're logged in!") }}
            </div>
        </main>
    </div>
</body>
</html>