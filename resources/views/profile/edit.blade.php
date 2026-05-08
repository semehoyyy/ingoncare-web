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

    {{-- Success/Error Message --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center justify-between">
        <span class="flex items-center gap-2">✓ {{ session('success') }}</span>
        <button onclick="this.parentElement.remove()">✕</button>
    </div>
    @endif

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
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
            @csrf
            @method('PUT')

            {{-- Hidden input for cropped image --}}
            <input type="hidden" name="profile_photo_cropped" id="croppedImageData">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                {{-- Upload Foto Profil --}}
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-lg mb-4">Upload Foto Profil</h3>

                    <div class="flex items-center gap-6">
                        <div class="relative">
                            {{-- Preview Container --}}
                            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-gray-200 bg-gray-100 flex items-center justify-center" id="avatarContainer">
                                @if($user->profile_photo)
                                    <img id="avatarPreview"
                                        src="{{ asset('storage/' . $user->profile_photo) }}"
                                        alt="Foto Profil"
                                        class="w-full h-full object-cover">
                                @else
                                    <img id="avatarPreview"
                                        src=""
                                        alt="Foto Profil"
                                        class="w-full h-full object-cover hidden">
                                    <span id="avatarPlaceholder" class="text-3xl select-none">👤</span>
                                @endif
                            </div>

                            {{-- Upload Button --}}
                            <label for="profile_photo_input"
                                class="absolute bottom-0 right-0 w-8 h-8 bg-cyan-500 rounded-full flex items-center justify-center cursor-pointer hover:bg-cyan-600 transition shadow-lg">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </label>
                            <input type="file"
                                id="profile_photo_input"
                                name="profile_photo"
                                class="hidden"
                                accept="image/jpeg,image/png,image/jpg"
                                onchange="openCropModal(event)">
                        </div>

                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 mb-1">Foto Profil</p>
                            <p class="text-sm text-gray-500">JPG, PNG maksimal 5MB</p>
                            <p class="text-xs text-cyan-600 mt-1">Foto akan di-crop otomatis ke bentuk kotak</p>
                        </div>
                    </div>
                </div>

                {{-- Form Fields --}}
                <div class="p-6 space-y-5">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                            placeholder="Nama Lengkap" required>
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                            placeholder="email@gmail.com" required>
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                            placeholder="08xxxxxxxxxx" required>
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                        <input type="date" name="dob" value="{{ old('dob', $user->dob) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        @error('dob')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                        <textarea name="address" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent resize-none"
                            placeholder="Contoh: Bandung, Jawa Barat">{{ old('address', $user->address) }}</textarea>
                        @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
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
                    <p class="text-sm text-gray-500">Pastikan password Anda aman</p>
                </div>

                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Password Saat Ini <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="current_password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                            placeholder="Masukkan password saat ini" required>
                        @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Password Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                            placeholder="Masukkan password baru" required>
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Konfirmasi Password Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                            placeholder="Konfirmasi password baru" required>
                    </div>
                </div>

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

{{-- ============================================ --}}
{{-- CROP MODAL --}}
{{-- ============================================ --}}
<div id="cropModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
            <div>
                <h3 class="font-bold text-lg text-gray-900">Crop Foto Profil</h3>
                <p class="text-xs text-gray-500 mt-0.5">Sesuaikan posisi foto Anda</p>
            </div>
            <button onclick="closeCropModal()"
                class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition text-gray-600">
                ✕
            </button>
        </div>

        {{-- Cropper Area --}}
        <div class="p-4 bg-gray-50">
            <div class="relative overflow-hidden rounded-xl" style="height: 300px; background: #1a1a2e;">
                <img id="cropImage" src="" alt="Crop" class="max-w-full" style="display:block;">
            </div>
        </div>

        {{-- Preview + Controls --}}
        <div class="px-5 py-4 border-t border-gray-100">
            <div class="flex items-center gap-4 mb-4">
                <div class="flex-shrink-0">
                    <p class="text-xs text-gray-500 mb-1 text-center">Preview</p>
                    <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-cyan-400 bg-gray-100">
                        <img id="cropPreview" src="" class="w-full h-full object-cover" style="display:none;">
                        <div id="cropPreviewPlaceholder" class="w-full h-full flex items-center justify-center text-gray-300 text-2xl">👤</div>
                    </div>
                </div>

                <div class="flex-1 space-y-2">
                    <div class="flex gap-2">
                        <button type="button" onclick="cropperInstance.zoom(0.1)"
                            class="flex-1 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition font-medium">
                            🔍+
                        </button>
                        <button type="button" onclick="cropperInstance.zoom(-0.1)"
                            class="flex-1 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition font-medium">
                            🔍-
                        </button>
                        <button type="button" onclick="cropperInstance.rotate(-45)"
                            class="flex-1 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition font-medium">
                            ↺
                        </button>
                        <button type="button" onclick="cropperInstance.rotate(45)"
                            class="flex-1 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition font-medium">
                            ↻
                        </button>
                    </div>
                    <button type="button" onclick="cropperInstance.reset()"
                        class="w-full py-2 text-xs text-gray-500 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                        Reset Posisi
                    </button>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeCropModal()"
                    class="flex-1 py-3 border border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="button" onclick="applyCrop()"
                    class="flex-1 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-xl font-semibold hover:from-cyan-600 hover:to-blue-600 transition shadow">
                    Gunakan Foto Ini
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Cropper.js CDN --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

<script>
let cropperInstance = null;

// ===== TAB SWITCHING =====
function showTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('text-cyan-600', 'border-b-2', 'border-cyan-600', 'bg-cyan-50');
        btn.classList.add('text-gray-600');
    });

    document.getElementById('content-' + tabName).classList.remove('hidden');
    const activeBtn = document.getElementById('tab-' + tabName);
    activeBtn.classList.add('text-cyan-600', 'border-b-2', 'border-cyan-600', 'bg-cyan-50');
    activeBtn.classList.remove('text-gray-600');
}

// ===== CROP MODAL =====
function openCropModal(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Validate size (5MB)
    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran file terlalu besar! Maksimal 5MB.');
        event.target.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const cropImage = document.getElementById('cropImage');
        cropImage.src = e.target.result;

        // Show modal
        document.getElementById('cropModal').classList.remove('hidden');

        // Destroy existing cropper
        if (cropperInstance) {
            cropperInstance.destroy();
            cropperInstance = null;
        }

        // Reset preview
        document.getElementById('cropPreview').style.display = 'none';
        document.getElementById('cropPreviewPlaceholder').style.display = 'flex';

        // Init cropper after image loads
        cropImage.onload = function() {
            cropperInstance = new Cropper(cropImage, {
                aspectRatio: 1 / 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.8,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                crop(event) {
                    // Update live preview
                    const canvas = cropperInstance.getCroppedCanvas({ width: 200, height: 200 });
                    if (canvas) {
                        const previewEl = document.getElementById('cropPreview');
                        previewEl.src = canvas.toDataURL('image/jpeg', 0.9);
                        previewEl.style.display = 'block';
                        document.getElementById('cropPreviewPlaceholder').style.display = 'none';
                    }
                }
            });
        };

        // If image already loaded
        if (cropImage.complete) {
            cropImage.onload();
        }
    };

    reader.readAsDataURL(file);
}

function closeCropModal() {
    document.getElementById('cropModal').classList.add('hidden');
    if (cropperInstance) {
        cropperInstance.destroy();
        cropperInstance = null;
    }
    // Reset file input so same file can be re-selected
    document.getElementById('profile_photo_input').value = '';
}

function applyCrop() {
    if (!cropperInstance) return;

    const canvas = cropperInstance.getCroppedCanvas({
        width: 400,
        height: 400,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    });

    if (!canvas) {
        alert('Gagal memproses foto. Coba lagi.');
        return;
    }

    const croppedDataUrl = canvas.toDataURL('image/jpeg', 0.92);

    // Store in hidden input for server
    document.getElementById('croppedImageData').value = croppedDataUrl;

    // Update avatar preview on page
    updateAllAvatars(croppedDataUrl);

    // Close modal
    document.getElementById('cropModal').classList.add('hidden');
    if (cropperInstance) {
        cropperInstance.destroy();
        cropperInstance = null;
    }

    // Convert dataURL to File & replace file input
    dataURLtoFileInput(croppedDataUrl, 'profile_photo_input', 'profile_cropped.jpg');
}

function dataURLtoFileInput(dataUrl, inputId, filename) {
    // We'll send as base64 via hidden input instead
    // The file input stays empty, server reads croppedImageData hidden field
    document.getElementById(inputId).removeAttribute('name');
}

// ===== UPDATE ALL AVATARS ON PAGE =====
function updateAllAvatars(src) {
    // Edit page preview
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarPlaceholder = document.getElementById('avatarPlaceholder');

    if (avatarPreview) {
        avatarPreview.src = src;
        avatarPreview.classList.remove('hidden');
    }
    if (avatarPlaceholder) {
        avatarPlaceholder.style.display = 'none';
    }

    // Header avatars (in navbar)
    document.querySelectorAll('#profileButton img, header img.rounded-full').forEach(img => {
        img.src = src;
    });

    // Any other avatar on page
    document.querySelectorAll('[data-profile-photo]').forEach(el => {
        if (el.tagName === 'IMG') el.src = src;
    });
}

// ===== HANDLE FORM SUBMIT (convert base64 to file if needed) =====
document.getElementById('profileForm').addEventListener('submit', function(e) {
    const croppedData = document.getElementById('croppedImageData').value;
    if (!croppedData) return; // No crop done, submit normally

    e.preventDefault();

    // Convert base64 to Blob and append to FormData
    const form = this;
    const base64 = croppedData.split(',')[1];
    const byteCharacters = atob(base64);
    const byteNumbers = new Array(byteCharacters.length);
    for (let i = 0; i < byteCharacters.length; i++) {
        byteNumbers[i] = byteCharacters.charCodeAt(i);
    }
    const byteArray = new Uint8Array(byteNumbers);
    const blob = new Blob([byteArray], { type: 'image/jpeg' });

    const formData = new FormData(form);
    formData.delete('profile_photo_cropped');
    formData.delete('profile_photo'); // remove empty file input
    formData.append('profile_photo', blob, 'profile_cropped.jpg');

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json, text/html'
        }
    }).then(response => {
        if (response.redirected) {
            window.location.href = response.url;
        } else {
            window.location.reload();
        }
    }).catch(() => {
        form.submit(); // fallback
    });
});

// Close modal on backdrop click
document.getElementById('cropModal').addEventListener('click', function(e) {
    if (e.target === this) closeCropModal();
});
</script>

@endsection