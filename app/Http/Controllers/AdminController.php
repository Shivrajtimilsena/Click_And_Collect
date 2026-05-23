<?php

namespace App\Http\Controllers;

use App\Mail\TraderApprovedMail;
use App\Mail\TraderRejectedMail;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Trader;
use App\Models\TraderApplication;
use App\Models\TraderWithdrawal;
use App\Models\User;
use App\Services\PayPalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $pendingCount = TraderApplication::where('status', 'PENDING')->count();
        $approvedCount = TraderApplication::where('status', 'APPROVED')->count();
        $rejectedCount = TraderApplication::where('status', 'REJECTED')->count();
        $pendingWithdrawals = TraderWithdrawal::where('status', 'PENDING')->count();

        $pendingProducts = DB::table('product as p')
            ->join('shop as s', 'p.shop_id', '=', 's.shop_id')
            ->whereRaw("nvl(p.approval_status, 'APPROVED') = 'PENDING'")
            ->select([
                'p.product_id',
                'p.product_name',
                'p.description',
                'p.price',
                'p.stock',
                's.shop_name',
            ])
            ->orderByDesc('p.product_id')
            ->get();

        return view('admin.dashboard', compact(
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'pendingWithdrawals',
            'pendingProducts'
        ));
    }

    public function products(): View
    {
        $products = Product::with('shop')
            ->orderBy('approval_status')
            ->orderByDesc('product_id')
            ->paginate(30);

        return view('admin.products', compact('products'));
    }

    public function approveProduct(Product $product): RedirectResponse
    {
        $product->update(['approval_status' => 'APPROVED']);

        return back()->with('success', "Product #PRD-{$product->product_id} approved.");
    }

    public function rejectProduct(Product $product): RedirectResponse
    {
        $product->update(['approval_status' => 'REJECTED']);

        return back()->with('success', "Product #PRD-{$product->product_id} rejected.");
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

        $password = $application->password;

        $user = User::create([
            'full_name' => $application->shop_name,
            'email' => $application->email,
            'password' => $password,
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
            Mail::to($user->email)->send(new TraderApprovedMail($user, $password));
        } catch (\Exception $e) {
            Log::error('Failed to send trader approval email', [
                'email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('admin.applications')->with('warning', 'Application approved and account created, but the approval email could not be sent. Check your mail configuration.');
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

        $password = $application->password;

        $user = User::create([
            'full_name' => $application->shop_name,
            'email' => $application->email,
            'password' => $password,
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
            Mail::to($user->email)->send(new TraderApprovedMail($user, $password));
        } catch (\Exception $e) {
            Log::error('Failed to send trader approval email from APEX', [
                'email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Trader approved and email sent',
            'user_id' => $user->user_id,
            'email' => $user->email,
        ]);
    }

    public function withdrawals(): View
    {
        $withdrawals = TraderWithdrawal::with('trader.user')
            ->latest()
            ->paginate(30);

        return view('admin.withdrawals', compact('withdrawals'));
    }

    public function approveWithdrawal(Request $request, TraderWithdrawal $withdrawal): RedirectResponse
    {
        if ($withdrawal->status !== 'PENDING') {
            return back()->with('error', 'This withdrawal has already been processed.');
        }

        $paypalService = app(PayPalService::class);

        try {
            $result = $paypalService->createPayout(
                $withdrawal->amount,
                config('paypal.currency', 'GBP'),
                $withdrawal->paypal_email,
                "Withdrawal from Click&Collect - Ref #{$withdrawal->withdrawal_id}"
            );

            $batchId = $result['payout_batch_id'] ?? null;
            $batchStatus = $result['batch_status'] ?? 'UNKNOWN';

            // Query batch status to get item-level status (not in create response)
            try {
                $batchDetail = $paypalService->getPayoutStatus($batchId);
                $itemStatus = $batchDetail['item_status'] ?? 'UNKNOWN';
            } catch (\Exception $e) {
                $itemStatus = $batchStatus; // fallback to batch status
            }

            $localStatus = match ($itemStatus) {
                'SUCCESS' => 'COMPLETED',
                'UNCLAIMED' => 'APPROVED',
                'PENDING', 'PROCESSING' => 'APPROVED',
                'DENIED', 'RETURNED' => 'FAILED',
                default => 'APPROVED',
            };

            $paypalBatchStatus = "{$itemStatus} (batch: {$batchStatus})";

            $withdrawal->update([
                'status' => $localStatus,
                'paypal_batch_id' => $batchId,
                'paypal_batch_status' => $paypalBatchStatus,
                'processed_by' => $request->user()->user_id,
                'processed_at' => now(),
            ]);

            if ($localStatus === 'COMPLETED') {
                return redirect()->route('admin.withdrawals.index')
                    ->with('success', "Withdrawal #{$withdrawal->withdrawal_id} completed — £" . number_format($withdrawal->amount, 2) . " sent to {$withdrawal->paypal_email}.");
            }

            if ($itemStatus === 'UNCLAIMED') {
                return redirect()->route('admin.withdrawals.index')
                    ->with('warning', "Withdrawal #{$withdrawal->withdrawal_id} approved but PayPal reports UNCLAIMED. The recipient needs to claim the payment in their PayPal account. The funds will be returned if not claimed within 30 days.");
            }

            return redirect()->route('admin.withdrawals.index')
                ->with('info', "Withdrawal #{$withdrawal->withdrawal_id} approved. PayPal status: {$itemStatus}. Funds will arrive once PayPal processes the batch.");
        } catch (\Exception $e) {
            Log::error('Withdrawal payout failed', [
                'withdrawal_id' => $withdrawal->withdrawal_id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'PayPal payout failed: '.$e->getMessage());
        }
    }

    public function checkWithdrawalStatus(Request $request, TraderWithdrawal $withdrawal): RedirectResponse
    {
        if (! $withdrawal->paypal_batch_id) {
            return back()->with('error', 'No PayPal batch ID found for this withdrawal.');
        }

        $paypalService = app(PayPalService::class);

        try {
            $status = $paypalService->getPayoutStatus($withdrawal->paypal_batch_id);

            $itemStatus = $status['item_status'] ?? 'UNKNOWN';
            $batchStatus = $status['batch_status'] ?? 'UNKNOWN';
            $errors = $status['errors'] ?? null;

            $localStatus = match ($itemStatus) {
                'SUCCESS' => 'COMPLETED',
                'UNCLAIMED' => 'APPROVED',
                'PENDING', 'PROCESSING' => 'APPROVED',
                'DENIED', 'RETURNED' => 'FAILED',
                default => $withdrawal->status,
            };

            $paypalBatchStatus = "{$itemStatus} (batch: {$batchStatus})";

            $withdrawal->update([
                'status' => $localStatus,
                'paypal_batch_status' => $paypalBatchStatus,
            ]);

            $message = "Withdrawal #{$withdrawal->withdrawal_id} status refreshed: item={$itemStatus}, batch={$batchStatus}.";

            if ($localStatus === 'COMPLETED') {
                return redirect()->route('admin.withdrawals.index')
                    ->with('success', $message . ' Payment completed.');
            }

            if ($itemStatus === 'UNCLAIMED') {
                return redirect()->route('admin.withdrawals.index')
                    ->with('warning', $message . ' The recipient still needs to claim this payment in their PayPal account.');
            }

            if ($localStatus === 'FAILED') {
                $reason = $errors['message'] ?? 'Unknown reason';
                return redirect()->route('admin.withdrawals.index')
                    ->with('error', $message . " Payment failed: {$reason}");
            }

            return redirect()->route('admin.withdrawals.index')
                ->with('info', $message);
        } catch (\Exception $e) {
            Log::error('PayPal status check failed', [
                'withdrawal_id' => $withdrawal->withdrawal_id,
                'batch_id' => $withdrawal->paypal_batch_id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to check PayPal status: ' . $e->getMessage());
        }
    }

    public function rejectWithdrawal(Request $request, TraderWithdrawal $withdrawal): RedirectResponse
    {
        if ($withdrawal->status !== 'PENDING') {
            return back()->with('error', 'This withdrawal has already been processed.');
        }

        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $withdrawal->update([
            'status' => 'REJECTED',
            'admin_notes' => $request->admin_notes,
            'processed_by' => $request->user()->user_id,
            'processed_at' => now(),
        ]);

        return redirect()->route('admin.withdrawals.index')
            ->with('success', "Withdrawal #{$withdrawal->withdrawal_id} rejected.");
    }

    public function resendApprovalEmail(TraderApplication $application): RedirectResponse
    {
        if ($application->status !== 'APPROVED') {
            return back()->with('error', 'Only approved applications can receive approval emails.');
        }

        $user = User::where('email', $application->email)->first();

        if (! $user) {
            return back()->with('error', 'No user account found for this application. The user may not have been created yet.');
        }

        try {
            Mail::to($user->email)->send(new TraderApprovedMail($user, $application->password));
            Log::info('Approval email resent', [
                'email' => $user->email,
                'application_id' => $application->application_id,
            ]);
            return back()->with('success', "Approval email resent to {$user->email}.");
        } catch (\Exception $e) {
            Log::error('Failed to resend approval email', [
                'email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Failed to send email: '.$e->getMessage());
        }
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
