
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Page</title>
        <link rel="stylesheet" href="{{ asset('css/login.css') }}">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>

    <body>
    <div class="login-wrapper">
        <div class="login-box"><a class="navbar-brand d-flex align-items-center" href="/">
      <img src="{{ asset('img/hasta.jpeg') }}" alt="Hasta Logo" style="height:60px;">
    </a>
    <a href="/" class="top-home-btn">
    <i class="fas fa-home"></i>
    </a>

            <h1>Welcome Back!</h1>
            <p class="subtitle">Log in to your account</p>

            <form method="POST" action="{{ route('login') }}" class="form-container">
                @csrf

                <!-- Email -->
                <div class="input-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="Enter your e-mail"
                        value="" required autofocus autocomplete="username">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password"
                           required autocomplete="current-password">
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Options -->
                <div class="options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
                    @endif
                </div>

                <!-- Submit -->
                <button type="submit">LOGIN</button>
            </form>

            <!-- Sign Up Link (Optional) -->
                    <div class="text-center pt-4">
                        <p class="text-gray-600">
                            New Customer?
                            <a href="{{ route('customer.register') }}" class="reg-text-primary font-semibold hover:underline ml-1">
                                Register here
                            </a>
                        </p>
                    </div>
        </div>


    </div>
    </body>