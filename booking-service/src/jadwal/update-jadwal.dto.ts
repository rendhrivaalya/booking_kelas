import { IsString, IsOptional, IsNumber, IsDateString } from 'class-validator';

export class UpdateJadwalDto {
  @IsOptional()
  @IsString()
  namaKelas?: string;

  @IsOptional()
  @IsString()
  hari?: string;

  @IsOptional()
  @IsString()
  jamMulai?: string;

  @IsOptional()
  @IsString()
  jamSelesai?: string;

  @IsOptional()
  @IsNumber()
  kelasId?: number;
}
