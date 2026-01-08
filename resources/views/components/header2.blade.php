
    <style>
        .navbar {
            background-color: #fff8f5;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .navbar-brand img {
            height: 40px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        
        .navbar-brand img:hover {
            transform: scale(1.05);
        }
        
        .nav-link {
            font-weight: 500;
            color: #333 !important;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
            border-radius: 4px;
            margin: 0 2px;
        }
        
        .nav-link:hover, 
        .nav-link:focus,
        .nav-link.active {
            color: #dc3545 !important;
            background-color: rgba(220, 53, 69, 0.05);
        }
        
        /* HOVER DROPDOWN STYLES - FOR DESKTOP */
        @media (min-width: 992px) {
            .dropdown-hover:hover .dropdown-menu,
            .dropdown-hover .dropdown-toggle:hover + .dropdown-menu,
            .dropdown-hover .nav-link:focus + .dropdown-menu {
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
                transform: translateY(0) !important;
                pointer-events: auto !important;
            }
            
            .dropdown-hover .dropdown-menu {
                display: block !important;
                opacity: 0;
                visibility: hidden;
                transform: translateY(-10px);
                transition: all 0.3s ease;
                pointer-events: none;
                margin-top: 0;
            }
            
            .dropdown-hover:hover > .dropdown-menu {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
                pointer-events: auto;
            }
            
            /* Keep dropdown open when hovering over menu */
            .dropdown-menu:hover {
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
            }
            
            /* Adjust caret for hover dropdown */
            .dropdown-hover .dropdown-toggle::after {
                content: "▾";
                border: none;
                font-size: 1.2em;
                vertical-align: middle;
                margin-left: 0.5rem;
            }
        }
        
        .dropdown-menu {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
            animation: fadeIn 0.2s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .dropdown-item {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
            color: #333;
            position: relative;
        }
        
        .dropdown-item:hover,
        .dropdown-item:focus {
            background-color: #dc3545 !important;
            color: white !important;
            padding-left: 2rem;
        }
        
        .dropdown-item:hover::before {
            content: "→";
            position: absolute;
            left: 1rem;
            color: white;
        }
        
        /* Mobile responsiveness - Keep click functionality */
        @media (max-width: 991.98px) {
            .navbar-nav {
                padding: 1rem 0;
            }
            
            .nav-item {
                margin: 5px 0;
            }
            
            .dropdown-menu {
                border: none;
                box-shadow: none;
                background-color: transparent;
                padding-left: 1.5rem;
            }
            
            .dropdown-item {
                padding: 0.5rem 1rem;
            }
            
            .dropdown-hover .dropdown-toggle::after {
                content: "▸";
                border: none;
                font-size: 1.2em;
                float: right;
                margin-top: 0.3rem;
            }
        }
        
        /* Fix for dropdown positioning */
        .dropdown-menu-end {
            right: 0;
            left: auto;
        }
        
        /* Search form styling */
        .navbar .form-control {
            border-radius: 0.375rem;
            border: 1px solid #dee2e6;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .navbar .form-control:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }
        
        .navbar .btn-primary {
            background-color: #dc3545;
            border-color: #dc3545;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }
        
        .navbar .btn-primary:hover {
            background-color: #c82333;
            border-color: #bd2130;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }
        
        /* Add smooth transition for all */
        * {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- HEADER START -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('img/hasta.jpeg') }}" alt="Hasta Logo" style="height:40px;">
            </a>

            <!-- Mobile toggle button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
                aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar content -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <!-- Navigation links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="/browse-car">Book Car</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#">About Us</a>
                    </li>
                    
                    <!-- User Dropdown - HOVER ENABLED -->
                    <li class="nav-item dropdown dropdown-hover position-static">
                        <a class="nav-link dropdown-toggle fw-semibold" href="#" id="navbarDropdown" 
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-1"></i> User
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-2"></i>Log In
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.register') }}">
                                    <i class="fas fa-user-plus me-2"></i>Sign Up
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-history me-2"></i>Booking History
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cog me-2"></i>Account Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-question-circle me-2"></i>Help Center
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <!-- Search form -->
                <form class="d-flex" role="search">
                    <div class="input-group">
                        <input class="form-control" type="search" placeholder="Search cars..." 
                               aria-label="Search">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </nav>
    <!-- HEADER END -->

    <!-- Demo content to test sticky navbar -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1>Hasta Car Rental</h1>
                <p class="lead">Premium car rental service with the best rates.</p>
                <p>Hover over "User" in the navbar to see the dropdown appear instantly.</p>
                <p>On mobile devices, you still need to click (as hover doesn't work on touch devices).</p>
                
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Features:</h5>
                        <ul>
                            <li><strong>Hover dropdown</strong> on desktop (no click needed)</li>
                            <li><strong>Click dropdown</strong> on mobile (touch devices)</li>
                            <li>Smooth animations and transitions</li>
                            <li>Dropdown stays open when hovering over menu items</li>
                            <li>Visual feedback on all interactions</li>
                        </ul>
                    </div>
                </div>
                
                <div style="height: 1000px; margin-top: 2rem;"></div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Font Awesome for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    
    <script>
        // Hover dropdown functionality with fallback for mobile
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.querySelector('.dropdown-hover');
            const dropdownToggle = dropdown.querySelector('.dropdown-toggle');
            const dropdownMenu = dropdown.querySelector('.dropdown-menu');
            
            // Check if we're on desktop (hover capable)
            function isDesktop() {
                return window.innerWidth >= 992;
            }
            
            // Handle hover on desktop
            if (isDesktop()) {
                // Prevent Bootstrap's default click toggle on desktop
                dropdownToggle.addEventListener('click', function(e) {
                    if (isDesktop()) {
                        e.preventDefault();
                    }
                });
                
                // Show on hover
                dropdown.addEventListener('mouseenter', function() {
                    if (isDesktop()) {
                        dropdownMenu.style.display = 'block';
                        setTimeout(() => {
                            dropdownMenu.style.opacity = '1';
                            dropdownMenu.style.visibility = 'visible';
                            dropdownMenu.style.transform = 'translateY(0)';
                        }, 10);
                    }
                });
                
                // Hide when mouse leaves both button and menu
                dropdown.addEventListener('mouseleave', function(e) {
                    if (isDesktop()) {
                        // Check if mouse is going to dropdown menu
                        if (!dropdownMenu.contains(e.relatedTarget)) {
                            dropdownMenu.style.opacity = '0';
                            dropdownMenu.style.visibility = 'hidden';
                            dropdownMenu.style.transform = 'translateY(-10px)';
                            setTimeout(() => {
                                dropdownMenu.style.display = 'none';
                            }, 300);
                        }
                    }
                });
                
                // Also handle mouse leave from dropdown menu
                dropdownMenu.addEventListener('mouseleave', function(e) {
                    if (isDesktop() && !dropdown.contains(e.relatedTarget)) {
                        dropdownMenu.style.opacity = '0';
                        dropdownMenu.style.visibility = 'hidden';
                        dropdownMenu.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            dropdownMenu.style.display = 'none';
                        }, 300);
                    }
                });
            }
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (!isDesktop()) {
                    // On mobile, restore default Bootstrap behavior
                    dropdownToggle.removeEventListener('click', arguments.callee);
                }
            });
            
            // Add active state to clicked nav items
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>
