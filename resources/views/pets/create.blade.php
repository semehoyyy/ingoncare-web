@extends('layouts.app')

@section('title', 'Tambah Hewan')

@section('content')
<div>

    {{-- BANNER --}}
    <div class="text-white px-6 py-5 rounded-2xl flex items-center justify-between mb-6"
         style="background:linear-gradient(135deg,#5E4B8B,#9F86C0);">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
                 style="background:rgba(255,255,255,0.2);">
                <i class="ti ti-paw text-white" style="font-size:22px;" aria-hidden="true"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold">Tambah Hewan Baru</h2>
                <p class="text-sm" style="color:#EDE4F5;">Lengkapi informasi hewan peliharaan</p>
            </div>
        </div>
        <a href="{{ route('hewan-saya') }}"
           class="w-9 h-9 rounded-full flex items-center justify-center transition"
           style="background:rgba(255,255,255,0.2);"
           onmouseover="this.style.background='rgba(255,255,255,0.35)'"
           onmouseout="this.style.background='rgba(255,255,255,0.2)'">
            <i class="ti ti-x text-white" style="font-size:18px;" aria-hidden="true"></i>
        </a>
    </div>

    {{-- FORM --}}
    <form action="{{ route('pets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- UPLOAD FOTO --}}
        <div class="bg-white rounded-2xl p-6" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <h3 class="font-bold text-base mb-4 flex items-center gap-2" style="color:#2D1B69;">
                <i class="ti ti-camera" style="font-size:18px; color:#9F86C0;" aria-hidden="true"></i>
                Foto Hewan
            </h3>
            <div class="flex flex-col items-center">
                <div class="w-32 h-32 rounded-full overflow-hidden mb-4 flex items-center justify-center"
                     style="border:3px solid #CDB4DB; background:#EDE4F5;"
                     id="previewWrapper">
                    <img id="photoPreview" src="" class="w-full h-full object-cover hidden">
                    <i id="photoPlaceholder" class="ti ti-paw" style="font-size:40px; color:#CDB4DB;" aria-hidden="true"></i>
                </div>
                <label class="cursor-pointer px-5 py-2.5 rounded-xl text-sm font-semibold transition text-white"
                       style="background:#9F86C0;"
                       onmouseover="this.style.background='#5E4B8B'"
                       onmouseout="this.style.background='#9F86C0'">
                    <i class="ti ti-upload mr-1" style="font-size:14px;" aria-hidden="true"></i>
                    Pilih Foto Hewan
                    <input type="file" name="photo" id="photoInput" class="hidden" accept="image/*" onchange="previewPhoto(event)">
                </label>
                <p class="text-xs mt-2" style="color:#9ca3af;">JPG, PNG maksimal 5MB</p>
            </div>
        </div>

        {{-- INFORMASI DASAR --}}
        <div class="bg-white rounded-2xl p-6" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <h3 class="font-bold text-base mb-5 flex items-center gap-2" style="color:#2D1B69;">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#EDE4F5;">
                    <span class="text-xs font-bold" style="color:#9F86C0;">1</span>
                </div>
                Informasi Dasar
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Nama Hewan *</label>
                    <div class="relative">
                        <i class="ti ti-paw absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <input type="text" name="name" placeholder="Contoh: Milo" required
                               class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                               style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                               onfocus="this.style.borderColor='#9F86C0'"
                               onblur="this.style.borderColor='#CDB4DB'">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Jenis Hewan *</label>
                    <div class="relative">
                        <i class="ti ti-category absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <select name="species" required
                                class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none appearance-none"
                                style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                                onfocus="this.style.borderColor='#9F86C0'"
                                onblur="this.style.borderColor='#CDB4DB'">
                            <option value="">Pilih Jenis</option>
                            <option>Kucing</option>
                            <option>Anjing</option>
                            <option>Burung</option>
                            <option>Kelinci</option>
                            <option>Lainnya</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Ras / Breed</label>
                    <div class="relative">
                        <i class="ti ti-dna absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <input type="text" name="breed" placeholder="Contoh: Golden Retriever"
                               class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                               style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                               onfocus="this.style.borderColor='#9F86C0'"
                               onblur="this.style.borderColor='#CDB4DB'">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Jenis Kelamin *</label>
                    <div class="relative">
                        <i class="ti ti-gender-bigender absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <select name="gender" required
                                class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none appearance-none"
                                style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                                onfocus="this.style.borderColor='#9F86C0'"
                                onblur="this.style.borderColor='#CDB4DB'">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option>Jantan</option>
                            <option>Betina</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Tanggal Lahir *</label>
                    <div class="relative">
                        <i class="ti ti-calendar absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <input type="date" name="birth_date" required
                               class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                               style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                               onfocus="this.style.borderColor='#9F86C0'"
                               onblur="this.style.borderColor='#CDB4DB'">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Berat Badan (Kg)</label>
                    <div class="relative">
                        <i class="ti ti-weight absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <input type="number" step="0.1" name="weight" placeholder="Contoh: 2.5"
                               class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                               style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                               onfocus="this.style.borderColor='#9F86C0'"
                               onblur="this.style.borderColor='#CDB4DB'">
                    </div>
                </div>

            </div>
        </div>

        {{-- KARAKTERISTIK FISIK --}}
        <div class="bg-white rounded-2xl p-6" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <h3 class="font-bold text-base mb-5 flex items-center gap-2" style="color:#2D1B69;">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#EDE4F5;">
                    <span class="text-xs font-bold" style="color:#9F86C0;">2</span>
                </div>
                Karakteristik Fisik
            </h3>
            <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Ciri Khusus</label>
            <textarea name="special_marks" rows="3"
                      placeholder="Contoh: Terdapat bercak hitam di kaki depan kanan dan ekornya bervolume"
                      class="w-full rounded-xl px-4 py-3 text-sm focus:outline-none resize-none"
                      style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                      onfocus="this.style.borderColor='#9F86C0'"
                      onblur="this.style.borderColor='#CDB4DB'"></textarea>
        </div>

        {{-- INFORMASI KESEHATAN --}}
        <div class="bg-white rounded-2xl p-6" style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <h3 class="font-bold text-base mb-5 flex items-center gap-2" style="color:#2D1B69;">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#EDE4F5;">
                    <span class="text-xs font-bold" style="color:#9F86C0;">3</span>
                </div>
                Informasi Kesehatan
            </h3>

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Status Steril</label>
                    <div class="relative">
                        <i class="ti ti-vaccine absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <select name="is_steril"
                                class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none appearance-none"
                                style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                                onfocus="this.style.borderColor='#9F86C0'"
                                onblur="this.style.borderColor='#CDB4DB'">
                            <option value="1">Sudah</option>
                            <option value="0">Belum</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Alergi</label>
                    <div class="relative">
                        <i class="ti ti-alert-triangle absolute left-4 top-1/2 -translate-y-1/2" style="font-size:16px; color:#CDB4DB;" aria-hidden="true"></i>
                        <input type="text" name="allergies" placeholder="Contoh: Alergi ayam"
                               class="w-full rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none"
                               style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                               onfocus="this.style.borderColor='#9F86C0'"
                               onblur="this.style.borderColor='#CDB4DB'">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold tracking-wide mb-2 uppercase" style="color:#5E4B8B;">Kondisi Kesehatan Khusus</label>
                    <textarea name="health_notes" rows="3"
                              placeholder="Contoh: Diabetes, arthritis, atau kondisi medis lainnya"
                              class="w-full rounded-xl px-4 py-3 text-sm focus:outline-none resize-none"
                              style="border:1.5px solid #CDB4DB; background:#FDFAFF;"
                              onfocus="this.style.borderColor='#9F86C0'"
                              onblur="this.style.borderColor='#CDB4DB'"></textarea>
                </div>
            </div>
        </div>

        {{-- BUTTONS --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('hewan-saya') }}"
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
                Simpan Hewan
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
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection