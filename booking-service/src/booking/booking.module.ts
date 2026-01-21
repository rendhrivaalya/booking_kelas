import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { BookingService } from './booking.service';
import { BookingController } from './booking.controller';
import { Booking } from './booking.entity';
import { Kelas } from '../kelas/kelas.entity';
import { Jadwal } from '../jadwal/jadwal.entity';

@Module({
  imports: [TypeOrmModule.forFeature([Booking, Kelas, Jadwal])],
  controllers: [BookingController],
  providers: [BookingService],
})
export class BookingModule {}
