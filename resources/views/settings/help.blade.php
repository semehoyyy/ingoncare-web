@extends('layouts.app')

@section('title', 'Bantuan & Dukungan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('settings.index') }}" 
               class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Bantuan & Dukungan</h1>
                <p class="text-sm text-gray-500">Dapatkan bantuan dan dukungan</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <span class="text-2xl">📚</span>
                Pusat Bantuan
            </h3>
        </div>

        <div class="p-6 space-y-3">
            <a href="#" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition border border-gray-200 group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">FAQ</h4>
                        <p class="text-sm text-gray-500">Pertanyaan yang sering diajukan</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-cyan-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="#" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition border border-gray-200 group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Panduan Pengguna</h4>
                        <p class="text-sm text-gray-500">Tutorial lengkap penggunaan aplikasi</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-cyan-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="#" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition border border-gray-200 group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Video Tutorial</h4>
                        <p class="text-sm text-gray-500">Tonton video panduan</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-cyan-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-cyan-50 to-blue-50">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <span class="text-2xl">💬</span>
                Hubungi Kami
            </h3>
        </div>

        <div class="p-6 space-y-3">
            <a href="mailto:support@ingoncare.com" class="flex items-center gap-3 p-4 hover:bg-gray-50 rounded-xl transition border border-gray-200">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900">Email</h4>
                    <p class="text-sm text-cyan-600">support@ingoncare.com</p>
                </div>
            </a>

            <a href="https://wa.me/6281234567890" class="flex items-center gap-3 p-4 hover:bg-gray-50 rounded-xl transition border border-gray-200">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900">WhatsApp</h4>
                    <p class="text-sm text-cyan-600">+62 812-3456-7890</p>
                </div>
            </a>
        </div>
    </div>

    <div class="bg-gradient-to-r from-cyan-500 to-blue-600 rounded-2xl p-6 text-white text-center">
        <h3 class="text-xl font-bold mb-2">Butuh Bantuan Lebih Lanjut?</h3>
        <p class="mb-4 text-cyan-100">Tim kami siap membantu anda 24/7</p>
        <button class="px-6 py-3 bg-white text-cyan-600 rounded-xl font-semibold hover:bg-cyan-50 transition shadow">
            Chat dengan Tim Support
        </button>
    </div>
</div>
@endsection