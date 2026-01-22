import { IsString, IsNumber, IsDateString } from 'class-validator';

export class CreateBookingDto {
  @IsString()
  namaUser: string;

  @IsDateString()
  tanggalBooking: string;

  @IsNumber()
  kelasId: number;

  @IsNumber()
  jadwalId: number;
}
