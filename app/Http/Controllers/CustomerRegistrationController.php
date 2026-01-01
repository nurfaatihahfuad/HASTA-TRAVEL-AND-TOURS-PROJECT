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
        /*$faculties = [
            'Faculty of Engineering',
            'Faculty of Science',
            'Faculty of Medicine',
            'Faculty of Business',
            'Faculty of Arts',
            'Faculty of Law',
            'Faculty of Education',
            'Faculty of Computing',
            'Faculty of Architecture',
            'Faculty of Pharmacy'
        ];

        $residentialColleges = [
            'KTDI',
            'KTHO',
            'KTF',
            'KTR'
        ];*/

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
            'referralCode' => 'nullable|string|max:50|unique:customer,referralCode',
            'accountNumber' => 'required|string|max:50',
            'bankType' => 'required|string|max:50|in:Maybank,CIMB,Public Bank,RHB,Hong Leong Bank,Ambank,HSBC Malaysia,OCBC Malaysia,Bank Rakyat,Bank Islam,Affin Bank,Alliance Bank,BSN',
            
        ];

        // Add conditional validation based on customer type
        if ($request->customerType === 'student') {
            $validationRules['matricNo'] = 'required|string|max:50|unique:studentcustomer,matricNo';
            $validationRules['facultyID'] = 'required|exists:faculty,facultyID';
            $validationRules['collegeID'] = 'required|exists:college,collegeID';
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

            // 2. Generate referral code if not provided
            $referralCode = $validated['referralCode'] ?? $this->generateReferralCode();

            // 3. Create Customer
            $customer = Customer::create([
                'userID' => $user->userID,
                'referralCode' => $referralCode,
                'accountNumber' => $validated['accountNumber'],
                'bankType' => $validated['bankType'],
                'customerType' => $validated['customerType'],
            ]);

            // 4. Create specific customer type record
            if ($validated['customerType'] === 'student') {
                $facultyID = Faculty::find($validated['facultyID']);
                $collegeID = College::find($validated['collegeID']);

                StudentCustomer::create([
                    'userID' => $user->userID,
                    'matricNo' => $validated['matricNo'],
                    'facultyID' => $facultyID->facultyID,
                    'collegeID' => $collegeID->collegeID,
                    'customerStatus' => 'pending', // pending verification
                ]);
            } else {
                StaffCustomer::create([
                    'userID' => $user->userID,
                    'staffNo' => $validated['staffNo'],
                    'customerStatus' => 'pending', // pending verification
                ]);
            }

            // store files and create VerificationDocs record
            $verificationData = [
                'userID' => $user->userID,
                'status' => 'pending',
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

            // Redirect to success page
            /*return redirect()->route('customer.dashboard')
                ->with('success', 'Registration successful! Welcome, ' . $user->name);*/
            return redirect()->route('welcome')->with('success', 'Login successful!');

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
    private function generateReferralCode()
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (Customer::where('referralCode', $code)->exists());

        return $code;
    }

    // Show success page (optional)
    public function success()
    {
        return view('customers.success');
    }
}