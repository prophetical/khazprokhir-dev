<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penerimaan HCS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <h3 class="text-lg font-medium text-gray-900">Data Receiving</h3>
                        @if(auth()->user()->role === 'sortir')
                            <a href="{{ route('hcs-receiving.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shrink-0">
                                Tambah Data
                            </a>
                        @endif
                    </div>

                    <!-- Search & Filter Form -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
                        <form action="{{ route('hcs-receiving.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                            
                            <!-- Search -->
                            <div class="flex-1">
                                <x-input-label for="search" value="Cari Bon / Batch / Seri" />
                                <x-text-input id="search" name="search" type="text" class="mt-1 block w-full text-sm" value="{{ request('search') }}" placeholder="Ketik kata kunci..." />
                            </div>

                            <!-- Filter: Start Date -->
                            <div>
                                <x-input-label for="start_date" value="Dari Tanggal" />
                                <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full text-sm" value="{{ request('start_date') }}" />
                            </div>

                            <!-- Filter: End Date -->
                            <div>
                                <x-input-label for="end_date" value="Sampai Tanggal" />
                                <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full text-sm" value="{{ request('end_date') }}" />
                            </div>

                            <!-- Filter: Pecahan -->
                            <div>
                                <x-input-label for="pecahan" value="Pecahan" />
                                <select id="pecahan" name="pecahan" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                    <option value="">Semua</option>
                                    <option value="S" {{ request('pecahan') == 'S' ? 'selected' : '' }}>S - 1.000</option>
                                    <option value="T" {{ request('pecahan') == 'T' ? 'selected' : '' }}>T - 2.000</option>
                                    <option value="U" {{ request('pecahan') == 'U' ? 'selected' : '' }}>U - 5.000</option>
                                    <option value="V" {{ request('pecahan') == 'V' ? 'selected' : '' }}>V - 10.000</option>
                                    <option value="W" {{ request('pecahan') == 'W' ? 'selected' : '' }}>W - 20.000</option>
                                    <option value="X" {{ request('pecahan') == 'X' ? 'selected' : '' }}>X - 50.000</option>
                                    <option value="Y" {{ request('pecahan') == 'Y' ? 'selected' : '' }}>Y - 100.000</option>
                                </select>
                            </div>

                            <!-- Filter: Supplier -->
                            <div>
                                <x-input-label for="supplier" value="Supplier" />
                                <select id="supplier" name="supplier" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                    <option value="">Semua</option>
                                    <option value="Cutpack" {{ request('supplier') == 'Cutpack' ? 'selected' : '' }}>Cutpack</option>
                                    <option value="Rikyet" {{ request('supplier') == 'Rikyet' ? 'selected' : '' }}>Rikyet</option>
                                </select>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-end gap-2 pt-2 md:pt-0">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 h-10 w-full md:w-auto mt-2 md:mt-0 justify-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> Cari
                                </button>
                                @if(request()->anyFilled(['search', 'start_date', 'end_date', 'pecahan', 'supplier']))
                                    <a href="{{ route('hcs-receiving.index') }}" class="inline-flex items-center px-4 py-2 bg-red-100 border border-transparent rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest hover:bg-red-200 focus:bg-red-200 active:bg-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 h-10 w-full md:w-auto mt-2 md:mt-0 justify-center" title="Reset Filters">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('hcs-receiving.index', array_merge(request()->query(), ['sort_by' => 'tanggal_penerimaan', 'sort_direction' => request('sort_by') === 'tanggal_penerimaan' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-gray-700">
                                            Tanggal
                                            @if(request('sort_by') === 'tanggal_penerimaan')
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('sort_direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('hcs-receiving.index', array_merge(request()->query(), ['sort_by' => 'nomor_bon', 'sort_direction' => request('sort_by') === 'nomor_bon' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-gray-700">
                                            No Bon
                                            @if(request('sort_by') === 'nomor_bon')
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('sort_direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('hcs-receiving.index', array_merge(request()->query(), ['sort_by' => 'pecahan', 'sort_direction' => request('sort_by') === 'pecahan' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-gray-700">
                                            Pecahan
                                            @if(request('sort_by') === 'pecahan')
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('sort_direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('hcs-receiving.index', array_merge(request()->query(), ['sort_by' => 'jumlah', 'sort_direction' => request('sort_by') === 'jumlah' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-gray-700">
                                            Jumlah
                                            @if(request('sort_by') === 'jumlah')
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('sort_direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('hcs-receiving.index', array_merge(request()->query(), ['sort_by' => 'batch', 'sort_direction' => request('sort_by') === 'batch' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-gray-700">
                                            Batch
                                            @if(request('sort_by') === 'batch')
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('sort_direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('hcs-receiving.index', array_merge(request()->query(), ['sort_by' => 'supplier', 'sort_direction' => request('sort_by') === 'supplier' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-gray-700">
                                            Supplier
                                            @if(request('sort_by') === 'supplier')
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('sort_direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operator</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($receivings->groupBy(function($item) { return $item->batch . ' / ' . $item->seri; }) as $groupKey => $groupItems)
                                    <!-- Group Header Row -->
                                    <tr class="bg-indigo-50 border-t border-b border-indigo-100">
                                        <td colspan="8" class="px-6 py-3 text-sm font-bold text-indigo-900">
                                            Batch / Seri: {{ $groupKey }}
                                        </td>
                                    </tr>
                                    
                                    <!-- Group Data Rows -->
                                    @foreach($groupItems as $receiving)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $receiving->tanggal_penerimaan }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $receiving->nomor_bon }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $receiving->pecahan }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($receiving->jumlah, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">{{ $receiving->batch }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $receiving->supplier === 'Cutpack' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $receiving->supplier }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $receiving->user->name ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                @if(auth()->user()->role === 'sortir')
                                                    <div class="flex justify-center items-center space-x-3">
                                                        <a href="{{ route('hcs-receiving.edit', $receiving->id) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors" title="Edit Data">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </a>
                                                        <form action="{{ route('hcs-receiving.destroy', $receiving->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini beserta seluruh packs dan mengurangi stock ledgernya?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Hapus Data">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">Belum ada data receiving.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $receivings->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
