@extends('app')

@section('title', 'Privacy Policy | Click&Collect')

@section('content')
    <section class="bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="max-w-4xl mb-12">
                <span class="inline-block text-primary font-bold uppercase tracking-[0.12em] text-xs mb-4">Privacy Policy</span>
                <h1 class="text-4xl md:text-5xl font-black text-zinc-900 leading-tight mb-5">
                    Click&Collect Privacy Policy
                </h1>
                <p class="text-zinc-600 text-lg leading-8">
                    This Privacy Policy explains how Click&Collect collects, uses, shares, stores, and protects personal information when you browse our marketplace, create an account, place an order, apply to become a trader, or contact us.
                </p>
                <p class="text-zinc-500 text-sm mt-5">Last updated: May 13, 2026</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-10">
                <aside class="hidden lg:block">
                    <div class="sticky top-28 border-l-2 border-surface-container-high pl-5 space-y-3 text-sm">
                        <a href="#who-we-are" class="block text-zinc-500 hover:text-primary">Who we are</a>
                        <a href="#data-we-collect" class="block text-zinc-500 hover:text-primary">Data we collect</a>
                        <a href="#how-we-use-data" class="block text-zinc-500 hover:text-primary">How we use data</a>
                        <a href="#lawful-bases" class="block text-zinc-500 hover:text-primary">Lawful bases</a>
                        <a href="#sharing" class="block text-zinc-500 hover:text-primary">Sharing</a>
                        <a href="#retention" class="block text-zinc-500 hover:text-primary">Retention</a>
                        <a href="#your-rights" class="block text-zinc-500 hover:text-primary">Your rights</a>
                        <a href="#contact" class="block text-zinc-500 hover:text-primary">Contact</a>
                    </div>
                </aside>

                <div class="space-y-12">
                    <section id="who-we-are" class="scroll-mt-28">
                        <h2 class="text-2xl font-bold text-zinc-900 mb-4">1. Who We Are</h2>
                        <div class="space-y-4 text-zinc-600 leading-7">
                            <p>Click&Collect is a local marketplace operated by the Cleckhuddersfax Independent Traders Collective. We connect customers with approved local traders so customers can order products online and collect them from participating collection points.</p>
                            <p>For most marketplace activity, Click&Collect decides why and how personal information is used and acts as the data controller. Approved traders may also act as independent controllers for limited information they receive to prepare, fulfil, or support your order.</p>
                        </div>
                    </section>

                    <section id="data-we-collect" class="scroll-mt-28">
                        <h2 class="text-2xl font-bold text-zinc-900 mb-4">2. Personal Information We Collect</h2>
                        <div class="overflow-hidden border border-surface-container-high">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-surface-container-low text-zinc-900">
                                    <tr>
                                        <th class="p-4 font-bold">Category</th>
                                        <th class="p-4 font-bold">Examples</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-surface-container-high text-zinc-600">
                                    <tr>
                                        <td class="p-4 font-semibold text-zinc-800">Account details</td>
                                        <td class="p-4">Name, email address, password, account role, account status, profile settings, avatar, and login activity.</td>
                                    </tr>
                                    <tr>
                                        <td class="p-4 font-semibold text-zinc-800">Customer activity</td>
                                        <td class="p-4">Cart items, wishlist items, product views, saved preferences, reviews, order history, collection slot choices, and support messages.</td>
                                    </tr>
                                    <tr>
                                        <td class="p-4 font-semibold text-zinc-800">Order and payment details</td>
                                        <td class="p-4">Products ordered, quantities, prices, discounts, payment status, PayPal transaction references, refunds, invoices, and collection information.</td>
                                    </tr>
                                    <tr>
                                        <td class="p-4 font-semibold text-zinc-800">Trader application details</td>
                                        <td class="p-4">Business name, contact details, shop information, application status, approval notes, inventory, product listings, and order fulfilment records.</td>
                                    </tr>
                                    <tr>
                                        <td class="p-4 font-semibold text-zinc-800">Technical information</td>
                                        <td class="p-4">IP address, browser and device type, session identifiers, security logs, cookie data, timestamps, and error diagnostics.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-zinc-600 leading-7 mt-4">We do not intentionally collect special category data. If you provide allergy, dietary, or accessibility notes, we use them only where needed to support your order or collection experience.</p>
                    </section>

                    <section id="how-we-use-data" class="scroll-mt-28">
                        <h2 class="text-2xl font-bold text-zinc-900 mb-4">3. How We Use Personal Information</h2>
                        <ul class="space-y-3 text-zinc-600 leading-7 list-disc pl-5">
                            <li>To create, authenticate, maintain, and secure customer, trader, and admin accounts.</li>
                            <li>To display products, shops, stock, prices, discounts, collection slots, carts, wishlists, and order history.</li>
                            <li>To process orders, payments, cancellations, refunds, confirmation emails, and customer support requests.</li>
                            <li>To share order details with the relevant trader so they can prepare and fulfil your order.</li>
                            <li>To assess trader applications, manage trader access, and maintain marketplace standards.</li>
                            <li>To prevent fraud, misuse, unauthorised access, payment disputes, and security incidents.</li>
                            <li>To improve platform reliability, diagnose technical issues, and understand how the marketplace is used.</li>
                            <li>To comply with tax, accounting, legal, regulatory, and dispute-resolution obligations.</li>
                        </ul>
                    </section>

                    <section id="lawful-bases" class="scroll-mt-28">
                        <h2 class="text-2xl font-bold text-zinc-900 mb-4">4. Our Lawful Bases</h2>
                        <p class="text-zinc-600 leading-7 mb-4">Where UK GDPR applies, we rely on the following lawful bases depending on the purpose:</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="border border-surface-container-high p-5">
                                <h3 class="font-bold text-zinc-900 mb-2">Contract</h3>
                                <p class="text-zinc-600 text-sm leading-6">Used when processing is needed to provide accounts, baskets, checkout, orders, trader services, and related support.</p>
                            </div>
                            <div class="border border-surface-container-high p-5">
                                <h3 class="font-bold text-zinc-900 mb-2">Legal obligation</h3>
                                <p class="text-zinc-600 text-sm leading-6">Used for tax, accounting, regulatory, consumer protection, and lawful request compliance.</p>
                            </div>
                            <div class="border border-surface-container-high p-5">
                                <h3 class="font-bold text-zinc-900 mb-2">Legitimate interests</h3>
                                <p class="text-zinc-600 text-sm leading-6">Used for security, fraud prevention, service improvement, basic analytics, trader vetting, and dispute handling where our interests are not overridden by your rights.</p>
                            </div>
                            <div class="border border-surface-container-high p-5">
                                <h3 class="font-bold text-zinc-900 mb-2">Consent</h3>
                                <p class="text-zinc-600 text-sm leading-6">Used where we ask for optional permission, such as non-essential marketing or optional cookies. You can withdraw consent at any time.</p>
                            </div>
                        </div>
                    </section>

                    <section id="cookies" class="scroll-mt-28">
                        <h2 class="text-2xl font-bold text-zinc-900 mb-4">5. Cookies And Similar Technologies</h2>
                        <div class="space-y-4 text-zinc-600 leading-7">
                            <p>We use essential cookies and session storage to keep you signed in, protect forms from cross-site request forgery, remember basket activity, and maintain security. These are necessary for the service to work.</p>
                            <p>If we introduce analytics, advertising, or other non-essential cookies, we will provide appropriate notice and controls where required by law.</p>
                        </div>
                    </section>

                    <section id="payments" class="scroll-mt-28">
                        <h2 class="text-2xl font-bold text-zinc-900 mb-4">6. Payments</h2>
                        <p class="text-zinc-600 leading-7">Payments are processed by PayPal or another supported payment provider. We receive limited payment information such as payment status, transaction references, and payer details needed to confirm or troubleshoot an order. We do not store full payment card numbers.</p>
                    </section>

                    <section id="sharing" class="scroll-mt-28">
                        <h2 class="text-2xl font-bold text-zinc-900 mb-4">7. Who We Share Personal Information With</h2>
                        <ul class="space-y-3 text-zinc-600 leading-7 list-disc pl-5">
                            <li>Approved traders, where needed to prepare, fulfil, refund, or support your order.</li>
                            <li>Payment providers, including PayPal, for payment authorisation, capture, refund, and fraud checks.</li>
                            <li>Email, hosting, database, security, analytics, and support service providers that process information on our behalf.</li>
                            <li>Professional advisers, insurers, auditors, regulators, courts, law enforcement, or public authorities where legally required or necessary to protect rights and safety.</li>
                            <li>A successor organisation if the marketplace is reorganised, merged, or transferred, provided appropriate safeguards apply.</li>
                        </ul>
                        <p class="text-zinc-600 leading-7 mt-4">We do not sell personal information.</p>
                    </section>

                    <section id="international-transfers" class="scroll-mt-28">
                        <h2 class="text-2xl font-bold text-zinc-900 mb-4">8. International Transfers</h2>
                        <p class="text-zinc-600 leading-7">Some technology providers may process personal information outside the United Kingdom. Where this happens, we expect appropriate safeguards to be used, such as adequacy regulations, international data transfer agreements, standard contractual clauses, or equivalent protections recognised by applicable law.</p>
                    </section>

                    <section id="retention" class="scroll-mt-28">
                        <h2 class="text-2xl font-bold text-zinc-900 mb-4">9. How Long We Keep Personal Information</h2>
                        <div class="space-y-4 text-zinc-600 leading-7">
                            <p>We keep personal information only for as long as reasonably needed for the purposes described in this policy, including account management, order fulfilment, tax and accounting records, marketplace safety, dispute resolution, and legal compliance.</p>
                            <p>Typical retention periods depend on the data type. Account data is usually kept while the account is active. Order, payment, tax, and accounting records may be kept for up to seven years. Security logs are usually kept for a shorter period unless needed to investigate misuse or protect the platform. Trader application records may be kept while the trader relationship is active and for a reasonable period afterwards.</p>
                            <p>When information is no longer needed, we delete it, anonymise it, or securely archive it with restricted access.</p>
                        </div>
                    </section>

                    <section id="security" class="scroll-mt-28">
                        <h2 class="text-2xl font-bold text-zinc-900 mb-4">10. Security</h2>
                        <p class="text-zinc-600 leading-7">We use administrative, technical, and organisational safeguards designed to protect personal information, including access controls, password hashing, session security, audit-aware administration, and limits on who can access customer, trader, and order records. No online service is completely risk-free, so we also encourage users to use strong passwords and keep account details private.</p>
                    </section>

                    <section id="children" class="scroll-mt-28">
                        <h2 class="text-2xl font-bold text-zinc-900 mb-4">11. Children</h2>
                        <p class="text-zinc-600 leading-7">Click&Collect is not intended for children under 13, and we do not knowingly collect personal information from children under 13. If you believe a child has provided personal information to us, please contact us so we can review and delete it where appropriate.</p>
                    </section>

                    <section id="your-rights" class="scroll-mt-28">
                        <h2 class="text-2xl font-bold text-zinc-900 mb-4">12. Your Privacy Rights</h2>
                        <div class="space-y-4 text-zinc-600 leading-7">
                            <p>Depending on where you live and the law that applies, you may have rights to access, correct, erase, restrict, object to, or receive a portable copy of your personal information. You may also have the right to withdraw consent where processing is based on consent.</p>
                            <p>You can update some account information from your profile settings. For other requests, contact us using the details below. We may need to verify your identity before completing a request.</p>
                            <p>If you are in the United Kingdom and are unhappy with how we handle your information, you can complain to the Information Commissioner's Office. We would appreciate the chance to resolve your concern first.</p>
                        </div>
                    </section>

                    <section id="changes" class="scroll-mt-28">
                        <h2 class="text-2xl font-bold text-zinc-900 mb-4">13. Changes To This Policy</h2>
                        <p class="text-zinc-600 leading-7">We may update this Privacy Policy when our services, legal obligations, or data practices change. If a change is significant, we will take reasonable steps to bring it to your attention, such as updating the date on this page or notifying account holders.</p>
                    </section>

                    <section id="contact" class="scroll-mt-28 bg-surface-container-low p-6 md:p-8">
                        <h2 class="text-2xl font-bold text-zinc-900 mb-4">14. Contact Us</h2>
                        <div class="space-y-3 text-zinc-600 leading-7">
                            <p>For privacy questions, requests, or complaints, contact:</p>
                            <p>
                                <span class="font-semibold text-zinc-800">Click&Collect</span><br>
                                Cleckhuddersfax Independent Traders Collective<br>
                                12-14 High Street<br>
                                Cleckhuddersfax<br>
                                West Yorkshire, HX1 1AA
                            </p>
                            <p>Email: <a href="mailto:support@cleckhuddersfax-local.co.uk" class="text-primary font-semibold hover:underline">support@cleckhuddersfax-local.co.uk</a></p>
                            <p>Phone: +44 (0) 1484 123456</p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
@endsection
