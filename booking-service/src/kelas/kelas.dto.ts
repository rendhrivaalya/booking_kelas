import { IsString, IsNotEmpty, IsInt } from 'class-validator';

export class CreateKelasDto {
  @IsString()
  @IsNotEmpty()
  kode_kelas: string;

  @IsString()
  @IsNotEmpty()
  nama_kelas: string; // Pastikan ini namanya 'nama_kelas'

  @IsInt()
  @IsNotEmpty()
  kapasitas: number;

  @IsString()
  @IsNotEmpty()
  deskripsi: string;
}