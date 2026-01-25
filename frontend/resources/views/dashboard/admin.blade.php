<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Booking Kelas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans" x-data="{ 
    modalKelas: false, 
    modalJadwal: false, 
    isEdit: false,
    formAction: '',
    kKode: '', kNama: '', kKap: '', kDesc: '',
    jId: '', jKelasId: '', jTgl: '', jHari: '', jMulai: '', jSelesai: ''
}">

    <nav class="bg-gray-900 text-white p-4 shadow-lg flex justify-between items-center sticky top-0 z-50">
        <h1 class="text-xl font-bold flex items-center">
            <i class="fas fa-calendar-check mr-3 text-green-400"></i> Dashboard Admin Booking
        </h1>
        <div class="flex items-center gap-4">
            <span class="hidden sm:block text-gray-300">Halo, {{ $user['username'] }}</span>
            <a href="{{ route('logout') }}" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded text-sm transition shadow">Logout</a>
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
                <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="flex flex-col md:flex-row justify-between items-center bg-white p-6 rounded-xl shadow-sm gap-4 border border-gray-100">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Jadwal Kelas</h2>
                <p class="text-gray-500 text-sm mt-1">Pantau penggunaan ruangan dan kelola jadwal.</p>
            </div>
            
            <div class="flex gap-3">
                <button @click="modalKelas = true; formAction = '{{ url('/kelas') }}'; kKode=''; kNama=''; kKap=''; kDesc='';" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow font-bold flex items-center transition transform hover:scale-105">
                    <i class="fas fa-door-open mr-2"></i> Buat Ruangan
                </button>
                
                <button @click="modalJadwal = true; isEdit = false; formAction = '{{ url('/jadwal') }}'; jTgl=''; jHari=''; jMulai=''; jSelesai='';" 
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg shadow font-bold flex items-center transition transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i> Tambah Jadwal
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Daftar Jadwal Aktif</span>
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full border border-blue-200 shadow-sm">{{ count($jadwal) }} Jadwal</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b text-gray-600 text-sm uppercase">
                            <th class="px-6 py-4 font-bold">Ruangan & Kelas</th>
                            <th class="px-6 py-4 font-bold">Waktu</th>
                            <th class="px-6 py-4 font-bold">Info Booking</th> 
                            <th class="px-6 py-4 font-bold">Status</th>
                            <th class="px-6 py-4 font-bold text-center">Kontrol</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($jadwal as $j)

                        {{-- PHP Logic: Bersihkan Teks Keperluan & Siapkan Nama Peminjam --}}
                        @php
                            $peminjam = '-';
                            $keperluan = '-';
                            
                            if ($j['status'] != 'available' && isset($j['booking'])) {
                                $rawKeperluan = $j['booking']['keperluan'] ?? '';
                                // Hapus format lama (Peminjam: ...) biar bersih
                                $keperluan = preg_replace('/ \(Peminjam: .*\)/', '', $rawKeperluan);
                                $keperluan = preg_replace('/ \(Oleh: .*\)/', '', $keperluan);
                                
                                // Ambil username peminjam
                                $peminjam = $j['booking']['user']['username'] ?? 'User';
                            }
                        @endphp

                        <tr class="hover:bg-blue-50 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center font-bold text-xs shadow-sm border border-blue-200">
                                        {{ $j['kelas']['kode_kelas'] ?? '?' }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-bold text-gray-900">{{ $j['kelas']['nama_kelas'] ?? 'Nama Tidak Ada' }}</div>
                                        <div class="text-xs text-gray-500">{{ $j['kelas']['deskripsi'] ?? $j['kelas']['lokasi'] ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-gray-700 flex items-center">
                                        <i class="far fa-calendar-alt mr-2 text-gray-400"></i> {{ $j['tanggal'] ?? '-' }}
                                    </span>
                                    <span class="text-gray-500 text-xs mt-1 flex items-center">
                                        <i class="far fa-clock mr-2 text-gray-400"></i> {{ $j['hari'] }}, {{ $j['jam_mulai'] }} - {{ $j['jam_selesai'] }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                @if($j['status'] != 'available')
                                    <div class="flex flex-col">
                                        <div class="text-gray-800 font-bold flex items-center text-xs">
                                            <i class="fas fa-user-circle mr-2 text-blue-500"></i> {{ $peminjam }}
                                        </div>
                                        <div class="text-gray-500 italic text-xs mt-1 pl-5">
                                            "{{ $keperluan }}"
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                @if($j['status'] == 'available')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                        <span class="h-2 w-2 rounded-full bg-green-400 mr-1.5"></span> Available
                                    </span>
                                @else
                                    <div class="flex flex-col items-start gap-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            <span class="h-2 w-2 rounded-full bg-red-400 mr-1.5"></span> Booked
                                        </span>
                                        <form action="{{ url('/jadwal/'.$j['id'].'/reset') }}" method="POST">
                                            @csrf @method('PUT')
                                            <button onclick="return confirm('Reset jadwal ini menjadi Available? Data booking akan hilang.')" 
                                                class="text-[10px] text-blue-500 hover:text-blue-700 hover:underline font-medium flex items-center">
                                                <i class="fas fa-undo mr-1"></i> Reset Status
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center gap-3">
                                    <button @click="
                                        modalJadwal = true; 
                                        isEdit = true;
                                        formAction = '{{ url('/jadwal/'.$j['id']) }}';
                                        jKelasId = '{{ $j['kelas']['id'] ?? '' }}';
                                        jTgl = '{{ $j['tanggal'] ?? '' }}';
                                        jHari = '{{ $j['hari'] }}';
                                        jMulai = '{{ $j['jam_mulai'] }}';
                                        jSelesai = '{{ $j['jam_selesai'] }}';
                                    " class="w-8 h-8 rounded-full bg-gray-100 hover:bg-blue-100 text-gray-500 hover:text-blue-600 transition flex items-center justify-center" title="Edit Jadwal">
                                        <i class="fas fa-pen text-xs"></i>
                                    </button>

                                    <form action="{{ url('/jadwal/'.$j['id']) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                                        @csrf @method('DELETE')
                                        <button class="w-8 h-8 rounded-full bg-gray-100 hover:bg-red-100 text-gray-500 hover:text-red-600 transition flex items-center justify-center" title="Hapus Jadwal">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic bg-gray-50">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                                    <p class="font-medium">Belum ada jadwal aktif.</p>
                                    <p class="text-xs mt-1">Klik tombol "Tambah Jadwal" di atas untuk memulai.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div x-show="modalKelas" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 backdrop-blur-sm hidden" :class="{'hidden': !modalKelas}">
        <div class="bg-white rounded-xl w-full max-w-md p-8 shadow-2xl transform transition-all">
            <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2 flex items-center"><i class="fas fa-door-open mr-2 text-blue-600"></i> Buat Ruangan</h2>
            <form :action="formAction" method="POST">
                @csrf
                <div class="space-y-5">
                    <div><label class="block text-sm font-bold text-gray-700 mb-1">Kode</label><input type="text" name="kode_kelas" x-model="kKode" required class="w-full border rounded-lg px-4 py-2"></div>
                    <div><label class="block text-sm font-bold text-gray-700 mb-1">Nama</label><input type="text" name="nama_kelas" x-model="kNama" required class="w-full border rounded-lg px-4 py-2"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-bold text-gray-700 mb-1">Kapasitas</label><input type="number" name="kapasitas" x-model="kKap" required class="w-full border rounded-lg px-4 py-2"></div>
                        <div><label class="block text-sm font-bold text-gray-700 mb-1">Lokasi</label><input type="text" name="deskripsi" x-model="kDesc" class="w-full border rounded-lg px-4 py-2"></div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" @click="modalKelas = false" class="px-5 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg font-medium">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="modalJadwal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 backdrop-blur-sm hidden" :class="{'hidden': !modalJadwal}">
        <div class="bg-white rounded-xl w-full max-w-md p-8 shadow-2xl transform transition-all">
            <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2 flex items-center"><i class="fas fa-clock mr-2 text-green-600"></i> <span x-text="isEdit ? 'Edit Jadwal' : 'Tambah Jadwal'"></span></h2>
            <form :action="formAction" method="POST">
                @csrf
                <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">
                <div class="space-y-5">
                    <div x-show="!isEdit">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Pilih Ruangan</label>
                        <select name="kelas_id" x-model="jKelasId" class="w-full border rounded-lg px-4 py-2 bg-white">
                            <option value="">-- Pilih --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k['id'] }}">{{ $k['kode_kelas'] }} - {{ $k['nama_kelas'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" x-model="jTgl" required 
                            @change="const d = new Date($el.value); const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; if(!isNaN(d.getTime())){ jHari = days[d.getDay()]; }"
                            class="w-full border rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Hari (Otomatis)</label>
                        <input type="text" name="hari" x-model="jHari" readonly class="w-full border bg-gray-100 text-gray-600 font-bold rounded-lg px-4 py-2 cursor-not-allowed">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-bold text-gray-700 mb-1">Jam Mulai</label><input type="time" name="jam_mulai" x-model="jMulai" required class="w-full border rounded-lg px-4 py-2"></div>
                        <div><label class="block text-sm font-bold text-gray-700 mb-1">Jam Selesai</label><input type="time" name="jam_selesai" x-model="jSelesai" required class="w-full border rounded-lg px-4 py-2"></div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" @click="modalJadwal = false" class="px-5 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg font-medium">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>