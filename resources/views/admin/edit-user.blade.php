<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Kullanıcıyı Düzenle
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto py-10">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block mb-1">Ad</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Rol</label>
                <select name="role" class="w-full border p-2 rounded" required>
                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Güncelle</button>
        </form>
    </div>
</x-app-layout>