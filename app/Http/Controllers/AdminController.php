<?php

namespace App\Http\Controllers;

use App\Mail\TraderApprovedMail;
use App\Mail\TraderRejectedMail;
use App\Models\Shop;
use App\Models\Trader;
use App\Models\TraderApplication;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $pendingCount = TraderApplication::where('status', 'PENDING')->count();
        $approvedCount = TraderApplication::where('status', 'APPROVED')->count();
        $rejectedCount = TraderApplication::where('status', 'REJECTED')->count();

        return view('admin.dashboard', compact('pendingCount', 'approvedCount', 'rejectedCount'));
    }

    public function applications(): View
    {
        $applications = TraderApplication::latest()->get();

        return view('admin.applications', compact('applications'));
    }

    public function showApplication(TraderApplication $application): View
    {
        return view('admin.application-show', compact('application'));
    }

    public function approve(Request $request, TraderApplication $application): RedirectResponse
    {
        if ($application->status !== 'PENDING') {
            return back()->with('error', 'This application has already been reviewed.');
        }

        $plainPassword = $application->password ?: Str::random(12);

        $user = User::create([
            'full_name' => $application->shop_name,
            'email' => $application->email,
            'password' => $plainPassword,
            'role' => 'TRADER',
            'status' => 'ACTIVE',
            'address' => $application->location,
        ]);

        $trader = Trader::create([
            'user_id' => $user->user_id,
            'shop_type' => 'TRADER',
            'is_active' => true,
        ]);

        Shop::create([
            'trader_id' => $trader->trader_id,
            'shop_name' => $application->shop_name,
            'description' => $application->description,
            'is_active' => 'Y',
            'register_date' => now(),
        ]);

        $application->update([
            'status' => 'APPROVED',
            'reviewed_by' => $request->user()->user_id,
            'reviewed_at' => now(),
        ]);

        try {
            Mail::to($user->email)->send(new TraderApprovedMail($user, $plainPassword));
        } catch (\Exception $e) {
            Log::error('Failed to send trader approval email: '.$e->getMessage());
        }

        return redirect()->route('admin.applications')->with('success', 'Application approved. Trader account created and email sent.');
    }

    public function reject(Request $request, TraderApplication $application): RedirectResponse
    {
        Log::debug('Reject method called', [
            'app_id' => $application->application_id,
            'shop_name' => $application->shop_name,
            'email' => $application->email,
        ]);

        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        if ($application->status !== 'PENDING') {
            return back()->with('error', 'This application has already been reviewed.');
        }

        $application->update([
            'status' => 'REJECTED',
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => $request->user()->user_id,
            'reviewed_at' => now(),
        ]);

        try {
            Log::debug('Attempting rejection email', [
                'email' => $application->email,
                'app_id' => $application->application_id,
                'shop' => $application->shop_name,
            ]);
            Mail::to($application->email)->send(new TraderRejectedMail($application));
            Log::info('Rejection email sent successfully', ['email' => $application->email, 'app_id' => $application->application_id]);
        } catch (\Exception $e) {
            Log::error('Failed to send rejection email', [
                'email' => $application->email,
                'app_id' => $application->application_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return redirect()->route('admin.applications')->with('success', 'Application rejected. A notification email has been sent to the applicant.');
    }

    public function approveFromApex(Request $request, TraderApplication $application): \Illuminate\Http\JsonResponse
    {
        $apiKey = $request->header('X-API-Key') ?: $request->input('api_key');
        if (! $apiKey || $apiKey !== config('app.apex_api_key')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($application->status !== 'PENDING') {
            return response()->json(['error' => 'Application already reviewed'], 409);
        }

        $plainPassword = $application->password ?: Str::random(12);

        $user = User::create([
            'full_name' => $application->shop_name,
            'email' => $application->email,
            'password' => $plainPassword,
            'role' => 'TRADER',
            'status' => 'ACTIVE',
            'address' => $application->location,
        ]);

        $trader = Trader::create([
            'user_id' => $user->user_id,
            'shop_type' => 'TRADER',
            'is_active' => true,
        ]);

        Shop::create([
            'trader_id' => $trader->trader_id,
            'shop_name' => $application->shop_name,
            'description' => $application->description,
            'is_active' => 'Y',
            'register_date' => now(),
        ]);

        $application->update([
            'status' => 'APPROVED',
            'reviewed_by' => null,
            'reviewed_at' => now(),
        ]);

        try {
            Mail::to($user->email)->send(new TraderApprovedMail($user, $plainPassword));
        } catch (\Exception $e) {
            Log::error('Failed to send trader approval email: '.$e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Trader approved and email sent',
            'user_id' => $user->user_id,
            'email' => $user->email,
        ]);
    }

    public function rejectFromApex(Request $request, TraderApplication $application): JsonResponse
    {
        $apiKey = $request->header('X-API-Key') ?: $request->input('api_key');
        if (! $apiKey || $apiKey !== config('app.apex_api_key')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($application->status !== 'REJECTED') {
            return response()->json(['error' => 'Application is not rejected'], 409);
        }

        try {
            Mail::to($application->email)->send(new TraderRejectedMail($application));
            Log::info('Rejection email sent from APEX', ['app_id' => $application->application_id, 'email' => $application->email]);
            return response()->json(['success' => true, 'message' => 'Rejection email sent']);
        } catch (\Exception $e) {
            Log::error('Failed to send rejection email from APEX', [
                'app_id' => $application->application_id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Failed to send email: '.$e->getMessage()], 500);
        }
    }
}
