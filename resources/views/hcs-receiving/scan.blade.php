<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-lg text-gray-800 leading-tight tracking-tighter">
            {{ __('Scan Barcode Penerimaan HCS') }}
        </h2>
    </x-slot>

    <div id="scan-monitor-component" class="py-6" x-data="{ 
            showBarcodeModal: false, 
            selectedReg: null,
            openBarcodeModal(reg) {
                console.log('Alpine: openBarcodeModal called with:', reg);
                this.selectedReg = reg;
                this.showBarcodeModal = true;
                
                this.$nextTick(() => {
                    setTimeout(() => {
                        const svg = document.getElementById('modal-barcode-svg');
                        if (svg) {
                            try {
                                JsBarcode('#modal-barcode-svg', reg.barcode_token, {
                                    format: 'CODE128',
                                    width: 2,
                                    height: 80,
                                    displayValue: true,
                                    fontSize: 16,
                                    font: 'monospace',
                                    margin: 10
                                });
                                console.log('Alpine: Barcode rendered successfully');
                            } catch (e) {
                                console.error('JsBarcode Error:', e);
                            }
                        } else {
                            console.error('Alpine: SVG #modal-barcode-svg NOT FOUND in DOM');
                        }
                    }, 150);
                });
            }
        }" x-init="console.log('Alpine: Monitor component READY')"
        @open-barcode-modal.window="openBarcodeModal($event.detail)">

        <!-- Modal View Barcode (Teleport to body for z-index safety) -->
        <template x-teleport="body">
            <div x-show="showBarcodeModal" 
                class="fixed inset-0 overflow-y-auto" 
                style="z-index: 999999 !important;"
                x-cloak>
                <div class="flex items-start justify-center min-h-screen px-4 pt-48 pb-20 text-center">
                    <div x-show="showBarcodeModal" 
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        @click="showBarcodeModal = false"
                        class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm"
                        style="z-index: -1;"></div>

                    <div x-show="showBarcodeModal"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-slate-900 rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-5 border border-slate-200 dark:border-slate-800"
                        style="z-index: 1000000 !important;">

                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">
                                    Detail Barcode</h3>
                                <button @click="showBarcodeModal = false"
                                    class="text-slate-400 hover:text-slate-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <div
                                class="bg-white dark:bg-white rounded-xl p-4 flex flex-col items-center justify-center border border-slate-100 min-h-[160px]">
                                <svg id="modal-barcode-svg" class="max-w-full h-auto"></svg>
                                <p class="mt-3 text-[10px] font-black text-slate-900 uppercase tracking-[0.2em]"
                                    x-text="selectedReg ? selectedReg.barcode_token : ''"></p>
                            </div>

                            <div class="mt-5 grid grid-cols-2 gap-3">
                                <div
                                    class="p-2.5 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800">
                                    <span
                                        class="block text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Nomor
                                        Bon</span>
                                    <span class="text-[10px] font-black text-slate-700 dark:text-slate-200"
                                        x-text="selectedReg ? selectedReg.nomor_bon : '-'"></span>
                                </div>
                                <div
                                    class="p-2.5 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800">
                                    <span
                                        class="block text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Pecahan
                                        (Q)</span>
                                    <span class="text-[10px] font-black text-slate-700 dark:text-slate-200"
                                        x-text="selectedReg ? selectedReg.pecahan + ' (' + new Intl.NumberFormat('id-ID').format(selectedReg.jumlah) + ')' : '-'"></span>
                                </div>
                                <div
                                    class="p-2.5 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800">
                                    <span
                                        class="block text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Batch
                                        / Seri</span>
                                    <span class="text-[10px] font-black text-slate-700 dark:text-slate-200"
                                        x-text="selectedReg ? selectedReg.batch + ' / ' + selectedReg.seri : '-'"></span>
                                </div>
                                <div
                                    class="p-2.5 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800">
                                    <span
                                        class="block text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Gilir</span>
                                    <span class="text-[10px] font-black text-slate-700 dark:text-slate-200"
                                        x-text="selectedReg ? selectedReg.gilir : '-'"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button @click="showBarcodeModal = false"
                                class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">TUTUP</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Sisi Kiri: Scanner Terminal -->
                <div class="lg:col-span-4 xl:col-span-4">
                    <div
                        class="bg-white dark:bg-slate-900 shadow-sm rounded-2xl border border-slate-200 dark:border-slate-800 p-6 sticky top-6">

                        <div class="mb-6">
                            <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">
                                Scanner Terminal</h3>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter mt-1">Konfirmasi
                                Penerimaan HCS</p>
                        </div>

                        <!-- Scan Area -->
                        <div class="space-y-6">
                            <div class="relative overflow-hidden rounded-xl border-2 border-slate-100 dark:border-slate-800 bg-black aspect-square group"
                                x-data="{ cameraActive: false }">
                                <div id="reader" class="w-full h-full"></div>
                                <div id="scan-status"
                                    class="absolute inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm text-white text-[10px] font-black uppercase tracking-[0.2em] opacity-0 transition-opacity">
                                    Memindai...
                                </div>
                                <button @click="cameraActive = !cameraActive; toggleCamera(cameraActive)"
                                    class="absolute bottom-4 right-4 z-[100] px-4 py-2 rounded-xl font-black text-[9px] uppercase tracking-widest transition-all shadow-2xl backdrop-blur-xl border border-white/30"
                                    :class="cameraActive ? 'bg-rose-600 text-white' : 'bg-emerald-600 text-white'">
                                    <span x-text="cameraActive ? 'OFF CAMERA' : 'ON CAMERA'"></span>
                                </button>
                            </div>

                            <!-- Manual Input -->
                            <div class="space-y-3">
                                <label
                                    class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Input
                                    Barcode</label>
                                <div class="flex flex-col gap-2">
                                    <input type="text" id="manual-barcode"
                                        onkeypress="if(event.keyCode == 13) processBarcode(this.value)"
                                        class="w-full border-slate-200 dark:border-slate-800 rounded-xl font-bold py-3 px-4 text-xs text-indigo-600 dark:bg-slate-950 focus:ring-2 focus:ring-indigo-500 transition-all uppercase tracking-widest"
                                        placeholder="Scan / Ketik...">
                                    <button onclick="processBarcode(document.getElementById('manual-barcode').value)"
                                        class="w-full bg-indigo-600 text-white py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-700 active:scale-95 transition-all shadow-sm">
                                        SUBMIT DATA
                                    </button>
                                </div>
                            </div>

                            <!-- Alert Box -->
                            <div id="result-box" class="hidden">
                                <div
                                    class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 rounded-xl p-4 flex items-center gap-3">
                                    <div class="p-2 bg-emerald-500 rounded-lg shrink-0">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <p class="text-[10px] font-black text-emerald-700 dark:text-emerald-400 leading-tight"
                                        id="result-message"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sisi Kanan: Monitoring -->
                <div class="lg:col-span-8 xl:col-span-8">

                    <div id="scan-status-container" class="space-y-6">
                        @include('hcs-receiving.scan-status-table', ['registrations' => $registrations])
                    </div>
                </div>

            </div>
        </div>

        <!-- Scanner Library -->
        <script src="{{ asset('js/vendor/html5-qrcode.min.js') }}"></script>
        <script src="{{ asset('js/vendor/JsBarcode.all.min.js') }}"></script>
        <script>
            const html5QrCode = new Html5Qrcode("reader");
            const statusEl = document.getElementById('scan-status');
            const resultBox = document.getElementById('result-box');
            const resultMsg = document.getElementById('result-message');
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };

            function onScanSuccess(decodedText, decodedResult) {
                if (html5QrCode.getState() === 2) { // 2 = Scanning
                    html5QrCode.pause();
                    statusEl.innerText = "Processing...";
                    statusEl.style.opacity = "1";
                    processBarcode(decodedText);
                }
            }

            async function toggleCamera(active) {
                try {
                    if (active) {
                        await html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess);
                    } else {
                        if (html5QrCode.getState() !== 1) { // 1 = Idle
                            await html5QrCode.stop();
                        }
                    }
                } catch (err) {
                    console.error("Camera error:", err);
                }
            }

            async function refreshStatusTable() {
                try {
                    const response = await fetch("{{ route('hcs-receiving.scan-status') }}");
                    const html = await response.text();
                    document.getElementById('scan-status-container').innerHTML = html;
                } catch (error) {
                    console.error("Refresh table error:", error);
                }
            }

            async function processBarcode(barcode) {
                if (!barcode) return;

                try {
                    const response = await fetch("{{ route('hcs-receiving.scan-process') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ barcode: barcode })
                    });

                    const result = await response.json();

                    if (result.success) {
                        resultBox.classList.remove('hidden');
                        resultMsg.innerText = result.message;

                        // Audio feedback
                        new Audio('{{ asset("sounds/success.mp3") }}').play().catch(() => { });

                        // Refresh Monitoring Table
                        refreshStatusTable();

                        setTimeout(() => {
                            resultBox.classList.add('hidden');
                            if (html5QrCode.getState() === 3) html5QrCode.resume();
                        }, 3000);
                    } else {
                        alert('Gagal: ' + result.message);
                        if (html5QrCode.getState() === 3) html5QrCode.resume();
                    }
                } catch (error) {
                    console.error(error);
                    alert('Terjadi kesalahan sistem.');
                    if (html5QrCode.getState() === 3) html5QrCode.resume();
                } finally {
                    statusEl.style.opacity = "0";
                    document.getElementById('manual-barcode').value = "";
                    document.getElementById('manual-barcode').focus();
                }
            }

            async function manualConfirm(id) {
                try {
                    const result = await Swal.fire({
                        title: 'Konfirmasi Manual?',
                        text: 'Terima data ini tanpa melalui scanner?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#4f46e5',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Terima!',
                        cancelButtonText: 'Batal'
                    });

                    if (result.isConfirmed) {
                        const response = await fetch(`/hcs-receiving/manual-confirm/${id}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({ title: 'Berhasil!', text: data.message, icon: 'success', timer: 1500, showConfirmButton: false });
                            refreshStatusTable();
                        } else {
                            throw new Error(data.message);
                        }
                    }
                } catch (error) {
                    Swal.fire('Error', error.message || 'Gagal memproses konfirmasi manual', 'error');
                }
            }

            async function deleteRegistration(id) {
                try {
                    const result = await Swal.fire({
                        title: 'Hapus Registrasi?',
                        text: 'Data yang belum diterima akan dihapus permanen.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    });

                    if (result.isConfirmed) {
                        const response = await fetch(`/hcs-khazai-registration/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({ title: 'Berhasil!', text: data.message, icon: 'success', timer: 1500, showConfirmButton: false });
                            refreshStatusTable();
                        } else {
                            throw new Error(data.message);
                        }
                    }
                } catch (error) {
                    Swal.fire('Error', error.message || 'Gagal menghapus registrasi', 'error');
                }
            }

            async function deleteReceiving(id) {
                try {
                    const result = await Swal.fire({
                        title: 'Batalkan Penerimaan?',
                        text: 'Status barcode akan kembali menjadi PENDING dan data stok akan dikembalikan.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Batalkan!',
                        cancelButtonText: 'Batal'
                    });

                    if (result.isConfirmed) {
                        const response = await fetch(`/hcs-receiving/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({ title: 'Berhasil!', text: data.message, icon: 'success', timer: 1500, showConfirmButton: false });
                            refreshStatusTable();
                        } else {
                            throw new Error(data.message);
                        }
                    }
                } catch (error) {
                    Swal.fire('Error', error.message || 'Gagal membatalkan penerimaan', 'error');
                }
            }

            window.openBarcodeGlobal = function (reg) {
                console.log('Global: Dispatching open-barcode-modal for', reg.barcode_token);
                window.dispatchEvent(new CustomEvent('open-barcode-modal', { detail: reg }));
            };

            // Auto focus manual input
            window.onload = () => document.getElementById('manual-barcode').focus();
        </script>

    </div>
</x-app-layout>