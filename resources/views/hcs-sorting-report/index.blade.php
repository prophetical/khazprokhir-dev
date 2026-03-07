<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Penyortiran HCS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 border-l-4 border-indigo-600 pl-2 mb-6">Filter Laporan</h3>
                    
                    <form method="GET" action="{{ route('hcs-sorting-reports.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-4 items-end bg-gray-50 p-4 rounded-md border border-gray-100">
                        <div>
                            <x-input-label for="tanggal_dari" :value="__('Tanggal Dari')" />
                            <x-text-input id="tanggal_dari" class="block mt-1 w-full text-sm" type="date" name="tanggal_dari" :value="request('tanggal_dari')" />
                        </div>
                        <div>
                            <x-input-label for="tanggal_sampai" :value="__('Tanggal Sampai')" />
                            <x-text-input id="tanggal_sampai" class="block mt-1 w-full text-sm" type="date" name="tanggal_sampai" :value="request('tanggal_sampai')" />
                        </div>
                        <div>
                            <x-input-label for="batch" :value="__('Batch')" />
                            <x-text-input id="batch" class="block mt-1 w-full text-sm" type="text" name="batch" :value="request('batch')" placeholder="Cari Batch" />
                        </div>
                        <div>
                            <x-input-label for="seri" :value="__('Seri')" />
                            <x-text-input id="seri" class="block mt-1 w-full text-sm" type="text" name="seri" :value="request('seri')" placeholder="Cari Seri" />
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Filter
                            </button>
                            <a href="{{ route('hcs-sorting-reports.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 text-center">
                                Reset
                            </a>
                        </div>
                    </form>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto rounded-lg border border-gray-200 mt-4">
                        <table id="reportsTable" class="min-w-full divide-y divide-gray-200 stripe hover" style="width:100%">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gilir</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seri</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Pch</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pack Terpilih</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Jml Pack</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Total Bilyet</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                                    <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $colorMap = [
                                        'S' => 'bg-lime-200 border-lime-400 text-lime-800',       // 1k Kuning Kehijauan
                                        'T' => 'bg-gray-200 border-gray-400 text-gray-800',       // 2k Abu-abu
                                        'U' => 'bg-amber-100 border-amber-300 text-amber-800',    // 5k Cokelat Kekuningan
                                        'V' => 'bg-purple-100 border-purple-300 text-purple-800',   // 10k Ungu
                                        'W' => 'bg-green-100 border-green-300 text-green-800',    // 20k Hijau
                                        'X' => 'bg-blue-100 border-blue-300 text-blue-800',      // 50k Biru
                                        'Y' => 'bg-red-100 border-red-300 text-red-800',       // 100k Merah
                                    ];
                                @endphp
                                @forelse ($reports as $report)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900" data-sort="{{ $report->tanggal_sortir->format('Y-m-d') }}">{{ $report->tanggal_sortir->format('d/m/Y') }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->gilir }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $report->batch }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->seri }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $colorMap[$report->pecahan] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $report->pecahan }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->supplier }}</td>
                                        <td class="px-3 py-4 text-sm text-gray-500 max-w-xs truncate" title="{{ implode(', ', $report->packs_selected) }}">
                                            @php
                                                $arr = $report->packs_selected;
                                                sort($arr);
                                                $displayStr = "";
                                                if(count($arr) <= 5) {
                                                    $displayStr = implode(', ', $arr);
                                                } else {
                                                    $displayStr = $arr[0] . ', ' . $arr[1] . ', ... , ' . $arr[count($arr)-1];
                                                }
                                            @endphp
                                            {{ $displayStr }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium">{{ number_format($report->jumlah_pack, 0, ',', '.') }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-green-700 text-right font-medium" data-sort="{{ $report->jumlah_bilyet }}">{{ number_format($report->jumlah_bilyet, 0, ',', '.') }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->petugas_1 }} 
                                            @if($report->petugas_2)
                                                <br><span class="text-xs text-gray-400">&amp; {{ $report->petugas_2 }}</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
                                            @if(auth()->user()->role === 'sortir' || auth()->user()->role === 'admin')
                                                <div class="flex items-center justify-center gap-3">
                                                    <a href="{{ route('hcs-sorting-reports.edit', $report->id) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center justify-center" title="Edit">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 pointer-events-none">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                        </svg>
                                                    </a>
                                                    <form id="delete-form-{{ $report->id }}" action="{{ route('hcs-sorting-reports.destroy', $report->id) }}" method="POST" class="m-0 p-0 flex items-center justify-center">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" onclick="deleteReport({{ $report->id }})" class="text-red-600 hover:text-red-900 focus:outline-none flex items-center justify-center" title="Hapus">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 pointer-events-none">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <!-- Leave empty, let DataTables handle empty state -->
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTables CSS & JS (Localized) -->
    <link href="{{ asset('vendor/datatables/datatables.min.css') }}" rel="stylesheet">
    <script src="{{ asset('vendor/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            var table = $('#reportsTable').DataTable({
                "pageLength": 15,
                "lengthMenu": [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                },
                "order": [[0, "desc"]],
                "dom": '<"flex flex-col md:flex-row justify-between items-center mb-4"lBf>rt<"flex justify-between items-center mt-4"ip>',
                "buttons": [
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        className: 'px-3 py-1 bg-gray-200 text-gray-800 rounded mx-1 hover:bg-gray-300 transition'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        className: 'px-3 py-1 bg-green-500 text-white rounded mx-1 hover:bg-green-600 transition'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        className: 'px-3 py-1 bg-red-500 text-white rounded mx-1 hover:bg-red-600 transition',
                        orientation: 'landscape',
                        pageSize: 'A4'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'px-3 py-1 bg-blue-500 text-white rounded mx-1 hover:bg-blue-600 transition'
                    }
                ]
            });

            // Make sure DT search integrates with Tailwind
            $('.dataTables_filter input').addClass('border-gray-300 rounded-md shadow-sm ml-2 focus:ring-indigo-500 focus:border-indigo-500');
            $('.dataTables_length select').addClass('border-gray-300 rounded-md shadow-sm mx-1 focus:ring-indigo-500 focus:border-indigo-500');
        });

        // Global function for SweetAlert Delete Confirmation
        function deleteReport(id) {
            Swal.fire({
                title: 'Hapus Laporan?',
                text: 'Data penyortiran akan dihapus dan status pack akan dikembalikan menjadi siap sortir.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>
