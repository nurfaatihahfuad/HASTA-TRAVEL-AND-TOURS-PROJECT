<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
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
                <a href="#" class="block px-6 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Booking History</a>
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
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Customer Dashboard</h2>

            <!-- Profile Info -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-6">
                <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-100">Profile Info</h3>
                <p><strong>Name:</strong> Asyura Rania</p>
                <p><strong>Username:</strong> asyura.rania</p>
                <p><strong>Password:</strong> ********</p>
                <p><strong>Status:</strong> Active</p>
                <a href="#" class="text-blue-600 hover:underline mt-2 inline-block">Edit Profile</a>
            </div>

            <!-- Booking Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 p-4 rounded shadow text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">5</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded shadow text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Days Rented</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">24</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded shadow text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Most Rented Car</p>
                    <p class="text-xl font-semibold text-gray-800 dark:text-gray-100">Perodua Axia</p>
                </div>
            </div>

            <!-- Booking History -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Booking History</h3>

                <div class="mb-4 border-b pb-4">
                    <p><strong>Car:</strong> Perodua Axia 2018</p>
                    <p><strong>Booking Dates:</strong> 12 May 2024 (10.00 A.M) → 15 May 2024 (12.00 A.M)</p>
                    <p><strong>Route:</strong> UTM → KLIA</p>
                    <p><strong>Status:</strong> Completed</p>
                    <p><strong>Cost:</strong> RM 1200</p>
                </div>

                <div class="mb-4 border-b pb-4">
                    <p><strong>Car:</strong> Perodua Myvi 2015</p>
                    <p><strong>Booking Dates:</strong> 21 March 2024 (11.00 A.M) → 22 March 2024 (2.00 P.M)</p>
                    <p><strong>Route:</strong> UTM → Seremban</p>
                    <p><strong>Status:</strong> Completed</p>
                    <p><strong>Cost:</strong> RM 850</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>