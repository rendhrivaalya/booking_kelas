import { IsNotEmpty, IsNumber, IsString } from 'class-validator';

export class CreateJadwalDto {
  @IsNotEmpty()
  @IsNumber()
  kelasId: number; // ID Kelas yang mau diberi jadwal

  @IsNotEmpty()
  @IsString()
  hari: string;

  @IsNotEmpty()
  @IsString()
  jam_mulai: string;

  @IsNotEmpty()
  @IsString()
  jam_selesai: string;
}