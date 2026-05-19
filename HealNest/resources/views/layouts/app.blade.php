<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HealNest')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cream: '#F5F0E8',
                        forest: '#2D5016',
                        midgreen: '#4A7C2F',
                        lightgreen: '#7AAF52',
                        tan: '#C4A96B',
                        earthbrown: '#8B6914',
                    },
                    fontFamily: {
                        heading: ['"Playfair Display"', 'serif'],
                        body: ['Lato', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #F5F0E8; font-family: 'Lato', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside x-data="{ open: true }" class="bg-forest text-white flex flex-col transition-all duration-300"
           :class="open ? 'w-64' : 'w-16'">
        <div class="flex items-center justify-between p-4 border-b border-midgreen">
            <span x-show="open" x-cloak class="font-heading text-xl font-bold text-tan">🌿 HealNest</span>
            <button @click="open = !open" class="text-tan hover:text-white ml-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <nav class="flex-1 py-4 space-y-1 overflow-y-auto">
            <x-nav-item href="{{ route('dashboard') }}" icon="🏠" label="Dashboard"/>
            <x-nav-item href="{{ route('mood.create') }}" icon="😊" label="Log Mood"/>
            <x-nav-item href="{{ route('mood.history') }}" icon="📈" label="Progress"/>
            <x-nav-item href="{{ route('assessment.index') }}" icon="🧠" label="Assessment"/>
            <x-nav-item href="{{ route('resources') }}" icon="📚" label="Resources"/>
            <x-nav-item href="{{ route('connect.counselor') }}" icon="👩‍⚕️" label="Contact Counselor"/>

            @if(auth()->user()?->isCounselor() || auth()->user()?->isAdmin())
                <div class="border-t border-midgreen my-2"></div>
                <x-nav-item href="{{ route('counselor.index') }}" icon="👩‍⚕️" label="Counselor"/>
            @endif

            @if(auth()->user()?->isAdmin())
                <x-nav-item href="{{ route('admin.index') }}" icon="⚙️" label="Admin"/>
                <x-nav-item href="{{ route('admin.users') }}" icon="👥" label="Users"/>
                <x-nav-item href="{{ route('admin.counselors') }}" icon="👩‍⚕️" label="Counselors"/>
                <x-nav-item href="{{ route('admin.reports') }}" icon="📊" label="Reports"/>
            @endif
        </nav>

        <div class="p-4 border-t border-midgreen">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-tan hover:text-white w-full text-sm">
                    <span>🚪</span><span x-show="open" x-cloak>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Topbar --}}
        <header class="bg-white border-b border-tan/30 px-6 py-3 flex items-center justify-between shadow-sm">
            <h1 class="font-heading text-forest text-xl font-semibold">@yield('page-title', 'Dashboard')</h1>
            <div class="flex items-center gap-4">
                @php $alertCount = auth()->check() ? \App\Models\Alert::where('user_id', (string)auth()->id())->where('status','open')->count() : 0; @endphp
                <div x-data="{ show: false }" class="relative">
                    <button @click="show = !show" class="relative text-forest hover:text-midgreen">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if($alertCount > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">{{ $alertCount }}</span>
                        @endif
                    </button>
                    <div x-show="show" @click.away="show = false" x-cloak
                         class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-tan/30 z-50 p-3">
                        @if($alertCount > 0)
                            <p class="text-sm text-earthbrown font-semibold">⚠️ {{ $alertCount }} open alert(s)</p>
                            <p class="text-xs text-gray-500 mt-1">Please check your assessment results.</p>
                        @else
                            <p class="text-sm text-gray-500">No new notifications</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-midgreen flex items-center justify-center text-white text-sm font-bold">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                    </div>
                    <span class="text-forest text-sm font-medium hidden md:block">{{ auth()->user()?->name }}</span>
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        <div class="px-6 pt-4">
            @if(session('success'))
                <x-alert-banner type="success" :message="session('success')"/>
            @endif
            @if(session('error'))
                <x-alert-banner type="error" :message="session('error')"/>
            @endif
            @if($errors->any())
                <x-alert-banner type="error" :message="$errors->first()"/>
            @endif
        </div>

        <main class="flex-1 min-h-0 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
