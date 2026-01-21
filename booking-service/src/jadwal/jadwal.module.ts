import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { JadwalService } from './jadwal.service';
import { JadwalController } from './jadwal.controller';
import { Jadwal } from './jadwal.entity';
import { Kelas } from '../kelas/kelas.entity';

@Module({
  imports: [TypeOrmModule.forFeature([Jadwal, Kelas])],
  providers: [JadwalService],
  controllers: [JadwalController],
  exports: [JadwalService],
})
export class JadwalModule {}
