<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen - Booking Kelas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans" x-data="{ 
    modalBooking: false, 
    jadwalId: '', 
    namaKelas: '', 
    waktu: '' 
}">

    <nav class="bg-indigo-900 text-white p-4 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold flex items-center">
                <i class="fas fa-chalkboard-teacher mr-3 text-yellow-400"></i> Dashboard Dosen
            </h1>
            <div class="flex items-center gap-4">
                <span class="hidden sm:block text-indigo-200">Halo, {{ $user['username'] }}</span>
                <a href="{{ route('logout') }}" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded text-sm transition shadow">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6 space-y-6">

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow flex items-center">
                <i class="fas fa-check-circle mr-3 text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow flex items-center">
                <i class="fas fa-times-circle mr-3 text-xl"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white p-6 rounded-xl shadow-sm border border-indigo-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Daftar Jadwal Kelas</h2>
                <p class="text-gray-500 text-sm mt-1">Pilih jadwal <span class="text-green-600 font-bold">Available</span> untuk melakukan booking.</p>
            </div>
            <div class="bg-indigo-50 px-4 py-2 rounded-lg text-indigo-700 font-bold shadow-sm">
                Total: {{ count($jadwal) }} Jadwal
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider border-b">
                            <th class="px-6 py-4 font-bold">Info Kelas</th>
                            <th class="px-6 py-4 font-bold">Waktu</th>
                            <th class="px-6 py-4 font-bold">Dipinjam Oleh</th>
                            <th class="px-6 py-4 font-bold">Keperluan</th>
                            <th class="px-6 py-4 font-bold text-center">Status</th>
                            <th class="px-6 py-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($jadwal as $j)

                        {{-- === LOGIKA PEMBERSIH TEXT (INI SOLUSINYA!) === --}}
                        @php
                            // Ambil data keperluan asli
                            $rawKeperluan = $j['booking']['keperluan'] ?? '-';
                            
                            // Hapus teks "(Peminjam: ...)" atau "(Oleh: ...)" menggunakan Regex
                            // Jadi data lama pun akan terlihat bersih
                            $cleanKeperluan = preg_replace('/ \(Peminjam: .*\)/', '', $rawKeperluan);
                            $cleanKeperluan = preg_replace('/ \(Oleh: .*\)/', '', $cleanKeperluan);
                        @endphp

                        <tr class="hover:bg-indigo-50 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $j['kelas']['kode_kelas'] ?? '?' }}</div>
                                <div class="text-xs text-gray-600">{{ $j['kelas']['nama_kelas'] ?? '-' }}</div>
                                <div class="text-[10px] text-gray-400 mt-0.5">{{ $j['kelas']['deskripsi'] ?? $j['kelas']['lokasi'] ?? '' }}</div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-700">{{ $j['hari'] }}</div>
                                <div class="text-xs text-gray-500">{{ $j['tanggal'] }} | {{ $j['jam_mulai'] }}-{{ $j['jam_selesai'] }}</div>
                            </td>

                            <td class="px-6 py-4">
                                @if($j['status'] != 'available')
                                    <div class="flex items-center text-gray-700 font-medium">
                                        <i class="fas fa-user-circle mr-2 text-indigo-500"></i>
                                        {{ $j['booking']['user']['username'] ?? 'User' }}
                                    </div>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                @if($j['status'] != 'available')
                                    <span class="text-gray-600 italic block max-w-xs truncate" title="{{ $cleanKeperluan }}">
                                        "{{ $cleanKeperluan }}"
                                    </span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($j['status'] == 'available')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                        Available
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                        Booked
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($j['status'] == 'available')
                                    <button @click="
                                        modalBooking = true; 
                                        jadwalId = '{{ $j['id'] }}';
                                        namaKelas = '{{ $j['kelas']['nama_kelas'] }}';
                                        waktu = '{{ $j['hari'] }}, {{ $j['jam_mulai'] }}';
                                    " class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow text-xs transition transform hover:scale-105">
                                        Booking
                                    </button>
                                @else
                                    <button disabled class="bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded cursor-not-allowed text-xs flex items-center justify-center w-full shadow-inner">
                                        <i class="fas fa-lock mr-1"></i> Terisi
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 italic bg-gray-50">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-2"></i>
                                    <p>Belum ada jadwal kelas yang tersedia.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div x-show="modalBooking" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 backdrop-blur-sm hidden" 
         x-transition.opacity :class="{'hidden': !modalBooking}">
        
        <div class="bg-white rounded-xl w-full max-w-md p-6 shadow-2xl transform transition-all scale-100">
            <h2 class="text-xl font-bold text-gray-800 mb-2 border-b pb-2 flex items-center">
                <i class="fas fa-edit mr-2 text-indigo-600"></i> Form Booking
            </h2>
            
            <div class="mb-5 bg-indigo-50 p-3 rounded-lg text-sm text-indigo-900 border border-indigo-100">
                <div class="flex justify-between mb-1">
                    <span class="font-semibold">Ruangan:</span>
                    <span x-text="namaKelas"></span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold">Waktu:</span>
                    <span x-text="waktu"></span>
                </div>
            </div>

            <form action="{{ url('/booking') }}" method="POST">
                @csrf
                <input type="hidden" name="jadwal_id" x-model="jadwalId">

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Keperluan</label>
                    <textarea name="keperluan" rows="3" required placeholder="Contoh: Kuliah Pengganti, Seminar, Rapat..." 
                        class="w-full border-gray-300 border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none resize-none transition"></textarea>
                    <p class="text-[10px] text-gray-400 mt-1">*Nama peminjam otomatis diambil dari akun Anda.</p>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="modalBooking = false" class="px-5 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg font-medium transition">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg shadow-indigo-200 transition">
                        Konfirmasi Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>