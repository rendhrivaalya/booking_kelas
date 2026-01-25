<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - Info Jadwal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

    <nav class="bg-blue-900 text-white p-4 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold flex items-center">
                <i class="fas fa-university mr-3 text-yellow-400"></i> Portal Mahasiswa
            </h1>
            <div class="flex items-center gap-4">
                <span class="hidden sm:block text-blue-200">Halo, {{ $user['username'] }}</span>
                <a href="{{ route('logout') }}" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded text-sm transition shadow">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6 space-y-6">

        <div class="bg-white p-6 rounded-xl shadow-sm border border-blue-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Monitor Jadwal Ruangan</h2>
                <p class="text-gray-500 text-sm mt-1">Cek ketersediaan ruangan kelas secara real-time di sini.</p>
            </div>
            <div class="bg-blue-50 px-4 py-2 rounded-lg text-blue-700 font-bold shadow-sm">
                Total: {{ count($jadwal) }} Jadwal Aktif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider border-b">
                            <th class="px-6 py-4 font-bold">Info Ruangan</th>
                            <th class="px-6 py-4 font-bold">Waktu</th>
                            <th class="px-6 py-4 font-bold">Keterangan</th>
                            <th class="px-6 py-4 font-bold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($jadwal as $j)

                        {{-- Logic Pembersih Teks (Sama kayak Dosen biar rapi) --}}
                        @php
                            $peminjam = '-';
                            $keperluan = '-';
                            
                            if ($j['status'] != 'available' && isset($j['booking'])) {
                                $fullString = $j['booking']['keperluan'] ?? '';
                                // Bersihkan string
                                $keperluan = preg_replace('/ \(Peminjam: .*\)/', '', $fullString);
                                $keperluan = preg_replace('/ \(Oleh: .*\)/', '', $keperluan);
                                
                                // Ambil nama peminjam dari akun user
                                $peminjam = $j['booking']['user']['username'] ?? 'Dosen';
                            }
                        @endphp

                        <tr class="hover:bg-blue-50 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center font-bold text-xs">
                                        {{ $j['kelas']['kode_kelas'] ?? '?' }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-bold text-gray-900">{{ $j['kelas']['nama_kelas'] ?? '-' }}</div>
                                        <div class="text-[10px] text-gray-400 mt-0.5">{{ $j['kelas']['deskripsi'] ?? $j['kelas']['lokasi'] ?? '' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-700">{{ $j['hari'] }}</div>
                                <div class="text-xs text-gray-500">{{ $j['tanggal'] }} | {{ $j['jam_mulai'] }} - {{ $j['jam_selesai'] }}</div>
                            </td>

                            <td class="px-6 py-4">
                                @if($j['status'] != 'available')
                                    <div class="text-gray-700 font-medium text-xs">
                                        <i class="fas fa-user-circle mr-1 text-gray-400"></i> {{ $peminjam }}
                                    </div>
                                    <div class="text-gray-500 italic text-xs mt-1">
                                        "{{ $keperluan }}"
                                    </div>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($j['status'] == 'available')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> Available
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span> Booked
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 italic bg-gray-50">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-2"></i>
                                    <p>Belum ada jadwal yang dirilis.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>