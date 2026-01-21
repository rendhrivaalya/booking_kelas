import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { KelasModule } from './kelas/kelas.module';
import { JadwalModule } from './jadwal/jadwal.module';
import { BookingModule } from './booking/booking.module';

@Module({
  imports: [
    TypeOrmModule.forRoot({
      type: 'mysql',
      host: 'localhost',
      port: 3306,
      username: 'root',
      password: '',
      database: 'booking_kelas',
      autoLoadEntities: true,
      synchronize: true,
    }),
    KelasModule,
    JadwalModule,
    BookingModule,
  ],
})
export class AppModule {}