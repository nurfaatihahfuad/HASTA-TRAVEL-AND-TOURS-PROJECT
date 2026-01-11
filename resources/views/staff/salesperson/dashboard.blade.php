<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salesperson Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Salesperson Dashboard</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <!-- Sidebar -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Menu</h5>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('salesperson.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                            <li><a href="#" class="text-decoration-none">Customers</a></li>
                            <li><a href="#" class="text-decoration-none">Bookings</a></li>
                            <li><a href="#" class="text-decoration-none">Reports</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <!-- Main Content -->
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title">Welcome to Salesperson Dashboard</h1>
                        <p class="card-text">This is your sales dashboard.</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card text-white bg-primary mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Sales</h5>
                                        <h2 class="card-text">RM 0</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-white bg-success mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Bookings</h5>
                                        <h2 class="card-text">0</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-white bg-info mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Customers</h5>
                                        <h2 class="card-text">0</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>