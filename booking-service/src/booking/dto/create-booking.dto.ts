import { IsNotEmpty, IsString, IsNumber, Matches } from 'class-validator';

export class CreateBookingDto {
  @IsNotEmpty()
  @IsNumber()
  userId: number;

  @IsNotEmpty()
  @IsString()
  role: string; // 'mahasiswa', 'dosen', 'staf', 'admin'

  @IsNotEmpty()
  @IsString()
  // Validasi format kode kelas (Angka.Angka.Angka)
  // @Matches(/^\d+\.\d+\.\d+$/, { message: 'Format kode kelas salah (contoh: 7.1.1)' })
  kode_kelas: string; // Kita pakai kode string (7.1.1) buat input biar user gampang

  @IsNotEmpty()
  @IsString()
  tanggal: string; // "2026-01-22"

  @IsNotEmpty()
  @IsString()
  jam_mulai: string; // "08:00"

  @IsNotEmpty()
  @IsString()
  jam_selesai: string; // "10:00"

  @IsNotEmpty()
  @IsString()
  keperluan: string;
}