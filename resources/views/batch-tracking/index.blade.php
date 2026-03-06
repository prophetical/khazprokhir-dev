<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Batch Tracking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Search Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('batch-tracking.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                        <div>
                            <x-input-label for="batch" value="Batch (7 digits)" />
                            <x-text-input id="batch" name="batch" type="text" class="mt-1 block w-full uppercase font-mono" value="{{ request('batch') }}" required maxlength="7" />
                        </div>
                        <div>
                            <x-input-label for="seri" value="Seri (Format: AA-BB7)" />
                            <x-text-input id="seri" name="seri" type="text" class="mt-1 block w-full uppercase font-mono" value="{{ request('seri') }}" required />
                        </div>
                        <div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cari Data
                            </button>
                            <a href="{{ route('batch-tracking.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 ml-2">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            @if(request('batch') && request('seri'))
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Tracking Summary -->
                    <div class="md:col-span-1 border rounded-lg bg-white overflow-hidden shadow-sm">
                        <div class="bg-gray-50 px-4 py-3 border-b">
                            <h3 class="text-sm font-semibold text-gray-700">Summary Batch / Seri</h3>
                        </div>
                        <div class="p-4 space-y-3">
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-sm text-gray-500">Batch</span>
                                <span class="text-sm font-mono font-bold">{{ strtoupper($batch) }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-sm text-gray-500">Seri</span>
                                <span class="text-sm font-mono font-bold">{{ strtoupper($seri) }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-sm text-gray-500">Total Packs Digunakan</span>
                                <span class="text-sm font-bold text-indigo-600">{{ $packs->count() }} packs</span>
                            </div>
                            
                            <div class="pt-2">
                                <span class="text-sm block text-gray-500 mb-2">Supplier Distribution:</span>
                                <div class="flex space-x-2">
                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">Cutpack: {{ $supplierDistribution['Cutpack'] ?? 0 }}</span>
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Rikyet: {{ $supplierDistribution['Rikyet'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Packs List -->
                    <div class="md:col-span-2 border rounded-lg bg-white overflow-hidden shadow-sm">
                        <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
                            <h3 class="text-sm font-semibold text-gray-700">Daftar Packs Digunakan</h3>
                        </div>
                        <div class="p-4">
                            @if($packs->count() > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach($packs as $pack)
                                        <div class="px-3 py-2 text-sm border rounded-md {{ $pack->supplier === 'Cutpack' ? 'bg-blue-50 border-blue-200' : 'bg-green-50 border-green-200' }}" title="No Bon: {{ $pack->hcsReceiving->nomor_bon }}">
                                            <div class="font-bold text-center">Pack {{ $pack->pack_number }}</div>
                                            <div class="text-xs text-gray-500 text-center">{{ \Carbon\Carbon::parse($pack->created_at)->format('d/m/Y') }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 py-4 text-center">Tidak ada packs yang ditemukan untuk Batch dan Seri ini.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- History Records -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Riwayat Penerimaan HCS</h3>
                    </div>
                    <div class="p-0">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Bon</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pecahan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gilir</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operator</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($history as $record)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record->tanggal_penerimaan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $record->nomor_bon }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($record->jumlah, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record->pecahan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record->gilir }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record->user->name ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">Belum ada riwayat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
