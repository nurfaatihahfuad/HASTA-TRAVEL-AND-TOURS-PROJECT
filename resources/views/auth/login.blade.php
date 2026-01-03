<x-guest-layout>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Page</title>
        <link rel="stylesheet" href="{{ asset('css/login.css') }}">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    </head>

    <body>
    <div class="login-wrapper">
        <div class="login-box">
            <div class="login-icon">👤</div>

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

            <!-- Sign up link (only if route exists) -->
            @if (Route::has('register'))
                <p class="signup-text">
                    Don't have an account?
                    <a href="{{ route('register') }}">Sign up</a>
                </p>
            @endif
        </div>

        <!-- Terms -->
        <p class="terms-text">
            By logging in, it's deemed that you have read and agreed to
            <a href="#">Hasta Terms of Use</a> and <a href="#">Privacy&nbsp;Policy</a>.
        </p>

        <!-- Help -->
        <div class="help-container">
            <a href="#" class="help-link">
                <span>Get help</span>
                <span class="help-icon">?</span>
            </a>
        </div>
    </div>
    </body>
</x-guest-layout>