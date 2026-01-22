import { IsNotEmpty, IsString, IsEmail, IsOptional } from 'class-validator';

export class RegisterDto {
  @IsNotEmpty()
  @IsEmail()
  email!: string;

  @IsNotEmpty()
  @IsString()
  username!: string;

  @IsNotEmpty()
  @IsString()
  password!: string;

  // TAMBAHAN: Role bersifat opsional. 
  // Kalau kosong, nanti otomatis jadi 'mahasiswa' di database
  @IsOptional()
  @IsString()
  role?: string;
}