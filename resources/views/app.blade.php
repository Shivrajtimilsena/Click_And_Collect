<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Click&Collect | Local Shopping')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface": "#f6f6f6",
                        "on-error-container": "#510017",
                        "surface-variant": "#dbdddd",
                        "primary-dim": "#9f1600",
                        "tertiary": "#9b3f15",
                        "on-primary-fixed-variant": "#5d0900",
                        "on-primary": "#ffefec",
                        "inverse-surface": "#0c0f0f",
                        "primary-fixed-dim": "#ff5c3e",
                        "secondary": "#5c5b5b",
                        "surface-container-high": "#e1e3e3",
                        "primary-fixed": "#ff775d",
                        "inverse-primary": "#fd583a",
                        "on-tertiary-fixed-variant": "#692100",
                        "secondary-dim": "#504f4f",
                        "on-secondary-fixed": "#403f3f",
                        "error": "#b41340",
                        "tertiary-fixed": "#ff956b",
                        "on-background": "#2d2f2f",
                        "surface-container-low": "#f0f1f1",
                        "on-surface": "#2d2f2f",
                        "tertiary-container": "#ff956b",
                        "outline-variant": "#acadad",
                        "background": "#f6f6f6",
                        "secondary-container": "#e5e2e1",
                        "surface-container-lowest": "#ffffff",
                        "on-surface-variant": "#5a5c5c",
                        "outline": "#767777",
                        "on-error": "#ffefef",
                        "surface-bright": "#f6f6f6",
                        "on-secondary-container": "#525151",
                        "on-tertiary": "#ffefeb",
                        "on-primary-container": "#4c0600",
                        "inverse-on-surface": "#9c9d9d",
                        "on-secondary": "#f5f2f1",
                        "primary-container": "#ff775d",
                        "surface-tint": "#b12209",
                        "surface-container-highest": "#dbdddd",
                        "on-tertiary-container": "#5a1c00",
                        "error-dim": "#a70138",
                        "secondary-fixed-dim": "#d6d4d3",
                        "on-secondary-fixed-variant": "#5c5b5b",
                        "tertiary-dim": "#8b3309",
                        "surface-dim": "#d3d5d5",
                        "secondary-fixed": "#e5e2e1",
                        "on-primary-fixed": "#000000",
                        "primary": "#b12209",
                        "error-container": "#f74b6d",
                        "surface-container": "#e7e8e8",
                        "tertiary-fixed-dim": "#f68355",
                        "on-tertiary-fixed": "#310b00"
                    },
                    "borderRadius": {
                        "DEFAULT": "1rem",
                        "lg": "2rem",
                        "xl": "3rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Plus Jakarta Sans"],
                        "body": ["Manrope"],
                        "label": ["Manrope"]
                    }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Manrope', sans-serif; }
        h1, h2, h3, h4 { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-surface text-on-background selection:bg-primary-container selection:text-on-primary-container">
    @include('components.navbar')

    <main class="pt-24 pb-12 px-4 md:px-12 max-w-[1920px] mx-auto">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-error/10 text-error rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-500/10 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    @include('modals.auth-modal')
    @include('components.footer')
</body>
</html>
