<!DOCTYPE html>
<html>
<head>
    <title>Test Layout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* FORCE EVERYTHING VISIBLE */
        * {
            border: 1px solid red !important;
            background-color: rgba(255,0,0,0.1) !important;
            color: black !important;
            opacity: 1 !important;
            visibility: visible !important;
            display: block !important;
        }
        
        body {
            background: yellow !important;
            padding-top: 100px !important;
        }
        
        nav {
            background: blue !important;
            color: white !important;
            height: 80px !important;
        }
    </style>
</head>
<body>
    
    <!-- DIRECT NAVBAR HTML -->
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">DIRECT NAVBAR</a>
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="#" style="color: black !important;">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#" style="color: black !important;">Book Car</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
        <h1>Testing Layout</h1>
        <p>Everything should have red borders and light red backgrounds.</p>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Force all elements visible
        document.querySelectorAll('*').forEach(el => {
            el.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important;';
        });
    </script>
</body>
</html>