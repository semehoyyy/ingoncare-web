@extends('layouts.app')

@section('title', 'Bantuan & Dukungan')

@section('content')
<div>

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('settings.index') }}"
           class="w-10 h-10 rounded-full flex items-center justify-center transition"
           style="background:#EDE4F5;"
           onmouseover="this.style.background='#CDB4DB'"
           onmouseout="this.style.background='#EDE4F5'">
            <i class="ti ti-arrow-left" style="font-size:18px; color:#5E4B8B;" aria-hidden="true"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold" style="color:#2D1B69;">Bantuan & Dukungan</h1>
            <p class="text-sm mt-0.5" style="color:#9ca3af;">Dapatkan bantuan dan dukungan</p>
        </div>
    </div>

    <div class="space-y-6">

        {{-- Pusat Bantuan --}}
        <div class="bg-white rounded-2xl overflow-hidden"
             style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <div class="px-5 py-4 flex items-center gap-2"
                 style="background:linear-gradient(135deg,#EDE4F5,#CDB4DB); border-bottom:1.5px solid #CDB4DB;">
                <i class="ti ti-book" style="font-size:18px; color:#5E4B8B;" aria-hidden="true"></i>
                <h2 class="font-bold" style="color:#5E4B8B;">Pusat Bantuan</h2>
            </div>

            <div class="p-4 space-y-2">

                <a href="#"
                   class="flex items-center justify-between p-4 rounded-xl transition"
                   style="border:1.5px solid #EDE4F5;"
                   onmouseover="this.style.background='#FDFAFF'; this.style.borderColor='#CDB4DB'"
                   onmouseout="this.style.background=''; this.style.borderColor='#EDE4F5'">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                             style="background:#EDE4F5;">
                            <i class="ti ti-help-circle" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-sm" style="color:#2D1B69;">FAQ</h4>
                            <p class="text-xs mt-0.5" style="color:#9ca3af;">Pertanyaan yang sering diajukan</p>
                        </div>
                    </div>
                    <i class="ti ti-chevron-right" style="font-size:16px; color:#CDB4DB;"></i>
                </a>

                <a href="#"
                   class="flex items-center justify-between p-4 rounded-xl transition"
                   style="border:1.5px solid #EDE4F5;"
                   onmouseover="this.style.background='#FDFAFF'; this.style.borderColor='#CDB4DB'"
                   onmouseout="this.style.background=''; this.style.borderColor='#EDE4F5'">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                             style="background:#EDE4F5;">
                            <i class="ti ti-book-2" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-sm" style="color:#2D1B69;">Panduan Pengguna</h4>
                            <p class="text-xs mt-0.5" style="color:#9ca3af;">Tutorial lengkap penggunaan aplikasi</p>
                        </div>
                    </div>
                    <i class="ti ti-chevron-right" style="font-size:16px; color:#CDB4DB;"></i>
                </a>

                <a href="#"
                   class="flex items-center justify-between p-4 rounded-xl transition"
                   style="border:1.5px solid #EDE4F5;"
                   onmouseover="this.style.background='#FDFAFF'; this.style.borderColor='#CDB4DB'"
                   onmouseout="this.style.background=''; this.style.borderColor='#EDE4F5'">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                             style="background:#EDE4F5;">
                            <i class="ti ti-video" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-sm" style="color:#2D1B69;">Video Tutorial</h4>
                            <p class="text-xs mt-0.5" style="color:#9ca3af;">Tonton video panduan</p>
                        </div>
                    </div>
                    <i class="ti ti-chevron-right" style="font-size:16px; color:#CDB4DB;"></i>
                </a>

            </div>
        </div>

        {{-- Hubungi Kami --}}
        <div class="bg-white rounded-2xl overflow-hidden"
             style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
            <div class="px-5 py-4 flex items-center gap-2"
                 style="background:linear-gradient(135deg,#EDE4F5,#CDB4DB); border-bottom:1.5px solid #CDB4DB;">
                <i class="ti ti-message-dots" style="font-size:18px; color:#5E4B8B;" aria-hidden="true"></i>
                <h2 class="font-bold" style="color:#5E4B8B;">Hubungi Kami</h2>
            </div>

            <div class="p-4 space-y-2">

                {{-- Tombol Email --}}
                <a href="mailto:ingoncare@gmail.com"
                   class="flex items-center gap-3 p-4 rounded-xl transition"
                   style="border:1.5px solid #EDE4F5;"
                   onmouseover="this.style.background='#FDFAFF'; this.style.borderColor='#CDB4DB'"
                   onmouseout="this.style.background=''; this.style.borderColor='#EDE4F5'">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                         style="background:#EDE4F5;">
                        <i class="ti ti-mail" style="font-size:16px; color:#9F86C0;" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-sm" style="color:#2D1B69;">Email</h4>
                        <p class="text-xs mt-0.5" style="color:#9F86C0;">ingoncare@gmail.com</p>
                    </div>
                </a>

            </div>
        </div>

    </div>
</div>
@endsection