import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
// Hapus import RabbitMQModule dari golevelup karena sudah dibungkus
import { AppController } from './app.controller';
import { AppService } from './app.service';
import { KelasModule } from './kelas/kelas.module';
import { JadwalModule } from './jadwal/jadwal.module';
import { BookingModule } from './booking/booking.module';
import { RabbitMQConfigModule } from './rabbitmq.module'; // Import file baru ini

@Module({
  imports: [
    // 1. Database Connection
    TypeOrmModule.forRoot({
      type: 'mysql',
      host: 'mysql-booking',
      port: 3306,
      username: 'root',
      password: 'root',
      database: 'booking_kelas', // Pastikan nama DB ini benar (booking_kelas / booking-kelas)
      autoLoadEntities: true,
      synchronize: true,
    }),

    // 2. RabbitMQ Connection (Panggil module global yang baru dibuat)
    // Ini otomatis membawa settingan Heartbeat & Reconnect
    RabbitMQConfigModule,

    // 3. Feature Modules
    KelasModule,
    JadwalModule,
    BookingModule,
  ],
  controllers: [AppController],
  providers: [AppService],
})
export class AppModule {}