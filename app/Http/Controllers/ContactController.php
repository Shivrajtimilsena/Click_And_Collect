<?php

namespace App\Http\Controllers;

use App\Mail\ContactEnquiryMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact');
    }

    public function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'customer_type' => ['required', 'in:customer,trader,partner,press,other'],
            'subject' => ['required', 'string', 'max:120'],
            'order_reference' => ['nullable', 'string', 'max:50'],
            'preferred_contact' => ['required', 'in:email,phone'],
            'message' => ['required', 'string', 'min:20', 'max:3000'],
        ]);

        try {
            Mail::to('support@cleckhuddersfax-local.co.uk')->send(new ContactEnquiryMail($validated));
        } catch (\Throwable $exception) {
            Log::error('Contact enquiry delivery failed.', [
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'error' => $exception->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'contact' => 'We could not send your message right now. Please try again shortly or call support if it is urgent.',
                ]);
        }

        return back()->with('success', 'Thanks for reaching out. Our team has received your message and will respond soon.');
    }
}
