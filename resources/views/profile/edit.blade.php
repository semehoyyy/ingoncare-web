@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('profile.index') }}"
                class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Profil Saya</h1>
                <p class="text-sm text-gray-500">Kelola Informasi Akun</p>
            </div>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
        <div class="flex border-b border-gray-200">
            <button onclick="showTab('informasi')"
                id="tab-informasi"
                class="tab-button flex-1 px-6 py-4 font-semibold text-cyan-600 border-b-2 border-cyan-600 bg-cyan-50 transition">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    <span>Informasi Pribadi</span>
                </div>
            </button>

            <button onclick="showTab('keamanan')"
                id="tab-keamanan"
                class="tab-button flex-1 px-6 py-4 font-semibold text-gray-600 hover:bg-gray-50 transition">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                    <span>Keamanan</span>
                </div>
            </button>
        </div>
    </div>

    {{-- Tab Content: Informasi Pribadi --}}
    <div id="content-informasi" class="tab-content">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                {{-- Upload Foto Profil --}}
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-lg mb-4">Upload Foto Profil</h3>

                    <div class="flex items-center gap-6">
                        <div class="relative">
                            <div id="preview-container" class="w-24 h-24 rounded-full overflow-hidden border-4 border-gray-200">
                                @if($user->profile_photo)
                                <img id="photo-preview" src="{{ asset('storage/' . $user->profile_photo) }}"
                                    alt="Preview" class="w-full h-full object-cover">
                                @else
                                    <div id="photo-preview"
                                        class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 text-3xl">
                                        👤
                                    </div>
                                @endif

                            </div>
                            <label for="profile_photo"
                                class="absolute bottom-0 right-0 w-8 h-8 bg-cyan-500 rounded-full flex items-center justify-center cursor-pointer hover:bg-cyan-600 transition shadow-lg">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </label>
                        </div>

                        <div class="flex-1">
                            <input type="file"
                                id="profile_photo"
                                name="profile_photo"
                                class="hidden"
                                accept="image/*"
                                onchange="previewProfilePhoto(event)">
                            <p class="font-semibold text-gray-900 mb-1">Foto Profil</p>
                            <p class="text-sm text-gray-500">JPG, PNG maksimal 5MB</p>
                        </div>
                    </div>
                </div>

                {{-- Form Fields --}}
                <div class="p-6 space-y-5">

                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                            placeholder="Contoh: Sooyaa"
                            required>
                        @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                            placeholder="Contoh: JisooSooyaa@gmail.com"
                            required>
                        @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nomor Telepon --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="tel"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                            placeholder="082358481314"
                            required>
                        @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Lahir
                        </label>
                        <input type="date"
                            name="dob"
                            value="{{ old('dob', $user->dob) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        @error('dob')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Alamat
                        </label>
                        <textarea name="address"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent resize-none"
                            placeholder="Contoh: Bandung">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <p class="text-xs text-gray-500">* Wajib diisi</p>

                </div>

                {{-- Action Buttons --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                    <a href="{{ route('profile.index') }}"
                        class="px-6 py-2.5 border border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-100 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-xl font-semibold hover:from-cyan-600 hover:to-blue-600 transition shadow">
                        Simpan Profil
                    </button>
                </div>

            </div>
        </form>
    </div>

    {{-- Tab Content: Keamanan --}}
    <div id="content-keamanan" class="tab-content hidden">
        <form action="{{ route('profile.updatePassword') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-lg mb-2">Ubah Password</h3>
                    <p class="text-sm text-gray-500">Pastikan password Anda aman dan tidak mudah ditebak</p>
                </div>

                <div class="p-6 space-y-5">

                    {{-- Password Saat Ini --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Password Saat Ini <span class="text-red-500">*</span>
                        </label>
                        <input type="password"
                            name="current_password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                            placeholder="Masukkan password saat ini"
                            required>
                        @error('current_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Baru --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Password Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="password"
                            name="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                            placeholder="Masukkan password baru"
                            required>
                        @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Konfirmasi Password Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="password"
                            name="password_confirmation"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                            placeholder="Konfirmasi password baru"
                            required>
                    </div>

                </div>

                {{-- Action Buttons --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                    <a href="{{ route('profile.index') }}"
                        class="px-6 py-2.5 border border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-100 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-xl font-semibold hover:from-cyan-600 hover:to-blue-600 transition shadow">
                        Ubah Password
                    </button>
                </div>

            </div>
        </form>
    </div>

</div>

{{-- JavaScript untuk Tab Switching & Photo Preview --}}
<script>
    // Tab Switching
    function showTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });

        // Remove active state from all buttons
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('text-cyan-600', 'border-b-2', 'border-cyan-600', 'bg-cyan-50');
            btn.classList.add('text-gray-600');
        });

        // Show selected tab
        document.getElementById('content-' + tabName).classList.remove('hidden');

        // Add active state to selected button
        const activeBtn = document.getElementById('tab-' + tabName);
        activeBtn.classList.add('text-cyan-600', 'border-b-2', 'border-cyan-600', 'bg-cyan-50');
        activeBtn.classList.remove('text-gray-600');
    }

    // Photo Preview
    function previewProfilePhoto(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('photo-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>

@endsection