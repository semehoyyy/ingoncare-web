{{-- resources/views/pets/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Hewan')

@section('content')
<div class="">

    <!-- Banner -->
    <div class="bg-[#13CAD6] text-white px-6 py-4 rounded-xl flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="bg-white/30 p-3 rounded-full text-2xl">🐾</div>
            <div>
                <h2 class="text-xl font-semibold">Edit Hewan Peliharaan</h2>
                <p class="text-sm text-white/80">Perbarui informasi hewan peliharaan</p>
            </div>
        </div>
        <a href="{{ route('hewan-saya') }}" class="text-white text-xl">✖</a>
    </div>

    <!-- FORM -->
    <form action="{{ route('pets.update', $pet->id) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-8">
        @csrf
        @method('PUT')

        <!-- Upload Foto -->
        <div class="bg-white border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Foto Hewan</h3>
            <div class="flex items-center gap-6">
                <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center border-2 border-gray-200">
                    @if($pet->photo)
                        <img id="photoPreview" src="{{ asset('storage/' . $pet->photo) }}" class="w-full h-full object-cover">
                    @else
                        <img id="photoPreview" src="" class="w-full h-full object-cover hidden">
                        <span id="photoPlaceholder" class="text-3xl">🐾</span>
                    @endif
                </div>
                <div>
                    <label class="cursor-pointer bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg transition text-sm font-medium">
                        Ganti Foto
                        <input type="file" name="photo" class="hidden" accept="image/*" onchange="previewPhoto(event)">
                    </label>
                    <p class="text-gray-500 text-xs mt-2">JPG, PNG maksimal 5MB</p>
                </div>
            </div>
        </div>

        <!-- Informasi Dasar -->
        <div class="bg-white border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold mb-4">1. Informasi Dasar</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="font-medium">Nama Hewan *</label>
                    <input type="text" name="name" value="{{ old('name', $pet->name) }}"
                        class="w-full p-3 border rounded-xl mt-1" required>
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="font-medium">Jenis Hewan *</label>
                    <select name="species" class="w-full p-3 border rounded-xl mt-1" required>
                        <option value="">Pilih Jenis</option>
                        @foreach(['Kucing','Anjing','Burung','Kelinci','Hamster','Kura-kura','Lainnya'] as $s)
                            <option value="{{ $s }}" {{ old('species', $pet->species) == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                    @error('species')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="font-medium">Ras / Breed</label>
                    <input type="text" name="breed" value="{{ old('breed', $pet->breed) }}"
                        class="w-full p-3 border rounded-xl mt-1" placeholder="Contoh: Golden Retriever">
                </div>

                <div>
                    <label class="font-medium">Jenis Kelamin *</label>
                    <select name="gender" class="w-full p-3 border rounded-xl mt-1" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Jantan" {{ old('gender', $pet->gender) == 'Jantan' ? 'selected' : '' }}>Jantan</option>
                        <option value="Betina" {{ old('gender', $pet->gender) == 'Betina' ? 'selected' : '' }}>Betina</option>
                    </select>
                    @error('gender')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="font-medium">Tanggal Lahir *</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $pet->birth_date ? \Carbon\Carbon::parse($pet->birth_date)->format('Y-m-d') : '') }}"
                        class="w-full p-3 border rounded-xl mt-1" required>
                    @error('birth_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="font-medium">Berat Badan (Kg)</label>
                    <input type="number" step="0.1" name="weight" value="{{ old('weight', $pet->weight) }}"
                        class="w-full p-3 border rounded-xl mt-1">
                </div>

            </div>
        </div>

        <!-- Karakteristik Fisik -->
        <div class="bg-white border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold mb-4">2. Karakteristik Fisik</h3>
            <label class="font-medium">Ciri Khusus</label>
            <textarea name="special_marks" rows="3" class="w-full p-3 border rounded-xl mt-1"
                placeholder="Contoh: Terdapat bercak hitam di kaki depan">{{ old('special_marks', $pet->special_marks) }}</textarea>
        </div>

        <!-- Informasi Kesehatan -->
        <div class="bg-white border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold mb-4">3. Informasi Kesehatan</h3>

            <label class="font-medium">Status Steril</label>
            <select name="is_steril" class="w-full p-3 border rounded-xl mt-1">
                <option value="1" {{ old('is_steril', $pet->is_steril) == '1' ? 'selected' : '' }}>Sudah</option>
                <option value="0" {{ old('is_steril', $pet->is_steril) == '0' ? 'selected' : '' }}>Belum</option>
            </select>

            <div class="mt-4">
                <label class="font-medium">Alergi</label>
                <input type="text" name="allergies" value="{{ old('allergies', $pet->allergies) }}"
                    class="w-full p-3 border rounded-xl mt-1" placeholder="Contoh: Alergi ayam">
            </div>

            <div class="mt-4">
                <label class="font-medium">Kondisi Kesehatan Khusus</label>
                <textarea name="health_notes" rows="3" class="w-full p-3 border rounded-xl mt-1"
                    placeholder="Contoh: Diabetes, arthritis">{{ old('health_notes', $pet->health_notes) }}</textarea>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('hewan-saya') }}" class="px-6 py-2 border rounded-xl text-gray-600 hover:bg-gray-100">Batal</a>
            <button type="submit" class="px-6 py-2 bg-[#13CAD6] text-white rounded-xl hover:bg-[#0fb3c2] shadow">
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>

<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('photoPreview');
            const placeholder = document.getElementById('photoPlaceholder');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection