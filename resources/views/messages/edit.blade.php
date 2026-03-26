<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pesan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" x-data="mentionAutocomplete(@json(\App\Models\User::all(['id', 'name'])))">
                    <form action="{{ route('messages.update', $message) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2 font-bold uppercase tracking-wider">Konten Pesan</label>
                            <div class="relative">
                                <textarea id="content" name="content" x-ref="input" @input="handleInput" @keydown="handleKeydown" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('content', $message->content) }}</textarea>
                                
                                {{-- Dropdown Suggestions --}}
                                <ul x-show="show" x-transition class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-md shadow-lg overflow-hidden py-1 max-h-40 overflow-y-auto">
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
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('messages.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-amber-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-600 active:bg-amber-700 focus:outline-none focus:border-amber-700 focus:ring ring-amber-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
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
                        if (!wordAfterAt.includes(' ')) {
                            this.search = wordAfterAt.toLowerCase();
                            this.filteredUsers = this.users.filter(user => 
                                user.name.toLowerCase().includes(this.search)
                            ).slice(0, 5);
                            
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
                    this.show = false;
                    input.focus();
                    const newPosition = newTextBeforeCursor.length;
                    input.setSelectionRange(newPosition, newPosition);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
