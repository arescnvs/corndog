<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800 flex">

    {{-- SIDEBAR KASIR --}}
    <aside class="w-64 flex-shrink-0 border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 flex flex-col">
        <div class="p-4 font-bold text-lg text-blue-500">Corndog Maxx</div>

        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Kasir Menu')" class="grid">
                <flux:navlist.item icon="home" :href="route('kasir.dashboard')" :current="request()->routeIs('kasir.dashboard')" wire:navigate>
                    Dashboard
                </flux:navlist.item>

                <flux:navlist.item icon="archive-box" :href="route('kasir.lihat-stok')" :current="request()->routeIs('kasir.lihat-stok')" wire:navigate>
                    Lihat Stok
                </flux:navlist.item>

                <flux:navlist.item  :href="route('kasir.daftar-pesanan')" :current="request()->routeIs('kasir.daftar-pesanan')" wire:navigate>
                    Daftar Pesanan
                </flux:navlist.item>

                <flux:navlist.item icon="plus-circle" :href="route('kasir.input-pesanan')" :current="request()->routeIs('kasir.input-pesanan')" wire:navigate>
                    Input Pesanan
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <div class="mt-auto p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-red-500 flex items-center gap-2 hover:text-red-700">
                     Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- KONTEN HALAMAN --}}
    <main class="flex-1 p-8 overflow-y-auto">
        {{ $slot }}
    </main>

    @fluxScripts
</body>
</html>
