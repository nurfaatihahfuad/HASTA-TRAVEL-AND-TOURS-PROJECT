<?php
// VerificationController.php - For Blade views
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\User;
    use App\Models\Customer;
    use App\Models\VerificationDocs;
    use App\Models\Staff;
    use Illuminate\Support\Facades\Auth;

    class AccountVerificationController extends Controller
    {
        // Show pending verifications page
        public function index()
        {
            // Get pending verifications for the logged-in salesperson
            $pendingUsers = User::whereHas('customer', function($query) {
                    $query->where('customerStatus', 'pending');
                })
                ->whereHas('verificationDocs', function($query) {
                    $query->where('status', 'pending');
                })
                ->with(['customer', 'verificationDocs'])
                ->get();

            return view('sales.verification.index', [
                'pendingUsers' => $pendingUsers,
                'pendingCount' => $pendingUsers->count()
            ]);
        }

        /**
        * Show single customer verification review page
        */
        public function show($userID)
        {
            $user = User::with(['customer', 'verificationDocs', 'customer.specificCustomer'])
                    ->where('userID', $userID)
                    ->firstOrFail();

            if (!$user->customer) {
                return redirect()->route('sales.verification.index')
                    ->with('error', 'User is not registered as a customer');
            }

            if (!$user->verificationDocs) {
                return redirect()->route('sales.verification.index')
                    ->with('error', 'No verification documents uploaded yet');
            }

            return view('sales.verification.show', [
                'user' => $user,
                'customer' => $user->customer,
                'verificationDocs' => $user->verificationDocs
            ]);
        }

        /**
        * Approve verification (form submission)
        */
        public function approve(Request $request, $userID)
        {
            $request->validate([
                'notes' => 'nullable|string|max:500',
            ]);

            $staffId = Auth::id();
            
            // Verify staff exists
            $staff = Staff::where('staffID', $staffId)->first();
            if (!$staff) {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }

            \DB::beginTransaction();
            try {
                $user = User::with(['customer', 'verificationDocs'])
                        ->where('userID', $userID)
                        ->firstOrFail();

                $customer = $user->customer;
                $verificationDocs = $user->verificationDocs;
                
                if (!$customer || !$verificationDocs) {
                    throw new \Exception('Customer or verification documents not found.');
                }

                // Update verification docs
                $verificationDocs->update([
                    'status' => 'verified',
                    'verified_by' => $staffId,
                    'verified_at' => now(),
                    'notes' => $request->notes
                ]);

                // Update customer status
                $customer->update([
                    'customerStatus' => 'active',
                ]);

                \DB::commit();

                return redirect()->route('sales.verification.index')
                    ->with('success', 'Customer verification approved successfully!');

            } catch (\Exception $e) {
                \DB::rollBack();
                return redirect()->back()->with('error', 'Failed: ' . $e->getMessage());
            }
        }

        /**
        * Reject verification (form submission)
        */
        public function reject(Request $request, $userID)
        {
            $request->validate([
                'reason' => 'required|string|min:10|max:500',
            ]);

            $staffId = Auth::id();
            $staff = Staff::where('staffID', $staffId)->first();
            
            if (!$staff) {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }

            \DB::beginTransaction();
            try {
                $user = User::with(['customer', 'verificationDocs'])
                        ->where('userID', $userID)
                        ->firstOrFail();

                $customer = $user->customer;
                $verificationDocs = $user->verificationDocs;
                
                if (!$customer) {
                    throw new \Exception('Customer not found.');
                }

                if ($verificationDocs) {
                    $verificationDocs->update([
                        'status' => 'rejected',
                        'verified_by' => $staffId,
                        'verified_at' => now(),
                        'notes' => $request->reason
                    ]);
                }

                $customer->update([
                    'customerStatus' => 'rejected',
                ]);

                \DB::commit();

                return redirect()->route('sales.verification.index')
                    ->with('success', 'Customer verification rejected.');

            } catch (\Exception $e) {
                \DB::rollBack();
                return redirect()->back()->with('error', 'Failed: ' . $e->getMessage());
            }
        }
    }
?>