import { IsNotEmpty, IsNumber, IsString } from 'class-validator';

export class CreateBookingDto {
  @IsNotEmpty()
  // Sesuaikan: Jika userId kamu di DB itu INT, pakai @IsNumber(). Jika UUID string, pakai @IsString()
  @IsNumber() 
  userId: number;

  @IsNotEmpty()
  @IsString()
  role: string;

  @IsNotEmpty()
  @IsNumber()
  jadwalId: number; // <--- Kita cuma butuh ID ini untuk cari data lainnya

  @IsNotEmpty()
  @IsString()
  keperluan: string;

}