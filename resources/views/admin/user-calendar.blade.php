<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Etkinlik Takvimi
        </h2>
    </x-slot>

    <div class="p-6">

        <div class="flex gap-2 mb-4">
            <button onclick="changeView('day')" class="bg-gray-200 px-3 py-1 rounded">Günlük</button>
            <button onclick="changeView('week')" class="bg-gray-200 px-3 py-1 rounded">Haftalık</button>
            <button onclick="changeView('month')" class="bg-gray-200 px-3 py-1 rounded">Aylık</button>
        </div>


        <div id="calendar" data-user-id="{{ $user->id }}" style="height: 800px;"></div>


        <div id="customPopup" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white shadow-lg p-6 rounded z-50 hidden" style="width: 55% !important;">
            <h2 class="text-lg font-semibold mb-2">Etkinlik Ekle</h2>

            <label class="block mb-1">Başlık</label>
            <input id="customTitle" type="text" class="w-full border mb-3 px-2 py-1 rounded" />

            <label class="block mb-1">Açıklama</label>
            <textarea id="customDescription" class="w-full border mb-3 px-2 py-1 rounded"></textarea>

            <label class="block mb-1">Başlangıç Zamanı</label>
            <input id="customStart" type="datetime-local" class="w-full border mb-3 px-2 py-1 rounded" />

            <label class="block mb-1">Bitiş Zamanı</label>
            <input id="customEnd" type="datetime-local" class="w-full border mb-3 px-2 py-1 rounded" />

            <div class="flex justify-end gap-2">
                <button id="cancelBtn" class="bg-gray-400 text-white px-4 py-2 rounded">İptal</button>
                <button id="saveBtn" class="bg-blue-600 text-white px-4 py-2 rounded">Kaydet</button>
            </div>
        </div>

        <div id="editPopup" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white shadow-lg p-6 rounded z-50 hidden" style="width: 55% !important;">
            <h2 class="text-lg font-semibold mb-2">Etkinlik Düzenle</h2>

            <input type="hidden" id="editId">

            <label class="block mb-1">Başlık</label>
            <input id="editTitle" type="text" class="w-full border mb-3 px-2 py-1 rounded" />

            <label class="block mb-1">Açıklama</label>
            <textarea id="editDescription" class="w-full border mb-3 px-2 py-1 rounded"></textarea>

            <label class="block mb-1">Başlangıç</label>
            <input id="editStart" type="datetime-local" class="w-full border mb-3 px-2 py-1 rounded" />

            <label class="block mb-1">Bitiş</label>
            <input id="editEnd" type="datetime-local" class="w-full border mb-3 px-2 py-1 rounded" />

            <div class="flex justify-end gap-2">
                <button id="editCancelBtn" class="bg-gray-400 text-white px-4 py-2 rounded">İptal</button>
                <button id="editSaveBtn" class="bg-green-600 text-white px-4 py-2 rounded">Güncelle</button>
            </div>
        </div>


    </div>
</x-app-layout>