@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="space-y-6">

    @if(session('success'))
    <div class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium"
         style="background:#f0fdf4; border:1.5px solid #bbf7d0; color:#15803d;">
        <span class="flex items-center gap-2">
            <i class="ti ti-circle-check" style="font-size:18px;"></i>
            {{ session('success') }}
        </span>
        <button onclick="this.parentElement.remove()" style="color:#15803d;">
            <i class="ti ti-x" style="font-size:16px;"></i>
        </button>
    </div>
    @endif

    {{-- COVER BANNER --}}
    <div class="relative rounded-2xl overflow-hidden"
         style="background:linear-gradient(135deg,#2D1B69 0%,#5E4B8B 50%,#9F86C0 100%); min-height:150px;">

        {{-- Dot Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <svg width="100%" height="100%">
                <defs>
                    <pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1.5" fill="white"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#dots)"/>
            </svg>
        </div>

        {{-- Edit Button --}}
        @if($isOwnProfile)
        <div class="relative p-6 flex justify-end">
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold text-white transition"
               style="background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.3);"
               onmouseover="this.style.background='rgba(255,255,255,0.25)'"
               onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                <i class="ti ti-edit" style="font-size:16px;"></i>
                Edit Profil
            </a>
        </div>
        @endif
    </div>

    {{-- PROFILE HEADER --}}
    <div class="relative px-2 pb-2">

        {{-- Avatar --}}
        <div class="-mt-14 lg:-mt-20 mb-4 relative z-10">
            <div class="w-24 h-24 lg:w-32 lg:h-32 rounded-full overflow-hidden flex-shrink-0"
                 style="border:4px solid white; box-shadow:0 4px 20px rgba(159,134,192,0.3); background:#EDE4F5;">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="ti ti-user" style="font-size:48px; color:#CDB4DB;"></i>
                    </div>
                @endif
            </div>
        </div>

        {{-- User Info --}}
        <div class="mb-6">
            <div class="flex flex-wrap items-center gap-3 mb-2">
                <h1 class="text-2xl lg:text-3xl font-bold" style="color:#2D1B69;">{{ $user->name }}</h1>

                @if($settings && $settings->show_online_status)
                <span class="flex items-center gap-1.5 text-xs px-3 py-1 rounded-full font-medium"
                      style="background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;">
                    <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#22c55e;"></span>
                    Online
                </span>
                @endif
            </div>

            @if($isOwnProfile || ($settings && $settings->show_email))
            <p class="text-sm mb-3" style="color:#9ca3af;">{{ $user->email }}</p>
            @endif

            <div class="flex flex-wrap items-center gap-3 text-sm" style="color:#9ca3af;">
                @if($user->address)
                <span class="flex items-center gap-1">
                    <i class="ti ti-map-pin" style="font-size:15px; color:#9F86C0;"></i>
                    {{ $user->address }}
                </span>
                <span style="color:#CDB4DB;">•</span>
                @endif
                <span class="flex items-center gap-1">
                    <i class="ti ti-calendar" style="font-size:15px; color:#9F86C0;"></i>
                    Bergabung {{ $user->created_at->format('F Y') }}
                </span>
            </div>
        </div>

        {{-- STATS --}}
            <div class="grid grid-cols-3 gap-2 lg:gap-4">
                <div class="bg-white rounded-2xl p-3 lg:p-5 text-center"
                     style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
                    <div class="text-xl lg:text-3xl font-bold" style="color:#2D1B69;">{{ $stats['total_posts'] }}</div>
                    <div class="text-xs lg:text-sm mt-1" style="color:#9ca3af;">Postingan</div>
                </div>
                <div class="bg-white rounded-2xl p-3 lg:p-5 text-center"
                     style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
                    <div class="text-xl lg:text-3xl font-bold" style="color:#2D1B69;">{{ $stats['total_replies'] }}</div>
                    <div class="text-xs lg:text-sm mt-1" style="color:#9ca3af;">Balasan</div>
                </div>
                <div class="bg-white rounded-2xl p-3 lg:p-5 text-center"
                     style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">
                    <div class="text-xl lg:text-3xl font-bold" style="color:#2D1B69;">{{ $stats['total_likes'] }}</div>
                    <div class="text-xs lg:text-sm mt-1" style="color:#9ca3af;">Likes</div>
                </div>
            </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">

        {{-- LEFT: HEWAN PELIHARAAN --}}
        <div>
            @php $pets = $user->pets ?? collect(); @endphp

            <div class="bg-white rounded-2xl overflow-hidden"
                 style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">

                <div class="px-5 py-4 flex items-center gap-3"
                     style="border-bottom:1.5px solid #EDE4F5; background:linear-gradient(135deg,#EDE4F5,#F5F0FA);">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center"
                         style="background:#9F86C0;">
                        <i class="ti ti-paw text-white" style="font-size:16px;"></i>
                    </div>
                    <h3 class="font-bold" style="color:#2D1B69;">Hewan Peliharaan</h3>
                </div>

                @if($pets->count() > 0)
                <div class="p-4 space-y-3">
                    @foreach($pets as $pet)
                    <a href="{{ route('pets.show', $pet->id) }}"
                       class="flex items-center gap-3 p-3 rounded-xl transition"
                       style="background:#F5F0FA;"
                       onmouseover="this.style.background='#EDE4F5'"
                       onmouseout="this.style.background='#F5F0FA'">
                        <div class="w-11 h-11 rounded-full overflow-hidden flex-shrink-0 flex items-center justify-center"
                             style="background:#EDE4F5; border:1.5px solid #CDB4DB;">
                            @if($pet->photo)
                                <img src="{{ asset('storage/' . $pet->photo) }}" class="w-full h-full object-cover">
                            @else
                                <i class="ti ti-paw" style="font-size:18px; color:#9F86C0;"></i>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-semibold" style="color:#2D1B69;">{{ $pet->name }}</p>
                            <p class="text-xs" style="color:#9ca3af;">
                                {{ $pet->species }}{{ $pet->breed ? ' · ' . $pet->breed : '' }}
                            </p>
                        </div>
                    </a>
                    @endforeach

                    @if($isOwnProfile)
                    <a href="{{ route('pets.create') }}"
                       class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-semibold transition"
                       style="border:1.5px dashed #CDB4DB; color:#9F86C0;"
                       onmouseover="this.style.background='#EDE4F5'"
                       onmouseout="this.style.background=''">
                        <i class="ti ti-plus" style="font-size:15px;"></i>
                        Tambah Hewan
                    </a>
                    @endif
                </div>

                @else
                <div class="p-8 text-center">
                    <div class="w-14 h-14 rounded-full mx-auto mb-3 flex items-center justify-center"
                         style="background:#EDE4F5;">
                        <i class="ti ti-paw" style="font-size:26px; color:#CDB4DB;"></i>
                    </div>
                    <p class="text-sm font-medium mb-1" style="color:#5E4B8B;">Belum ada hewan peliharaan</p>
                    @if($isOwnProfile)
                    <a href="{{ route('pets.create') }}"
                       class="inline-flex items-center gap-2 mt-3 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition"
                       style="background:#9F86C0;"
                       onmouseover="this.style.background='#5E4B8B'"
                       onmouseout="this.style.background='#9F86C0'">
                        <i class="ti ti-plus" style="font-size:14px;"></i>
                        Tambah Hewan
                    </a>
                    @endif
                </div>
                @endif

            </div>
        </div>

        {{-- RIGHT: KONTEN TAB --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl overflow-hidden"
                 style="border:1.5px solid #EDE4F5; box-shadow:0 2px 12px rgba(159,134,192,0.08);">

                {{-- TAB NAVIGATION --}}
                <div class="flex" style="border-bottom:1.5px solid #EDE4F5;">
                    @foreach(['postingan' => 'Postingan', 'balasan' => 'Balasan', 'suka' => 'Disukai'] as $key => $label)
                    <a href="{{ request()->url() }}?tab={{ $key }}"
                       class="flex-1 text-center py-4 text-sm font-semibold transition"
                       style="{{ $tab === $key
                           ? 'color:#9F86C0; border-bottom:2px solid #9F86C0; background:#FDFAFF;'
                           : 'color:#9ca3af;' }}"
                       onmouseover="this.style.background='#F5F0FA'"
                       onmouseout="this.style.background='{{ $tab === $key ? '#FDFAFF' : '' }}'">
                        {{ $label }}
                    </a>
                    @endforeach
                </div>

                {{-- TAB CONTENT --}}
                <div style="divide-y: 1px solid #EDE4F5;">
                    @forelse($content as $item)
                    <div class="p-5 transition"
                         style="border-bottom:1px solid #EDE4F5;"
                         onmouseover="this.style.background='#FDFAFF'"
                         onmouseout="this.style.background=''">

                        @if($item->title)
                        <h4 class="font-bold mb-2" style="color:#2D1B69;">{{ $item->title }}</h4>
                        @endif

                        <p class="text-sm leading-relaxed mb-3" style="color:#5E4B8B;">
                            {{ Str::limit($item->content, 180) }}
                        </p>

                        @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}"
                             class="w-full max-h-60 object-cover rounded-xl mb-3"
                             style="border:1.5px solid #EDE4F5;">
                        @endif

                        <div class="flex items-center gap-5 text-xs" style="color:#9ca3af;">
                            <span class="flex items-center gap-1">
                                <i class="ti ti-heart" style="font-size:14px; color:#9F86C0;"></i>
                                {{ $item->likes->count() }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="ti ti-message-circle" style="font-size:14px; color:#9F86C0;"></i>
                                {{ $item->replies->count() }}
                            </span>
                            <span class="ml-auto">{{ $item->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    @empty
                    <div class="py-20 text-center">
                        <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center"
                             style="background:#EDE4F5;">
                            <i class="ti ti-message-off" style="font-size:28px; color:#CDB4DB;"></i>
                        </div>
                        <p class="font-semibold" style="color:#5E4B8B;">Belum ada konten</p>
                    </div>
                    @endforelse
                </div>

                {{-- PAGINATION --}}
                @if($content instanceof \Illuminate\Pagination\LengthAwarePaginator && $content->hasPages())
                <div class="p-4" style="border-top:1.5px solid #EDE4F5;">
                    {{ $content->appends(['tab' => $tab])->links() }}
                </div>
                @endif

            </div>
        </div>

    </div>

</div>
@endsection