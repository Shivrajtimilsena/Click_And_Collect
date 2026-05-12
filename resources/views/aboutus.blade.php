@extends('app')

@section('content')
    <div class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-block text-red-600 font-semibold mb-2">OUR STORY</span>
                    <h1 class="text-5xl font-bold text-gray-900 mb-6 leading-tight">
                        Connecting You to<br/>
                        the <span class="italic">Heart</span> of the<br/>
                        Village.
                    </h1>
                    <p class="text-lg text-gray-700 mb-4">We are a digital bridge between marketplace artisans in your neighborhood and your kitchen table. High-end curators and small-batch specialists thrive when bypassing the middle shop and selling directly.</p>
                    <button class="bg-red-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-red-700">EXPLORE THE MARKET</button>
                </div>
                <div class="bg-gray-400 overflow-hidden">
                    <div class="bg-gray-500 p-8 text-white">
                        <div class="text-center">
                            <p class="text-lg font-semibold mb-2">LOUA & MARKET</p>
                            <p class="text-sm mb-4">SAFE RE WORK</p>
                            <img src="/images/aboutus_hero.png" alt="Loua Market" class="rounded-lg">
                            <p class="text-sm mt-4">"The finest vegetables in the valley, delivered without the hype."</p>
                            <p class="text-xs mt-2">- LOUA KUUSAR</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Local Curator Philosophy -->
    <div class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-8">The Local Curator Philosophy.</h2>
                    <p class="text-lg text-gray-700 mb-6">We believe that local commerce is the soul of our community. At its best, a fast-paced world needs the butcher, the baker, and the greensgrocer are often missed for convenience.</p>
                    <p class="text-lg text-gray-700 mb-6">By unifying the fragmented local shopping experiences, we empower artisans to support their favorite small businesses through a single, seamless digital storefront. We don't just aggregate; we curate. Every artisan on our platform has been hand-selected and vetted for their commitment to quality.</p>
                    <p class="text-lg text-gray-700">By unifying the fragmented local shopping experiences, we empower retailers to support their favorite small businesses through a single, seamless digital storefront. We don't just aggregate; we curate. Every artisan on our platform has been hand-selected for their commitment to quality.</p>
                </div>
                <div class="bg-gray-100 rounded-2xl overflow-hidden">
                    <img src="https://via.placeholder.com/400x500?text=Local+Artisans" alt="Local artisans at work" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </div>

    <!-- Pure Simplicity Section -->
    <div class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-16">The Process<br/>Pure Simplicity.</h2>
            <div class="grid grid-cols-3 gap-8">
                <div class="bg-white w-20 h-20 flex items-center justify-center mx-auto mb-6">
                    <div class="text-red-600 text-3xl">1</div>
                </div>
                <div class="bg-white w-20 h-20 flex items-center justify-center mx-auto mb-6">
                    <div class="text-red-600 text-3xl">2</div>
                </div>
                <div class="bg-white w-20 h-20 flex items-center justify-center mx-auto mb-6">
                    <div class="text-red-600 text-3xl">3</div>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Curate Your Basket</h3>
                    <p class="text-gray-700">Discover artisan products curated locally from a desire to preserve the heritage of local shopping here uniting ease of modern discovery.</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Unified Checkout</h3>
                    <p class="text-gray-700">Checkout once for everything in your basket. Manage our secure, hygiene and safety standards for your entire shopping cart.</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Collective Collection</h3>
                    <p class="text-gray-700">Curated checkout from all sellers on Wednesday during this venue, drop-in pick up at your local</p>
                </div>
            </div>
        </div>
    </div>


@endsection