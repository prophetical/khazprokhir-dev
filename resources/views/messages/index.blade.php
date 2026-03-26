<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pesan Antar User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Alert Success --}}
            @if (session('success'))
                <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Form Kirim Pesan --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('messages.store') }}" method="POST">
                        @csrf
                        <div class="mb-4" x-data="mentionAutocomplete(@json($users))">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2 font-bold uppercase tracking-wider">Tulis Pesan Baru</label>
                            <div class="relative">
                                <textarea id="content" name="content" x-ref="input" @input="handleInput" @keydown="handleKeydown" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Apa yang ingin Anda sampaikan?"></textarea>
                                
                                {{-- Dropdown Suggestions --}}
                                <ul x-show="show" x-transition class="absolute z-50 mt-1 w-64 bg-white border border-gray-200 rounded-md shadow-lg overflow-hidden py-1 max-h-40 overflow-y-auto">
                                    <template x-for="(user, index) in filteredUsers" :key="user.id">
                                        <li @click="selectUser(user)" 
                                            :class="{ 'bg-indigo-600 text-white': index === activeIndex, 'text-gray-900': index !== activeIndex }"
                                            class="px-4 py-2 text-sm cursor-pointer hover:bg-indigo-600 hover:text-white transition-colors duration-150">
                                            <span x-text="user.name"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            @error('content')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Kirim Pesan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Daftar Pesan --}}
            <div class="space-y-6">
                @forelse ($messages as $message)
                    <div class="space-y-3">
                        {{-- Parent Message --}}
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 {{ $message->user_id === auth()->id() ? 'border-indigo-500' : 'border-gray-300' }}">
                            <div class="p-6 text-gray-900">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-bold text-indigo-600">{{ $message->user->name }}</span>
                                            <span class="text-xs text-gray-400 font-mono">{{ $message->created_at->diffForHumans() }}</span>
                                            @if($message->created_at != $message->updated_at)
                                                <span class="text-[10px] text-gray-300 italic">(diedit)</span>
                                            @endif
                                        </div>
                                        <div class="mt-2 text-sm text-gray-700 whitespace-pre-wrap">
                                            {!! $message->formatted_content !!}
                                        </div>

                                        {{-- Reply Button --}}
                                        <div class="mt-4" x-data="{ showReply: false }">
                                            <button @click="showReply = !showReply" class="text-xs font-bold text-indigo-500 hover:text-indigo-700 uppercase tracking-tighter flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                                Balas
                                            </button>

                                            {{-- Reply Form --}}
                                            <div x-show="showReply" x-transition class="mt-3 bg-gray-50 p-4 rounded-lg border border-gray-100" x-data="mentionAutocomplete(@json($users))">
                                                <form action="{{ route('messages.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="parent_id" value="{{ $message->id }}">
                                                    <div class="relative">
                                                        <textarea name="content" x-ref="input" @input="handleInput" @keydown="handleKeydown" rows="2" class="w-full text-xs border-gray-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tulis balasan..."></textarea>
                                                        
                                                        {{-- Dropdown Suggestions --}}
                                                        <ul x-show="show" x-transition class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-md shadow-lg overflow-hidden py-1 max-h-32 overflow-y-auto">
                                                            <template x-for="(user, index) in filteredUsers" :key="user.id">
                                                                <li @click="selectUser(user)" 
                                                                    :class="{ 'bg-indigo-600 text-white': index === activeIndex, 'text-gray-900': index !== activeIndex }"
                                                                    class="px-4 py-1.5 text-xs cursor-pointer hover:bg-indigo-600 hover:text-white transition-colors duration-150">
                                                                    <span x-text="user.name"></span>
                                                                </li>
                                                            </template>
                                                        </ul>
                                                    </div>
                                                    <div class="flex justify-end mt-2">
                                                        <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded text-[10px] font-bold uppercase hover:bg-indigo-700">Kirim Balasan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Aksi Edit & Hapus --}}
                                    @if(auth()->user()->role === 'admin' || auth()->id() === $message->user_id)
                                        <div class="flex space-x-2">
                                            <a href="{{ route('messages.edit', $message) }}" class="text-amber-500 hover:text-amber-700 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('messages.destroy', $message) }}" method="POST" class="message-delete-confirm" data-message="Hapus pesan ini beserta semua balasannya?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 transition">
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
                            <div class="ml-12 space-y-3">
                                @foreach($message->replies as $reply)
                                    <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg border-l-2 border-indigo-200">
                                        <div class="p-4 text-gray-900">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="font-bold text-sm text-indigo-600">{{ $reply->user->name }}</span>
                                                        <span class="text-[10px] text-gray-400 font-mono">{{ $reply->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <div class="mt-1 text-xs text-gray-700 whitespace-pre-wrap">
                                                        {!! $reply->formatted_content !!}
                                                    </div>
                                                </div>

                                                {{-- Action for Reply --}}
                                                @if(auth()->user()->role === 'admin' || auth()->id() === $reply->user_id)
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('messages.edit', $reply) }}" class="text-amber-500 hover:text-amber-700 transition">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        </a>
                                                        <form action="{{ route('messages.destroy', $reply) }}" method="POST" class="message-delete-confirm" data-message="Hapus balasan ini?">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 hover:text-red-700 transition">
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
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center text-gray-500">
                            Belum ada pesan yang diposting.
                        </div>
                    </div>
                @endforelse

                <div class="mt-4">
                    {{ $messages->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function mentionAutocomplete(users) {
            return {
                users: users,
                show: false,
                search: '',
                activeIndex: 0,
                filteredUsers: [],
                
                handleInput(e) {
                    const input = this.$refs.input;
                    const cursorPosition = input.selectionStart;
                    const textBeforeCursor = input.value.substring(0, cursorPosition);
                    const lastAtIndex = textBeforeCursor.lastIndexOf('@');
                    
                    if (lastAtIndex !== -1) {
                        const wordAfterAt = textBeforeCursor.substring(lastAtIndex + 1);
                        // Check if there's no space between @ and cursor
                        if (!wordAfterAt.includes(' ')) {
                            this.search = wordAfterAt.toLowerCase();
                            this.filteredUsers = this.users.filter(user => 
                                user.name.toLowerCase().includes(this.search)
                            ).slice(0, 5); // Limit to 5 results
                            
                            if (this.filteredUsers.length > 0) {
                                this.show = true;
                                this.activeIndex = 0;
                                return;
                            }
                        }
                    }
                    this.show = false;
                },
                
                handleKeydown(e) {
                    if (!this.show) return;
                    
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        this.activeIndex = (this.activeIndex + 1) % this.filteredUsers.length;
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        this.activeIndex = (this.activeIndex - 1 + this.filteredUsers.length) % this.filteredUsers.length;
                    } else if (e.key === 'Enter' || e.key === 'Tab') {
                        e.preventDefault();
                        this.selectUser(this.filteredUsers[this.activeIndex]);
                    } else if (e.key === 'Escape') {
                        this.show = false;
                    }
                },
                
                selectUser(user) {
                    const input = this.$refs.input;
                    const cursorPosition = input.selectionStart;
                    const textBeforeCursor = input.value.substring(0, cursorPosition);
                    const textAfterCursor = input.value.substring(cursorPosition);
                    const lastAtIndex = textBeforeCursor.lastIndexOf('@');
                    
                    const newTextBeforeCursor = textBeforeCursor.substring(0, lastAtIndex) + '@' + user.name.split(' ')[0] + ' ';
                    input.value = newTextBeforeCursor + textAfterCursor;
                    
                    // Reset suggestions
                    this.show = false;
                    
                    // Set focus back to input and move cursor
                    input.focus();
                    const newPosition = newTextBeforeCursor.length;
                    input.setSelectionRange(newPosition, newPosition);
                }
            }
        }

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
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
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
