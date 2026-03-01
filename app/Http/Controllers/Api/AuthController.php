<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class AuthController extends Controller
{
    public function sendCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if email already exists
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['message' => 'This email is already registered.'], 422);
        }

        $code = rand(1000, 9999);
        
        // Save code to user if they somehow exist without completing registration (rare case)
        // or just let the frontend handle the 'verify' step before hitting register.
        // For security, we should ideally store this code in a cache associated with the email
        // or a temporary table. 
        // For this specific flow requested:
        
        // We will send the code via Email
        try {
            Mail::to($request->email)->send(new VerificationCodeMail($code, 'verification'));
        } catch (\Exception $e) {
            Log::error("Mail send failed: " . $e->getMessage());
            return response()->json(['message' => 'Failed to send email. Please try again.'], 500);
        }

        Log::info("Verification code for {$request->email}: {$code}"); // Keep log for debugging

        return response()->json([
            'message' => 'Verification code sent successfully.',
            'code' => $code // Optional: Remove this in production if you want strict security
        ]);
    }
// ... (verifyCode, register, login, methods remain similar) ...

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $code = rand(1000, 9999);
        
        $user = User::where('email', $request->email)->first();
        if ($user) {
             // In a real app, storing in DB column 'verification_code' is okay for simple MVP.
             // Ideally use PasswordResetTokens table.
             $user->verification_code = $code;
             $user->save();
        }

        try {
            Mail::to($request->email)->send(new VerificationCodeMail($code, 'reset'));
        } catch (\Exception $e) {
            Log::error("Mail send failed: " . $e->getMessage());
            return response()->json(['message' => 'Failed to send email.'], 500);
        }

        Log::info("Reset code for {$request->email}: {$code}");

        return response()->json([
            'message' => 'Reset code sent successfully.',
            'code' => $code // Remove in production
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully.']);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'phone', 'avatar']);
        
        if ($request->has('bio')) {
            $data['bio'] = ['en' => $request->bio];
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $user->fresh()
        ]);
    }

    public function uploadProfilePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            $path = public_path('uploads/avatars');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            
            $file->move($path, $filename);
            
            // Store only relative path (NO absolute URL)
            $relativePath = 'uploads/avatars/' . $filename;
            
            return response()->json([
                'message' => 'Photo uploaded successfully.',
                'url' => asset($relativePath), // Return full URL for immediate use
                'path' => $relativePath // Relative path to store in database
            ]);
        }

        return response()->json(['message' => 'No file uploaded.'], 400);
    }
    public function updateFcmToken(Request $request)
{
    $validator = Validator::make($request->all(), [
        'fcm_token' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $user = $request->user(); // المستخدم الحالي عبر sanctum/token
    $user->fcm_token = $request->fcm_token;
    $user->save();

    return response()->json([
        'message' => 'FCM token updated successfully',
        'fcm_token' => $user->fcm_token
    ]);
}

    public function getVets()
    {
        $vets = User::where('role', 'vet')->paginate(10);
        return response()->json($vets);
    }
}
