import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { ConfigModule } from '@nestjs/config'; 
import { AppController } from './app.controller';
import { AppService } from './app.service';
import { KelasModule } from './kelas/kelas.module';
import { JadwalModule } from './jadwal/jadwal.module';
import { BookingModule } from './booking/booking.module';
import { RabbitMQConfigModule } from './rabbitmq.module';

@Module({
  imports: [
    // 1. Config Module
    ConfigModule.forRoot({
      isGlobal: true,
      envFilePath: '.env',
    }),

    // 2. Database Connection
    TypeOrmModule.forRoot({
      type: 'mysql',
      // PENTING: Sesuaikan nama variabel dengan yang ada di docker-compose.yml
      host: process.env.DATABASE_HOST,      // <--- GANTI JADI DATABASE_HOST
      port: Number(process.env.DATABASE_PORT), // <--- GANTI JADI DATABASE_PORT
      username: process.env.DATABASE_USER,  // <--- GANTI JADI DATABASE_USER
      password: process.env.DATABASE_PASSWORD, // <--- GANTI JADI DATABASE_PASSWORD
      database: process.env.DATABASE_NAME,  // <--- GANTI JADI DATABASE_NAME
      
      entities: [__dirname + '/**/*.entity{.ts,.js}'],
      synchronize: true, 
    }),

    RabbitMQConfigModule,
    KelasModule,
    JadwalModule,
    BookingModule,
  ],
  controllers: [AppController],
  providers: [AppService],
})
export class AppModule {}