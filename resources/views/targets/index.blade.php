<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Target Tahunan & Bulanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex flex-col">
                            <h3 class="text-lg font-bold text-gray-800">Daftar Target</h3>
                            <p class="text-xs text-gray-500 mt-1">Kelola target tahunan dan bulanan.</p>
                        </div>
                        <a href="{{ route('targets.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:scale-95 transition-all duration-300 shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Target
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg shadow-sm">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-green-700 text-sm font-medium">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-200">
                                    <th class="px-4 py-3 text-left">Pecahan</th>
                                    <th class="px-4 py-3 text-center">Tahun Anggaran</th>
                                    <th class="px-4 py-3 text-center">Tahun Emisi</th>
                                    <th class="px-4 py-3 text-right">Target Tahunan</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($targets as $target)
                                    @php
                                        $colorMap = [
                                            'S' => 'bg-lime-500',
                                            'T' => 'bg-gray-400',
                                            'U' => 'bg-amber-400',
                                            'V' => 'bg-purple-500',
                                            'W' => 'bg-green-500',
                                            'X' => 'bg-blue-500',
                                            'Y' => 'bg-red-500',
                                        ];
                                        $badgeColor = $colorMap[$target->pecahan] ?? 'bg-gray-400';
                                    @endphp
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black text-white {{ $badgeColor }}">
                                                PECAHAN {{ $target->pecahan }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <span class="text-sm font-bold text-gray-700">{{ $target->tahun_anggaran }}</span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center text-sm text-gray-500 font-mono">
                                            TE {{ $target->tahun_emisi }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-black text-emerald-600">
                                            {{ number_format($target->target, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('targets.edit', $target->id) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit Target">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </a>
                                                <form id="delete-form-{{ $target->id }}" action="{{ route('targets.destroy', $target->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" @click="confirmDelete('{{ $target->id }}')" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Target">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v2m3 4h.01"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center opacity-40">
                                                <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                                                <h3 class="text-sm font-medium text-gray-900">Belum ada data target</h3>
                                                <p class="mt-1 text-sm text-gray-500">Klik "Tambah Target" untuk mulai mengelola target tahunan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $targets->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Data Target?',
                text: "Data target tahunan dan bulanan terkait akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'font-bold uppercase tracking-widest text-xs px-6 py-2.5 rounded-lg shadow-md',
                    cancelButton: 'font-bold uppercase tracking-widest text-xs px-6 py-2.5 rounded-lg shadow-sm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
    @endpush
</x-app-layout>
