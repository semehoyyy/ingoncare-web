@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="min-h-screen">

    {{-- Success Message --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center justify-between">
        <span class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </span>
        <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">✕</button>
    </div>
    @endif

    {{-- Header Card --}}
    <div class="bg-gradient-to-r from-cyan-500 to-blue-600 rounded-2xl shadow-lg overflow-hidden mb-6">
        <div class="p-8 relative">
            <div class="flex items-center gap-6">
                
                {{-- Profile Photo --}}
                <div class="relative">
                    <div class="w-28 h-28 rounded-full border-4 border-white shadow-lg overflow-hidden bg-white">
                        @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 text-4xl">👤</div>
                        @endif
                    </div>
                    <div class="absolute bottom-0 right-0 w-8 h-8 bg-green-400 rounded-full border-4 border-white"></div>
                </div>

                {{-- User Info --}}
                <div class="flex-1 text-white">
                    <h1 class="text-3xl font-bold mb-2">{{ $user->name }}</h1>
                    <div class="flex items-center gap-4 text-sm">
                        <span>{{ $user->address ?? 'Bandung' }}</span>
                        <span>• Bergabung {{ $user->created_at->format('F Y') }}</span>
                    </div>
                </div>

                @if($isOwnProfile)
                <a href="{{ route('profile.edit') }}"
                   class="absolute top-6 right-6 bg-white text-cyan-600 px-4 py-2 rounded-xl font-semibold hover:bg-cyan-50">
                    Edit Profil
                </a>
                @endif

            </div>
        </div>
    </div>

    {{-- Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT COLUMN --}}
        <div class="space-y-6">

            {{-- Personal Information --}}
            <div class="bg-white rounded-2xl shadow-sm border">
                <div class="bg-cyan-50 px-5 py-4 border-b">
                    <h3 class="font-bold text-lg">👤 Informasi Pribadi</h3>
                </div>
                <div class="p-5 space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Nama</p>
                        <p class="font-semibold">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-semibold">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Telepon</p>
                        <p class="font-semibold">{{ $user->phone ?? '-' }}</p>
                    </div>
                    @if($user->address)
                    <div>
                        <p class="text-sm text-gray-500">Alamat</p>
                        <p class="font-semibold">{{ $user->address }}</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                {{-- TAB CONTENT TIDAK DIUBAH --}}
                {{-- (punyamu tetap aman di sini) --}}
            </div>
        </div>

    </div>

</div>
@endsection