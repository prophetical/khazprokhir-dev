<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pesan Antar User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Alert Success --}}
            @if (session('success'))
                <div class="mb-6 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-400 px-5 py-4 rounded-xl shadow-sm relative flex items-center justify-between" role="alert" x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-emerald-500 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="block sm:inline font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 focus:outline-none transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            {{-- Form Kirim Pesan --}}
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-lg sm:rounded-2xl mb-8 border border-gray-100 dark:border-gray-700/50 transition-all duration-300">
                <div class="p-6 md:p-8">
                    <form action="{{ route('messages.store') }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label for="content" class="flex items-center text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wider relative">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Tulis Pesan Baru
                            </label>
                            <div class="relative group">
                                <textarea id="content" name="content" rows="3" class="w-full text-sm placeholder-gray-400 dark:placeholder-gray-500 bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 dark:focus:ring-indigo-400/50 dark:focus:border-indigo-400 transition-all duration-300 shadow-sm" placeholder="Apa yang ingin Anda sampaikan hari ini?"></textarea>
                                <div class="absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/5 group-hover:ring-gray-900/10 dark:ring-white/5 dark:group-hover:ring-white/10 pointer-events-none transition-all"></div>
                            </div>
                            @error('content')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-2 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-500 dark:to-purple-500 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:from-indigo-700 hover:to-purple-700 dark:hover:from-indigo-400 dark:hover:to-purple-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 shadow-md shadow-indigo-500/30 dark:shadow-indigo-500/20 transform hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                Kirim Pesan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Daftar Pesan --}}
            <div class="space-y-6">
                @forelse ($messages as $message)
                    <div class="space-y-4">
                        {{-- Parent Message --}}
                        <div x-data="{ showReply: false, isEditing: false }" class="bg-white dark:bg-gray-800 shadow-sm hover:shadow-md sm:rounded-2xl border-l-4 {{ $message->user_id === auth()->id() ? 'border-l-indigo-500 dark:border-l-indigo-400' : 'border-l-transparent dark:border-l-transparent' }} ring-1 ring-gray-200/50 dark:ring-gray-700/50 transition-all duration-300 relative group overflow-hidden">
                            {{-- Decorative gradient background --}}
                            @if($message->user_id === auth()->id())
                                <div class="absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-indigo-50 to-transparent dark:from-indigo-500/5 dark:to-transparent pointer-events-none"></div>
                            @endif

                            <div class="p-6 md:p-7 text-gray-900 dark:text-gray-100 relative z-10">
                                <div class="flex justify-between items-start gap-4">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <div class="flex items-center space-x-2">
                                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-xs ring-2 ring-white dark:ring-gray-800 shadow-sm">
                                                    {{ substr($message->user->name, 0, 2) }}
                                                </div>
                                                <span class="font-bold text-gray-900 dark:text-white truncate max-w-[200px]">{{ $message->user->name }}</span>
                                            </div>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $message->created_at->diffForHumans() }}
                                            </span>
                                            @if($message->created_at != $message->updated_at)
                                                <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-[10px] text-gray-500 dark:text-gray-400 font-medium tracking-wide">diedit</span>
                                            @endif
                                        </div>
                                        <div x-show="!isEditing" class="mt-3 text-sm md:text-base text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed text-left">{{ $message->content }}</div>

                                        {{-- Edit Form --}}
                                        <div x-show="isEditing" x-transition x-cloak class="mt-3" style="display: none;">
                                            <form action="{{ route('messages.update', $message) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <textarea name="content" rows="3" class="w-full text-sm bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-shadow">{{ $message->content }}</textarea>
                                                <div class="flex justify-end gap-2 mt-2">
                                                    <button type="button" @click="isEditing = false" class="px-4 py-1.5 text-xs font-bold text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">Batal</button>
                                                    <button type="submit" class="px-4 py-1.5 text-xs font-bold text-white bg-gradient-to-r from-amber-500 to-orange-500 rounded-lg hover:from-amber-600 hover:to-orange-600 shadow-sm transition-all transform hover:-translate-y-px">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>

                                        {{-- Reply Button --}}
                                        <div class="mt-5" x-show="!isEditing">
                                            <button @click="showReply = !showReply" class="inline-flex items-center text-xs font-semibold text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 uppercase tracking-widest transition-colors duration-200 group-hover:text-indigo-500">
                                                <svg class="w-4 h-4 mr-1.5 transition-transform duration-300" :class="{ 'rotate-180': showReply }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                                Balas Percakapan
                                            </button>

                                            {{-- Reply Form --}}
                                            <div x-show="showReply" 
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 translate-y-2"
                                                 x-transition:enter-end="opacity-100 translate-y-0"
                                                 class="mt-4 bg-gray-50/80 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 shadow-inner">
                                                <form action="{{ route('messages.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="parent_id" value="{{ $message->id }}">
                                                    <div class="relative">
                                                        <textarea name="content" rows="2" class="w-full text-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 dark:focus:ring-indigo-400/50 transition-shadow" placeholder="Tulis balasan Anda untuk {{ $message->user->name }}..."></textarea>
                                                    </div>
                                                    <div class="flex justify-end mt-3">
                                                        <button type="submit" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors shadow-sm">
                                                            Kirim Balasan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Aksi Edit & Hapus --}}
                                    @if(auth()->user()->role === 'admin' || auth()->id() === $message->user_id)
                                        <div x-show="!isEditing" class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-gray-50 dark:bg-gray-700/50 p-1.5 rounded-lg border border-gray-100 dark:border-gray-600">
                                            <button type="button" @click="isEditing = true" class="p-1.5 text-amber-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10 rounded-md transition-colors tooltip-trigger" title="Edit Pesan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <div class="w-px h-4 bg-gray-200 dark:bg-gray-600"></div>
                                            <form action="{{ route('messages.destroy', $message) }}" method="POST" class="message-delete-confirm" data-message="Hapus pesan ini beserta semua balasannya?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-md transition-colors tooltip-trigger" title="Hapus Pesan">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Replies --}}
                        @if($message->replies->count() > 0)
                            <div class="pl-6 md:pl-12 space-y-4 relative">
                                <!-- Connector Thread Line -->
                                <div class="absolute top-0 bottom-6 left-6 md:left-10 w-px bg-gray-200 dark:bg-gray-700"></div>
                                
                                @foreach($message->replies as $reply)
                                    <div x-data="{ isEditing: false }" class="relative bg-gray-50 dark:bg-gray-700 overflow-hidden shadow-sm hover:shadow-md sm:rounded-2xl border border-gray-100 dark:border-gray-600 ring-1 ring-black/5 dark:ring-white/5 transition-all duration-300 group ml-6">
                                        <!-- Thread Horizontal Connector -->
                                        <div class="absolute top-8 -left-6 w-6 h-px bg-gray-200 dark:bg-gray-600"></div>
                                        
                                        <div class="p-5 text-gray-900 dark:text-gray-100">
                                            <div class="flex justify-between items-start gap-4">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                                        <div class="flex items-center space-x-2">
                                                            <div class="h-6 w-6 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 flex items-center justify-center font-bold text-[10px] ring-1 ring-indigo-200 dark:ring-indigo-700">
                                                                {{ substr($reply->user->name, 0, 2) }}
                                                            </div>
                                                            <span class="font-bold text-sm text-gray-800 dark:text-gray-200 truncate">{{ $reply->user->name }}</span>
                                                        </div>
                                                        <span class="text-[11px] text-gray-500 dark:text-gray-400 flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            {{ $reply->created_at->diffForHumans() }}
                                                        </span>
                                                        @if($reply->created_at != $reply->updated_at)
                                                            <span class="px-1.5 py-0.5 rounded text-[9px] bg-gray-200/50 dark:bg-gray-600 text-gray-500 dark:text-gray-300 font-medium">diedit</span>
                                                        @endif
                                                    </div>
                                                    <div x-show="!isEditing" class="mt-2 text-sm text-gray-700 dark:text-gray-200 whitespace-pre-wrap leading-relaxed text-left">{{ $reply->content }}</div>

                                                    {{-- Edit Form Reply --}}
                                                    <div x-show="isEditing" x-transition x-cloak class="mt-3" style="display: none;">
                                                        <form action="{{ route('messages.update', $reply) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <textarea name="content" rows="2" class="w-full text-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-shadow">{{ $reply->content }}</textarea>
                                                            <div class="flex justify-end gap-2 mt-2">
                                                                <button type="button" @click="isEditing = false" class="px-3 py-1 text-xs font-bold text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500 transition-colors">Batal</button>
                                                                <button type="submit" class="px-3 py-1 text-xs font-bold text-white bg-gradient-to-r from-amber-500 to-orange-500 rounded-lg hover:from-amber-600 hover:to-orange-600 shadow-sm transition-all transform hover:-translate-y-px">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                {{-- Action for Reply --}}
                                                @if(auth()->user()->role === 'admin' || auth()->id() === $reply->user_id)
                                                    <div x-show="!isEditing" class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-white dark:bg-gray-700/80 p-1.5 rounded-lg border border-gray-100 dark:border-gray-600 shadow-sm">
                                                        <button type="button" @click="isEditing = true" class="p-1 text-amber-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10 rounded transition-colors" title="Edit Balasan">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        </button>
                                                        <div class="w-px h-3 bg-gray-200 dark:bg-gray-600"></div>
                                                        <form action="{{ route('messages.destroy', $reply) }}" method="POST" class="message-delete-confirm" data-message="Hapus balasan ini secara permanen?">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="p-1 text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 rounded transition-colors" title="Hapus Balasan">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-lg sm:rounded-2xl border border-gray-100 dark:border-gray-700/50">
                        <div class="p-12 flex flex-col items-center justify-center text-center">
                            <div class="w-20 h-20 bg-indigo-50 dark:bg-indigo-900/20 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-indigo-300 dark:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Belum Ada Pesan</h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-sm">Jadilah yang pertama memulai percakapan! Gunakan form di atas untuk menulis pesan baru.</p>
                        </div>
                    </div>
                @endforelse

                <div class="mt-8">
                    {{ $messages->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.message-delete-confirm');
            
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const message = this.getAttribute('data-message') || 'Apakah Anda yakin?';
                    
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: message,
                        icon: 'warning',
                        background: 'transparent',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100',
                            title: 'text-gray-900 dark:text-gray-100',
                            htmlContainer: 'text-gray-600 dark:text-gray-300'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
