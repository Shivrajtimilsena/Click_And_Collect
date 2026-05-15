<?php

namespace App\Http\Controllers;

use App\Mail\TraderApprovedMail;
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

        $plainPassword = Str::random(12);

        Log::info('Trader approval - generated random password', [
            'application_id' => $application->application_id,
            'email' => $application->email,
        ]);

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

        Log::info('User created - checking password in DB', [
            'user_id' => $user->user_id,
            'password_in_db' => User::find($user->user_id)->password,
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

        return redirect()->route('admin.applications')->with('success', 'Application rejected.');
    }

    // API endpoint for APEX to call after approving a trader application
    public function approveFromApex(Request $request, TraderApplication $application): JsonResponse
    {
        $apiKey = $request->header('X-API-Key') ?: $request->input('api_key');
        if (! $apiKey || $apiKey !== config('app.apex_api_key')) {
            Log::warning('APEX API: invalid API key attempt', [
                'application_id' => $application->application_id,
            ]);

            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 401);
        }
        $plainPassword = Str::random(12);

        Log::info('APEX trader approval - processing', [
            'application_id' => $application->application_id,
            'email' => $application->email,
        ]);

        $user = User::where('email', $application->email)->first();

        if ($user) {
            $user->update([
                'password' => $plainPassword,
                'status' => 'ACTIVE',
                'role' => 'TRADER',
                'full_name' => $application->shop_name,
            ]);
            Log::info('Existing user updated with new password', ['user_id' => $user->user_id]);
        } else {
            $user = User::create([
                'full_name' => $application->shop_name,
                'email' => $application->email,
                'password' => $plainPassword,
                'role' => 'TRADER',
                'status' => 'ACTIVE',
                'address' => $application->location,
            ]);

            Trader::create([
                'user_id' => $user->user_id,
                'shop_type' => 'TRADER',
                'is_active' => true,
            ]);

            Shop::create([
                'trader_id' => $user->user_id,
                'shop_name' => $application->shop_name,
                'description' => $application->description,
                'is_active' => 'Y',
                'register_date' => now(),
            ]);
        }

        $application->update([
            'status' => 'APPROVED',
            'reviewed_at' => now(),
        ]);

        try {
            Mail::to($user->email)->send(new TraderApprovedMail($user, $plainPassword));
            Log::info('Trader approval email sent', ['email' => $user->email]);
        } catch (\Exception $e) {
            Log::error('Failed to send trader approval email: '.$e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Trader approved successfully. Email sent.',
            'user_id' => $user->user_id,
            'email' => $user->email,
        ]);
    }
}
