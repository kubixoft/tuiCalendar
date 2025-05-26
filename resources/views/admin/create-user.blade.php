<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Yeni Kullanıcı Ekle
        </h2>
    </x-slot>

    <div class="max-w-lg mx-auto py-10">
        @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul class="list-disc pl-6">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium">İsim</label>
                <input type="text" name="name" id="name" class="w-full border p-2 rounded" required>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input type="email" name="email" id="email" class="w-full border p-2 rounded" required>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium">Şifre</label>
                <input type="password" name="password" id="password" class="w-full border p-2 rounded" required>
            </div>

            <div>
                <label for="role" class="block text-sm font-medium">Rol</label>
                <select name="role" id="role" class="w-full border p-2 rounded" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Kaydet</button>
            </div>
        </form>
    </div>
</x-app-layout>