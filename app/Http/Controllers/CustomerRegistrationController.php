<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\StudentCustomer;
use App\Models\StaffCustomer;
use App\Models\Faculty;
use App\Models\College;
use App\Models\VerificationDocs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CustomerRegistrationController extends Controller
{
    // Show registration form
    public function create()
    {
        // Get faculties and departments for dropdowns
        // Fetch from database
        $faculties = Faculty::orderBy('facultyName')->get();
        $residentialColleges = College::orderBy('collegeName')->get();

        $bankTypes = [
            'Maybank',
            'CIMB',
            'Public Bank',
            'RHB',
            'Hong Leong Bank',
            'Ambank',
            'HSBC Malaysia',
            'OCBC Malaysia',
            'Bank Rakyat',
            'Bank Islam',
            'Affin Bank',
            'Alliance Bank',
            'BSN'
        ];
        

        return view('customers.register', compact('faculties','residentialColleges','bankTypes'));
    }

    // Handle registration submission
    public function store(Request $request)
    {
        // Trim all inputs
        $request->merge(array_map('trim', $request->all()));

        // Base validation rules for all users
        $validationRules = [
            // User information
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user',
            'password' => 'required|string|min:8|confirmed',
            'noHP' => 'required|string|max:20',
            'noIC' => 'required|string|max:20|unique:user',
            
            // Customer type
            'customerType' => 'required|in:student,staff',
            
            // Customer information
            'referralCode' => 'nullable|string|max:50',
            'accountNumber' => 'required|string|max:50',
            'bankType' => 'required|string|max:50|in:Maybank,CIMB,Public Bank,RHB,Hong Leong Bank,Ambank,HSBC Malaysia,OCBC Malaysia,Bank Rakyat,Bank Islam,Affin Bank,Alliance Bank,BSN',
            
        ];

        // Add conditional validation based on customer type
        if ($request->customerType === 'student') {
            $validationRules['matricNo'] = 'required|string|max:50|unique:studentcustomer,matricNo';
            $validationRules['facultyID'] = 'required|string|exists:faculty,facultyID';
            $validationRules['collegeID'] = 'required|string|exists:college,collegeID';
        } else {
            $validationRules['staffNo'] = 'required|string|max:50|unique:staffcustomer,staffNo';
        }

        

        // file uploads
        $validationRules['ic'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:5120';
        $validationRules['drivers_license'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:5120';
        $validationRules['matric_card'] = 'required_if:customerType,student|file|mimes:jpg,jpeg,png,pdf|max:5120';

        // Validate the request
        $validated = $request->validate($validationRules);

        // Start database transaction
        DB::beginTransaction();

        try {
            // 1. Create User
            $user = User::create([
                'password' => Hash::make($validated['password']),
                'name' => $validated['name'],
                'noHP' => $validated['noHP'],
                'email' => $validated['email'],
                'noIC' => $validated['noIC'],
                'userType' => 'customer',
            ]);


            //$user->refresh(); // reloads trigger-generated userID
            $user = User::where('email', $validated['email'])->first();
            \Log::info('Generated UserID: ' . $user->userID);

            // 2. Generate referral code if not provided
            // $referred_byCode = NULL;

            // 3. Create Customer
            $customer = Customer::create([
                'userID' => $user->userID,
                'referred_byCode' => $validated['referralCode'] ?? null,
                'accountNumber' => $validated['accountNumber'],
                'bankType' => $validated['bankType'],
                'customerType' => $validated['customerType'],
                'customerStatus' => 'active'
            ]);

            // 4. Create specific customer type record
            if ($validated['customerType'] === 'student') {

                StudentCustomer::create([
                    'userID' => $user->userID,
                    'matricNo' => $validated['matricNo'],
                    'facultyID' => $validated['facultyID'],
                    'collegeID' => $validated['collegeID'],
                    
                ]);
            } else {
                StaffCustomer::create([
                    'userID' => $user->userID,
                    'staffNo' => $validated['staffNo'],
                ]);
            }

            // store files and create VerificationDocs record
            $verificationData = [
                'customerID' => $user->userID,
                'status' => 'active',
            ];

            // Store IC Copy
            if ($request->hasFile('ic')) {
                $icPath = $request->file('ic')->store("verification/{$user->userID}/ic", 'public');
                $verificationData['ic_file_path'] = $icPath;
            }

            // Store Driver's License
            if ($request->hasFile('drivers_license')) {
                $licensePath = $request->file('drivers_license')->store("verification/{$user->userID}/license", 'public');
                $verificationData['license_file_path'] = $licensePath;
            }

            // Store Matric Card (for students)
            if ($request->customerType === 'student' && $request->hasFile('matric_card')) {
                $matricPath = $request->file('matric_card')->store("verification/{$user->userID}/matric", 'public');
                $verificationData['matric_file_path'] = $matricPath;
            }

            // create VerificationDocs record
            VerificationDocs::create($verificationData);

            // Commit transaction
            DB::commit();

            // Send verification email or notification
            // Mail::to($user->email)->send(new CustomerRegistered($user));

            // Log the user in automatically
            // auth()->login($user);

            // Store userID in session
            session(['registered_user_id' => $user->userID]);
            
            // Redirect to success page
            return redirect()->route('customer.register.success');

            // Return back with success message
            // return back()->with('success', 'Registration successful!');

        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();

            // Log the error
            \Log::error('Customer registration failed: ' . $e->getMessage());
            \Log::error('Error Trace: ' . $e->getTraceAsString());

            // Return with error message
            return back()
                ->withErrors(['error' => 'Registration failed. '. $e->getMessage()]) // ← Show actual error
                ->withInput()
                ->with('error_details', $e->getMessage());
        }
    }

    

    // Generate unique referral code
    /*private function generateReferralCode()
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (Customer::where('referralCode', $code)->exists());

        return $code;
    }*/

    public function authenticate()
{
    $this->ensureIsNotRateLimited();

    // Check if user exists with email
    $user = User::where('email', $this->email)->first();
    
    if (!$user || !Hash::check($this->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }
    
    // Manually log in the user
    Auth::login($user, $this->boolean('remember'));
}
    // Show success page (optional)
    public function success()
    {
        $userId = session('registered_user_id'); // from function store above

        if (!$userId) {
        // If no user ID, redirect to registration
            return redirect()->route('customer.register')
                ->with('error', 'Registration session expired. Please register again.');
        }

        // Load user with ALL related data
        $user = User::with([
            'customer',
            'studentCustomer.faculty',
            'studentCustomer.college', 
            'staffCustomer',
            'verificationDocs'
        ])->find($userId);
        
        if (!$user) {
            return redirect()->route('customer.register')
                ->with('error', 'User not found. Please register again.');
        }

        //$user = User::find($userId);
        
        // loads the success.blade.php with registered user data
        return view('customers.success', [
            'user' => $user
        ]);

        
        
    }
}