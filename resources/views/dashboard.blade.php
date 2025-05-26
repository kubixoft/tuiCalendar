<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-6">

        @if(auth()->user()->role === 'admin')
        <h2 class="text-2xl font-bold text-blue-800 mb-4">Admin Paneli</h2>

        <ul class="list-disc pl-6 text-green-700 mb-6">
            <li>Kullanıcıları görüntüle</li>
            <li>Yeni kullanıcı ekle</li>
            <li>Yetkileri düzenle</li>
        </ul>

        <div class="flex gap-4">
            <a href="{{ route('admin.users') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Admin Paneline Git
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Çıkış Yap
                </button>
            </form>
        </div>

        @else
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Hoş geldin {{ auth()->user()->name }}</h2>

        <p class="text-gray-600 mb-6">
            Şu an sistem üzerinde aktif bir işlem yapamazsın. Sadece kendi bilgilerini görüntüleyebilirsin.
        </p>

        <div class="flex gap-4">
            <a href="{{ route('events.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Etkinlik Takvimi
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Çıkış Yap
                </button>
            </form>
        </div>
        @endif

    </div>
</x-app-layout>