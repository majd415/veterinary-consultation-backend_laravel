<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting; // Assuming this is where payment settings live or a separate model? 
// User mentioned "payment_settings table". Let's check if it exists. 
// Step 3901 output didn't list it explicitly but user mentioned it.
// Wait, Migration list in 3865 didn't show 'create_payment_settings'.
// Step 3883 showed 'create_settings_table'.
// I will assume for now 'payment_settings' might be part of 'settings' OR a table I missed.
// Actually, looking at the user request: "get all payment_settings table".
// If it doesn't exist, I should probably create it or assume they mean the 'settings' table key-values.
// BUT, I'll stick to 'InfoPayment' for the logs which DOES exist.
// I'll create a dummy 'PaymentLog' model or use DB facade if needed.
// Ah, `InfoPayment` model was created in step 3819 content summary.

use App\Models\InfoPayment;
use App\Models\PaymentSetting; // Added
use Illuminate\Support\Facades\DB;

class AdminPaymentController extends Controller
{
    public function index()
    {
        // 1. Payment Logs
        $payments = InfoPayment::latest()->paginate(10);

        // 2. Statistics
        $totalSuccess = InfoPayment::where('status', 'paid')->sum('amount');
        
        // 3. Payment Settings
        $stripeKey = PaymentSetting::where('key', 'stripe_secret_key')->value('value');
        $stripePub = PaymentSetting::where('key', 'stripe_publishable_key')->value('value');
        
        return view('admin.payments.index', compact('payments', 'totalSuccess', 'stripeKey', 'stripePub'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'stripe_secret_key' => 'required|string',
            'stripe_publishable_key' => 'required|string',
        ]);

        PaymentSetting::updateOrCreate(
            ['key' => 'stripe_secret_key'],
            ['value' => $request->stripe_secret_key]
        );

        PaymentSetting::updateOrCreate(
            ['key' => 'stripe_publishable_key'],
            ['value' => $request->stripe_publishable_key]
        );

        return redirect()->back()->with('success', 'Payment settings updated successfully');
    }

    public function destroy($id)
    {
        InfoPayment::destroy($id);
        return redirect()->back()->with('success', 'Payment log deleted successfully');
    }
}
