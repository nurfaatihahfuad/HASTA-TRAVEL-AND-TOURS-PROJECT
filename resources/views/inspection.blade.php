Baik Nur! Ini contoh ringkas fail Blade PHP untuk paparan **Damage Case** seperti dalam dashboard yang awak tunjuk. Ia hanya untuk paparan (view), tanpa logik simpanan atau pengesahan.

```php
{{-- resources/views/staff/damage-case.blade.php --}}
@extends('layouts.staff')
@section('title', 'Damage Case')

@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4 text-red-600">Damage Case</h2>

    <div class="bg-white p-6 rounded shadow-md">
        <form>
            <div class="mb-4">
                <label for="person_in_charge" class="block text-sm font-medium text-gray-700">Person-in-charge</label>
                <input type="text" id="person_in_charge" class="mt-1 block w-full border rounded px-3 py-2" placeholder="Nama staf">
            </div>

            <div class="mb-4">
                <label for="vehicle_model" class="block text-sm font-medium text-gray-700">Vehicle Model</label>
                <input type="text" id="vehicle_model" class="mt-1 block w-full border rounded px-3 py-2" placeholder="Contoh: Toyota Vios">
            </div>

            <div class="mb-4">
                <label for="plate_number" class="block text-sm font-medium text-gray-700">Plate Number</label>
                <input type="text" id="plate_number" class="mt-1 block w-full border rounded px-3 py-2 uppercase" placeholder="Contoh: JXX 1234">
            </div>

            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" id="date" class="mt-1 block w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Type of Damage</label>
                <div class="grid grid-cols-2 gap-3">
                    <label><input type="radio" name="damage_type" value="collision" checked> Collision Damage</label>
                    <label><input type="radio" name="damage_type" value="non_collision"> Non-Collision Damage</label>
                    <label><input type="radio" name="damage_type" value="technical"> Technical Damage</label>
                    <label><input type="radio" name="damage_type" value="body"> Body Damage</label>
                    <label><input type="radio" name="damage_type" value="glass"> Glass Damage</label>
                    <label><input type="radio" name="damage_type" value="total_loss"> Total Loss Damage</label>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
```

Blade ini hanya untuk paparan input dan pilihan jenis kerosakan. Tiada penyimpanan data atau pengesahan. Kalau awak nak sambung dengan controller atau tambah fungsi simpan ke database, saya boleh bantu teruskan. Nak saya tambah bahagian itu?
