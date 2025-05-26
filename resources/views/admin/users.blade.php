<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Kullanıcı Listesi
        </h2>
    </x-slot>

    <div class="py-12 px-6">
        <a href="{{ route('admin.users.create') }}" class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">
            + Yeni Kullanıcı
        </a>

        <table class="w-full border border-gray-300 text-center">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Ad</th>
                    <th class="border px-4 py-2">Email</th>
                    <th class="border px-4 py-2">Rol</th>
                    <th class="border px-4 py-2">İşlem</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="border px-4 py-2">{{ $user->id }}</td>
                    <td class="border px-4 py-2">{{ $user->name }}</td>
                    <td class="border px-4 py-2">{{ $user->email }}</td>
                    <td class="border px-4 py-2">{{ $user->role }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:underline mr-2">Düzenle</a>

                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Bu kullanıcı silinsin mi?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Sil</button>
                        </form>

                        <a href="{{ route('admin.users.calendar', $user->id) }}" class="text-grey-600 hover:underline ml-2">
                            Takvim
                        </a>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>