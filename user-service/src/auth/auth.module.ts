import { Module } from '@nestjs/common';
// 👇 1. INI WAJIB ADA: Import TypeOrmModule dari librarynya
import { TypeOrmModule } from '@nestjs/typeorm';

import { AuthService } from './auth.service';
import { AuthController } from './auth.controller';

// 👇 2. Pastikan Entity User juga sudah di-import
import { User } from './user.entity'; 

@Module({
  imports: [
    // Sekarang TypeOrmModule tidak akan merah lagi
    TypeOrmModule.forFeature([User]), 
  ],
  controllers: [AuthController],
  providers: [AuthService],
})
export class AuthModule {}