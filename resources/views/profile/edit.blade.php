@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="space-y-6">

    {{-- BANNER --}}
    <div class="text-white px-6 py-5 rounded-2xl flex items-center justify-between"
         style="background:linear-gradient(135deg,#5E4B8B,#9F86C0);">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
                 style="background:rgba(255,255,255,0.2);">
                <i class="ti ti-user-circle text-white" style="font-size:22px;" aria-hidden="true"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold">Edit Profil Saya</h2>
                <p class="text-sm" style="color:#EDE4F5;">Kelola informasi akun Anda</p>
            </div>
        </div>
        <a href="{{ route('profile.index') }}"
           class="w-9 h-9 rounded-full flex items-center justify-center transition"
           style="background:rgba(255,255,255,0.2);"
           onmouseover="this.style.background='rgba(255,255,255,0.35)'"
           onmouseout="this.style.background='rgba(255,255,255,0.2)'">
            <i class="ti ti-x text-white" style="font-size:18px;" aria-hidden="true"></i>
        </a>
    </div>

    @if(session('success'))
    <div class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium"
         style="background:#f0fdf4; border:1.5px solid #bbf7d0; color:#15803d;">
        <span class="flex items-center gap-2">
            <i class="ti ti-circle-check" style="font-size:18px;"></i>
            {{ session('success') }}
        </span>
        <button onclick="this.parentElement.remove()">
            <i class="ti ti-x" style="font-size:16px;"></i>
        </button>
    </div>
    @endif

    {{-- TAB NAVIGATION --}}
    <div class="bg-white rounded-2xl overflow-hidden"
         style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
        <div class="flex" style="border-bottom:1.5px solid #EDE4F5;">

            <button onclick="showTab('informasi')" id="tab-informasi"
                    class="tab-button flex-1 px-6 py-4 text-sm font-semibold transition"
                    style="color:#9F86C0; border-bottom:2px solid #9F86C0; background:#FDFAFF;">
                <div class="flex items-center justify-center gap-2">
                    <i class="ti ti-user" style="font-size:17px;"></i>
                    Informasi Pribadi
                </div>
            </button>

            <button onclick="showTab('keamanan')" id="tab-keamanan"
                    class="tab-button flex-1 px-6 py-4 text-sm font-semibold transition"
                    style="color:#9ca3af;">
                <div class="flex items-center justify-center gap-2">
                    <i class="ti ti-lock" style="font-size:17px;"></i>
                    Keamanan
                </div>
            </button>

        </div>
    </div>

    {{-- TAB: INFORMASI PRIBADI --}}
    <div id="content-informasi" class="tab-content space-y-6">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="profile_photo_cropped" id="croppedImageData">

            {{-- FOTO PROFIL --}}
            <div class="bg-white rounded-2xl p-6"
                 style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
                <h3 class="font-bold text-base mb-5 flex items-center gap-2" style="color:#2D1B69;">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#EDE4F5;">
                        <span class="text-xs font-bold" style="color:#9F86C0;">1</span>
                    </div>
                    Foto Profil
                </h3>

                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 rounded-full overflow-hidden flex-shrink-0"
                         style="border:3px solid #CDB4DB; background:#EDE4F5;">
                        @if($user->profile_photo)
                            <img id="avatarPreview"
                                 src="{{ asset('storage/' . $user->profile_photo) }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <img id="avatarPreview" src="" class="w-full h-full object-cover hidden">
                                <i id="avatarPlaceholder" class="ti ti-user"
                                   style="font-size:32px; color:#CDB4DB;" aria-hidden="true"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <label class="cursor-pointer px-5 py-2.5 rounded-xl text-sm font-semibold transition text-white inline-flex items-center gap-2"
                               style="background:#9F86C0;"
                               onmouseover="this.style.background='#5E4B8B'"
                               onmouseout="this.style.background='#9F86C0'">
                            <i class="ti ti-upload" style="font-size:14px;" aria-hidden="true"></i>
                            Ganti Foto
                            <input type="file" id="profile_photo_input" name="profile_photo"
                                   class="hidden" accept="image/jpeg,image/png,image/jpg"
                                   onchange="openCropModal(event)">
                        </label>
                        <p class="text-xs mt-2" style="color:#9ca3af;">JPG, PNG maksimal 5MB</p>
                        <p class="text-xs mt-0.5" style="color:#9F86C0;">Foto akan di-crop ke bentuk kotak</p>
                    </div>
                </div>
            </div>

            {{-- INFORMASI DASAR --}}
            <div class="bg-white rounded-2xl p-6"
                 style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
                <h3 class="font-bold text-base mb-5 flex items-center gap-2" style="color:#2D1B69;">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#EDE4F5;">
                        <span class="text-xs font-bold" style="color:#9F86C0;">2</span>
                    </div>
                    Informasi Pribadi
                </h3>

                <div class="space-y-5">

                    <div>
                        <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                            Nama Lengkap <span style="color:#ef4444;">*</span>
                        </label>
                        <div class="relative">
                            <i class="ti ti-user absolute left-4 top-1/2 -translate-y-1/2"
                               style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   placeholder="Nama Lengkap"
                                   class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                                   style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                                   onfocus="this.style.borderColor='#9F86C0'"
                                   onblur="this.style.borderColor='#CDB4DB'">
                        </div>
                        @error('name')<p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                            Email <span style="color:#ef4444;">*</span>
                        </label>
                        <div class="relative">
                            <i class="ti ti-mail absolute left-4 top-1/2 -translate-y-1/2"
                               style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   placeholder="email@gmail.com"
                                   class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                                   style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                                   onfocus="this.style.borderColor='#9F86C0'"
                                   onblur="this.style.borderColor='#CDB4DB'">
                        </div>
                        @error('email')<p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                            Nomor Telepon <span style="color:#ef4444;">*</span>
                        </label>
                        <div class="relative">
                            <i class="ti ti-phone absolute left-4 top-1/2 -translate-y-1/2"
                               style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                                   style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                                   onfocus="this.style.borderColor='#9F86C0'"
                                   onblur="this.style.borderColor='#CDB4DB'">
                        </div>
                        @error('phone')<p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Tanggal Lahir</label>
                        <div class="relative">
                            <i class="ti ti-calendar absolute left-4 top-1/2 -translate-y-1/2"
                               style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                            <input type="date" name="dob" value="{{ old('dob', $user->dob) }}"
                                   class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                                   style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                                   onfocus="this.style.borderColor='#9F86C0'"
                                   onblur="this.style.borderColor='#CDB4DB'">
                        </div>
                        @error('dob')<p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Alamat</label>
                        <div class="relative">
                            <i class="ti ti-map-pin absolute left-4 top-4"
                               style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                            <textarea name="address" rows="3"
                                      placeholder="Contoh: Bandung, Jawa Barat"
                                      class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none resize-none"
                                      style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                                      onfocus="this.style.borderColor='#9F86C0'"
                                      onblur="this.style.borderColor='#CDB4DB'">{{ old('address', $user->address) }}</textarea>
                        </div>
                        @error('address')<p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>@enderror
                    </div>

                    <p class="text-xs" style="color:#9ca3af;">* Wajib diisi</p>
                </div>
            </div>

            {{-- BUTTONS --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('profile.index') }}"
                   class="px-6 py-3 rounded-xl text-sm font-semibold transition"
                   style="border:1.5px solid #CDB4DB; color:#9F86C0;"
                   onmouseover="this.style.background='#EDE4F5'"
                   onmouseout="this.style.background=''">
                    Batal
                </a>
                <button type="submit"
                        class="flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold text-white transition"
                        style="background:#9F86C0;"
                        onmouseover="this.style.background='#5E4B8B'"
                        onmouseout="this.style.background='#9F86C0'">
                    <i class="ti ti-device-floppy" style="font-size:16px;" aria-hidden="true"></i>
                    Simpan Profil
                </button>
            </div>

        </form>
    </div>

    {{-- TAB: KEAMANAN --}}
    <div id="content-keamanan" class="tab-content hidden space-y-6">
        <form action="{{ route('profile.updatePassword') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl p-6"
                 style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
                <h3 class="font-bold text-base mb-5 flex items-center gap-2" style="color:#2D1B69;">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#EDE4F5;">
                        <i class="ti ti-lock" style="font-size:14px; color:#9F86C0;"></i>
                    </div>
                    Ubah Password
                </h3>

                <div class="space-y-5">

                    <div>
                        <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                            Password Saat Ini <span style="color:#ef4444;">*</span>
                        </label>
                        <div class="relative">
                            <i class="ti ti-lock absolute left-4 top-1/2 -translate-y-1/2"
                               style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                            <input type="password" name="current_password" required
                                   placeholder="Masukkan password saat ini"
                                   class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                                   style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                                   onfocus="this.style.borderColor='#9F86C0'"
                                   onblur="this.style.borderColor='#CDB4DB'">
                        </div>
                        @error('current_password')<p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                            Password Baru <span style="color:#ef4444;">*</span>
                        </label>
                        <div class="relative">
                            <i class="ti ti-key absolute left-4 top-1/2 -translate-y-1/2"
                               style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                            <input type="password" name="password" required
                                   placeholder="Masukkan password baru"
                                   class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                                   style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                                   onfocus="this.style.borderColor='#9F86C0'"
                                   onblur="this.style.borderColor='#CDB4DB'">
                        </div>
                        @error('password')<p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>@enderror
                        <p class="text-xs mt-1" style="color:#9ca3af;">Minimal 8 karakter</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">
                            Konfirmasi Password Baru <span style="color:#ef4444;">*</span>
                        </label>
                        <div class="relative">
                            <i class="ti ti-key absolute left-4 top-1/2 -translate-y-1/2"
                               style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                            <input type="password" name="password_confirmation" required
                                   placeholder="Konfirmasi password baru"
                                   class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                                   style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                                   onfocus="this.style.borderColor='#9F86C0'"
                                   onblur="this.style.borderColor='#CDB4DB'">
                        </div>
                    </div>

                </div>
            </div>

            {{-- BUTTONS --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('profile.index') }}"
                   class="px-6 py-3 rounded-xl text-sm font-semibold transition"
                   style="border:1.5px solid #CDB4DB; color:#9F86C0;"
                   onmouseover="this.style.background='#EDE4F5'"
                   onmouseout="this.style.background=''">
                    Batal
                </a>
                <button type="submit"
                        class="flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold text-white transition"
                        style="background:#9F86C0;"
                        onmouseover="this.style.background='#5E4B8B'"
                        onmouseout="this.style.background='#9F86C0'">
                    <i class="ti ti-shield-check" style="font-size:16px;" aria-hidden="true"></i>
                    Ubah Password
                </button>
            </div>

        </form>
    </div>

</div>

{{-- CROP MODAL --}}
<div id="cropModal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4"
     style="background:rgba(45,27,105,0.6); backdrop-filter:blur(4px);">
    <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden"
         style="border:1.5px solid #EDE4F5; box-shadow:0 8px 40px rgba(159,134,192,0.25);">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-5 py-4"
             style="border-bottom:1.5px solid #EDE4F5; background:linear-gradient(135deg,#EDE4F5,#F5F0FA);">
            <div>
                <h3 class="font-bold text-base" style="color:#2D1B69;">Crop Foto Profil</h3>
                <p class="text-xs mt-0.5" style="color:#9ca3af;">Sesuaikan posisi foto Anda</p>
            </div>
            <button onclick="closeCropModal()"
                    class="w-8 h-8 rounded-xl flex items-center justify-center transition"
                    style="background:#EDE4F5; color:#9F86C0;"
                    onmouseover="this.style.background='#CDB4DB'"
                    onmouseout="this.style.background='#EDE4F5'">
                <i class="ti ti-x" style="font-size:16px;"></i>
            </button>
        </div>

        {{-- Cropper Area --}}
        <div class="p-4" style="background:#2D1B69;">
            <div class="relative overflow-hidden rounded-xl" style="height:300px;">
                <img id="cropImage" src="" alt="Crop" class="max-w-full" style="display:block;">
            </div>
        </div>

        {{-- Preview + Controls --}}
        <div class="px-5 py-4" style="border-top:1.5px solid #EDE4F5;">
            <div class="flex items-center gap-4 mb-4">
                <div class="flex-shrink-0 text-center">
                    <p class="text-xs mb-1" style="color:#9ca3af;">Preview</p>
                    <div class="w-16 h-16 rounded-full overflow-hidden"
                         style="border:2px solid #9F86C0; background:#EDE4F5;">
                        <img id="cropPreview" src="" class="w-full h-full object-cover" style="display:none;">
                        <div id="cropPreviewPlaceholder" class="w-full h-full flex items-center justify-center">
                            <i class="ti ti-user" style="font-size:24px; color:#CDB4DB;"></i>
                        </div>
                    </div>
                </div>

                <div class="flex-1 space-y-2">
                    <div class="grid grid-cols-4 gap-2">
                        <button type="button" onclick="cropperInstance.zoom(0.1)"
                                class="py-2 text-sm rounded-xl font-medium transition"
                                style="background:#EDE4F5; color:#9F86C0;"
                                onmouseover="this.style.background='#CDB4DB'"
                                onmouseout="this.style.background='#EDE4F5'">
                            <i class="ti ti-zoom-in" style="font-size:15px;"></i>
                        </button>
                        <button type="button" onclick="cropperInstance.zoom(-0.1)"
                                class="py-2 text-sm rounded-xl font-medium transition"
                                style="background:#EDE4F5; color:#9F86C0;"
                                onmouseover="this.style.background='#CDB4DB'"
                                onmouseout="this.style.background='#EDE4F5'">
                            <i class="ti ti-zoom-out" style="font-size:15px;"></i>
                        </button>
                        <button type="button" onclick="cropperInstance.rotate(-45)"
                                class="py-2 text-sm rounded-xl font-medium transition"
                                style="background:#EDE4F5; color:#9F86C0;"
                                onmouseover="this.style.background='#CDB4DB'"
                                onmouseout="this.style.background='#EDE4F5'">
                            <i class="ti ti-rotate" style="font-size:15px;"></i>
                        </button>
                        <button type="button" onclick="cropperInstance.rotate(45)"
                                class="py-2 text-sm rounded-xl font-medium transition"
                                style="background:#EDE4F5; color:#9F86C0;"
                                onmouseover="this.style.background='#CDB4DB'"
                                onmouseout="this.style.background='#EDE4F5'">
                            <i class="ti ti-rotate-clockwise" style="font-size:15px;"></i>
                        </button>
                    </div>
                    <button type="button" onclick="cropperInstance.reset()"
                            class="w-full py-2 text-xs rounded-xl transition"
                            style="background:#F5F0FA; color:#9ca3af;"
                            onmouseover="this.style.background='#EDE4F5'"
                            onmouseout="this.style.background='#F5F0FA'">
                        Reset Posisi
                    </button>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeCropModal()"
                        class="flex-1 py-3 rounded-xl text-sm font-semibold transition"
                        style="border:1.5px solid #CDB4DB; color:#9F86C0;"
                        onmouseover="this.style.background='#EDE4F5'"
                        onmouseout="this.style.background=''">
                    Batal
                </button>
                <button type="button" onclick="applyCrop()"
                        class="flex-1 py-3 rounded-xl text-sm font-semibold text-white transition"
                        style="background:#9F86C0;"
                        onmouseover="this.style.background='#5E4B8B'"
                        onmouseout="this.style.background='#9F86C0'">
                    Gunakan Foto Ini
                </button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

<script>
let cropperInstance = null;

function showTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.add('hidden'));
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.style.color = '#9ca3af';
        btn.style.borderBottom = '';
        btn.style.background = '';
    });
    document.getElementById('content-' + tabName).classList.remove('hidden');
    const activeBtn = document.getElementById('tab-' + tabName);
    activeBtn.style.color = '#9F86C0';
    activeBtn.style.borderBottom = '2px solid #9F86C0';
    activeBtn.style.background = '#FDFAFF';
}

function openCropModal(event) {
    const file = event.target.files[0];
    if (!file) return;
    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran file terlalu besar! Maksimal 5MB.');
        event.target.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = function(e) {
        const cropImage = document.getElementById('cropImage');
        cropImage.src = e.target.result;
        document.getElementById('cropModal').classList.remove('hidden');
        if (cropperInstance) { cropperInstance.destroy(); cropperInstance = null; }
        document.getElementById('cropPreview').style.display = 'none';
        document.getElementById('cropPreviewPlaceholder').style.display = 'flex';
        cropImage.onload = function() {
            cropperInstance = new Cropper(cropImage, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.8,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                crop() {
                    const canvas = cropperInstance.getCroppedCanvas({ width: 200, height: 200 });
                    if (canvas) {
                        const prev = document.getElementById('cropPreview');
                        prev.src = canvas.toDataURL('image/jpeg', 0.9);
                        prev.style.display = 'block';
                        document.getElementById('cropPreviewPlaceholder').style.display = 'none';
                    }
                }
            });
        };
        if (cropImage.complete) cropImage.onload();
    };
    reader.readAsDataURL(file);
}

function closeCropModal() {
    document.getElementById('cropModal').classList.add('hidden');
    if (cropperInstance) { cropperInstance.destroy(); cropperInstance = null; }
    document.getElementById('profile_photo_input').value = '';
}

function applyCrop() {
    if (!cropperInstance) return;
    const canvas = cropperInstance.getCroppedCanvas({ width: 400, height: 400, imageSmoothingQuality: 'high' });
    if (!canvas) { alert('Gagal memproses foto. Coba lagi.'); return; }
    const croppedDataUrl = canvas.toDataURL('image/jpeg', 0.92);
    document.getElementById('croppedImageData').value = croppedDataUrl;
    const prev = document.getElementById('avatarPreview');
    const placeholder = document.getElementById('avatarPlaceholder');
    prev.src = croppedDataUrl;
    prev.classList.remove('hidden');
    if (placeholder) placeholder.style.display = 'none';
    document.getElementById('cropModal').classList.add('hidden');
    if (cropperInstance) { cropperInstance.destroy(); cropperInstance = null; }
    document.getElementById('profile_photo_input').removeAttribute('name');
}

document.getElementById('profileForm').addEventListener('submit', function(e) {
    const croppedData = document.getElementById('croppedImageData').value;
    if (!croppedData) return;
    e.preventDefault();
    const form = this;
    const base64 = croppedData.split(',')[1];
    const byteCharacters = atob(base64);
    const byteNumbers = new Array(byteCharacters.length);
    for (let i = 0; i < byteCharacters.length; i++) byteNumbers[i] = byteCharacters.charCodeAt(i);
    const blob = new Blob([new Uint8Array(byteNumbers)], { type: 'image/jpeg' });
    const formData = new FormData(form);
    formData.delete('profile_photo_cropped');
    formData.delete('profile_photo');
    formData.append('profile_photo', blob, 'profile_cropped.jpg');
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    }).then(r => { if (r.redirected) window.location.href = r.url; else window.location.reload(); })
      .catch(() => form.submit());
});

document.getElementById('cropModal').addEventListener('click', function(e) {
    if (e.target === this) closeCropModal();
});
</script>

@endsection