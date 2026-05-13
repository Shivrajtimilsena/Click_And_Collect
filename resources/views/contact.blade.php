@extends('app')

@section('title', 'Contact | Click&Collect')

@section('content')
    <section class="relative overflow-hidden bg-white">
        <div class="absolute inset-x-0 top-0 h-72 bg-gradient-to-br from-primary/10 via-tertiary-fixed/10 to-surface-container-low"></div>
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
            <div class="grid grid-cols-1 xl:grid-cols-[1.05fr_0.95fr] gap-8 lg:gap-12 items-start">
                <div class="space-y-8">
                    <div class="max-w-2xl">
                        <span class="inline-block text-primary font-bold uppercase tracking-[0.12em] text-xs mb-4">Contact</span>
                        <h1 class="text-4xl md:text-5xl font-black text-zinc-900 leading-tight mb-5">
                            Industry-ready support for customers, traders, and partners.
                        </h1>
                        <p class="text-zinc-600 text-lg leading-8">
                            Reach the Click&Collect team for order help, trader onboarding, partnership conversations, and collection-day issues. This page is designed to get each enquiry to the right team quickly.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="border border-surface-container-high bg-white p-5 shadow-sm">
                            <p class="text-xs font-bold uppercase tracking-[0.12em] text-zinc-500 mb-3">Email Support</p>
                            <a href="mailto:support@cleckhuddersfax-local.co.uk" class="text-base font-bold text-zinc-900 hover:text-primary transition-colors break-all">
                                support@cleckhuddersfax-local.co.uk
                            </a>
                            <p class="text-sm text-zinc-600 mt-3 leading-6">Best for detailed requests, application follow-up, and account support.</p>
                        </div>

                        <div class="border border-surface-container-high bg-white p-5 shadow-sm">
                            <p class="text-xs font-bold uppercase tracking-[0.12em] text-zinc-500 mb-3">Phone</p>
                            <a href="tel:+441484123456" class="text-base font-bold text-zinc-900 hover:text-primary transition-colors">
                                +44 (0) 1484 123456
                            </a>
                            <p class="text-sm text-zinc-600 mt-3 leading-6">Use this for time-sensitive collection day issues or urgent support.</p>
                        </div>

                        <div class="border border-surface-container-high bg-white p-5 shadow-sm">
                            <p class="text-xs font-bold uppercase tracking-[0.12em] text-zinc-500 mb-3">Response SLA</p>
                            <p class="text-base font-bold text-zinc-900">1 to 2 business days</p>
                            <p class="text-sm text-zinc-600 mt-3 leading-6">Urgent collection-related queries are prioritized during operating hours.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div class="border border-surface-container-high bg-surface-container-low p-6 md:p-7">
                            <p class="text-xs font-bold uppercase tracking-[0.12em] text-zinc-500 mb-4">Office And Mailing Address</p>
                            <div class="text-zinc-700 leading-7">
                                <p class="font-semibold text-zinc-900">Click&Collect</p>
                                <p>Cleckhuddersfax Independent Traders Collective</p>
                                <p>12-14 High Street</p>
                                <p>Cleckhuddersfax</p>
                                <p>West Yorkshire, HX1 1AA</p>
                            </div>
                        </div>

                        <div class="border border-surface-container-high bg-surface-container-low p-6 md:p-7">
                            <p class="text-xs font-bold uppercase tracking-[0.12em] text-zinc-500 mb-4">Support Hours</p>
                            <div class="space-y-2 text-sm text-zinc-700">
                                <p><span class="font-semibold text-zinc-900">Monday to Friday:</span> 8:30 AM to 6:00 PM</p>
                                <p><span class="font-semibold text-zinc-900">Saturday:</span> 9:00 AM to 2:00 PM</p>
                                <p><span class="font-semibold text-zinc-900">Sunday:</span> Closed</p>
                            </div>
                            <p class="text-sm text-zinc-600 mt-4 leading-6">
                                For trader operations or local partnership requests, include as much context as possible so the right team can respond first time.
                            </p>
                        </div>
                    </div>

                    <div class="border border-surface-container-high bg-zinc-900 text-white p-6 md:p-7">
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-white/70 mb-3">Before You Send</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm leading-6">
                            <p>Include your order reference if your message is about payment, fulfilment, or collection timing.</p>
                            <p>Trader applicants should mention their shop or business name to speed up triage.</p>
                            <p>Partnership and press requests should include timing, location, and the nature of the collaboration.</p>
                        </div>
                    </div>
                </div>

                <div class="border border-surface-container-high bg-white shadow-sm">
                    <div class="p-6 md:p-8 border-b border-surface-container-high">
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-zinc-500 mb-3">Send An Enquiry</p>
                        <h2 class="text-2xl font-black text-zinc-900">Talk to the right team</h2>
                        <p class="text-sm text-zinc-600 mt-3 leading-6">
                            We route messages by topic so customers, traders, and partners get faster answers.
                        </p>
                    </div>

                    <form action="{{ route('contact.submit') }}" method="POST" class="p-6 md:p-8 space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-zinc-900 mb-2">Full name</label>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    value="{{ old('name', auth()->user()?->full_name ?? '') }}"
                                    class="w-full border border-surface-container-high bg-surface-container-low px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"
                                    required
                                >
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-zinc-900 mb-2">Email address</label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email', auth()->user()?->email ?? '') }}"
                                    class="w-full border border-surface-container-high bg-surface-container-low px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"
                                    required
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-zinc-900 mb-2">Phone number</label>
                                <input
                                    id="phone"
                                    name="phone"
                                    type="text"
                                    value="{{ old('phone', auth()->user()?->phone_no ?? '') }}"
                                    class="w-full border border-surface-container-high bg-surface-container-low px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"
                                >
                            </div>

                            <div>
                                <label for="preferred_contact" class="block text-sm font-semibold text-zinc-900 mb-2">Preferred contact method</label>
                                <select
                                    id="preferred_contact"
                                    name="preferred_contact"
                                    class="w-full border border-surface-container-high bg-surface-container-low px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"
                                    required
                                >
                                    <option value="email" {{ old('preferred_contact', 'email') === 'email' ? 'selected' : '' }}>Email</option>
                                    <option value="phone" {{ old('preferred_contact') === 'phone' ? 'selected' : '' }}>Phone</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_type" class="block text-sm font-semibold text-zinc-900 mb-2">I am contacting you as</label>
                                <select
                                    id="customer_type"
                                    name="customer_type"
                                    class="w-full border border-surface-container-high bg-surface-container-low px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"
                                    required
                                >
                                    <option value="customer" {{ old('customer_type', 'customer') === 'customer' ? 'selected' : '' }}>Customer</option>
                                    <option value="trader" {{ old('customer_type') === 'trader' ? 'selected' : '' }}>Trader</option>
                                    <option value="partner" {{ old('customer_type') === 'partner' ? 'selected' : '' }}>Partner</option>
                                    <option value="press" {{ old('customer_type') === 'press' ? 'selected' : '' }}>Press</option>
                                    <option value="other" {{ old('customer_type') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div>
                                <label for="order_reference" class="block text-sm font-semibold text-zinc-900 mb-2">Order reference</label>
                                <input
                                    id="order_reference"
                                    name="order_reference"
                                    type="text"
                                    value="{{ old('order_reference') }}"
                                    class="w-full border border-surface-container-high bg-surface-container-low px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"
                                    placeholder="Optional"
                                >
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-semibold text-zinc-900 mb-2">Subject</label>
                            <input
                                id="subject"
                                name="subject"
                                type="text"
                                value="{{ old('subject') }}"
                                class="w-full border border-surface-container-high bg-surface-container-low px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20"
                                required
                            >
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-semibold text-zinc-900 mb-2">Message</label>
                            <textarea
                                id="message"
                                name="message"
                                rows="7"
                                class="w-full border border-surface-container-high bg-surface-container-low px-4 py-3 text-sm leading-6 focus:outline-none focus:ring-2 focus:ring-primary/20 resize-y"
                                placeholder="Tell us what you need help with."
                                required
                            >{{ old('message') }}</textarea>
                            <p class="text-xs text-zinc-500 mt-2">Please include enough detail for our team to help without needing a follow-up just to understand the issue.</p>
                        </div>

                        <button type="submit" class="w-full bg-primary text-on-primary px-6 py-3.5 text-sm font-bold hover:opacity-90 transition-opacity">
                            Submit enquiry
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-12 grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="border border-surface-container-high bg-white p-6">
                    <h3 class="text-lg font-bold text-zinc-900 mb-2">Customer orders</h3>
                    <p class="text-sm text-zinc-600 leading-6">Missing confirmations, payment questions, collection windows, substitutions, and product issues after collection.</p>
                </div>
                <div class="border border-surface-container-high bg-white p-6">
                    <h3 class="text-lg font-bold text-zinc-900 mb-2">Trader applications</h3>
                    <p class="text-sm text-zinc-600 leading-6">Application progress, onboarding support, inventory guidance, and marketplace requirements for approved traders.</p>
                </div>
                <div class="border border-surface-container-high bg-white p-6">
                    <h3 class="text-lg font-bold text-zinc-900 mb-2">Partnerships and press</h3>
                    <p class="text-sm text-zinc-600 leading-6">Local activations, collection point partnerships, community events, media requests, and regional initiatives.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
