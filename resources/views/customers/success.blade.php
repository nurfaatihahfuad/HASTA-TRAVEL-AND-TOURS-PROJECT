<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .success-icon {
            background: #4CAF50;
            color: white;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
        }
        .info-group {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="success-icon">✓</div>
        <h1 style="text-align: center; color: #333; margin-bottom: 30px;">
            Welcome to ASYURA, {{ $user->name }}!
        </h1>
        
        <!-- Customer Type Badge -->
        <div style="text-align: center; margin-bottom: 30px;">
            <span style="background: #EC9A85; color: white; padding: 8px 20px; border-radius: 20px; font-weight: bold;">
                {{ strtoupper($user->customer->customerType ?? 'Customer') }}
            </span>
        </div>
        
        <!-- User Details -->
        <div class="info-group">
            <strong>Email:</strong> {{ $user->email }}<br>
            <strong>Phone:</strong> {{ $user->noHP }}<br>
            <strong>Account Status:</strong> 
            <span style="color: #FF9800; font-weight: bold;">
                {{ $user->studentCustomer->customerStatus ?? $user->staffCustomer->customerStatus ?? 'Pending' }}
            </span>
        </div>
        
        <!-- Customer Specific Info -->
        @if($user->customer->customerType === 'student' && $user->studentCustomer)
        <div class="info-group">
            <strong>Matric No:</strong> {{ $user->studentCustomer->matricNo }}<br>
            <strong>Faculty:</strong> {{ $user->studentCustomer->faculty->facultyName ?? 'N/A' }}<br>
            <strong>College:</strong> {{ $user->studentCustomer->college->collegeName ?? 'N/A' }}
        </div>
        @elseif($user->customer->customerType === 'staff' && $user->staffCustomer)
        <div class="info-group">
            <strong>Staff No:</strong> {{ $user->staffCustomer->staffNo }}
        </div>
        @endif
        
        <!-- Banking Info -->
        @if($user->customer)
        <div class="info-group">
            <strong>Bank:</strong> {{ $user->customer->bankType }}<br>
            <strong>Account No:</strong> {{ $user->customer->accountNumber }}<br>
            <strong>Referral Code:</strong> {{ $user->customer->referralCode }}
        </div>
        @endif
        
        <!-- Next Steps -->
        <div style="background: #e3f2fd; border-radius: 10px; padding: 20px; margin-top: 30px;">
            <h3 style="color: #1976d2; margin-top: 0;">Next Steps:</h3>
            <p>1. Check your email for verification link</p>
            <p>2. Complete your profile setup</p>
            <p>3. Start using ASYURA services</p>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('login') }}" 
               style="background: #667eea; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; display: inline-block;">
                Go to Login
            </a>
        </div>
    </div>
</body>
</html>