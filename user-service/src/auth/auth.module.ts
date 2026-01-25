import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { ClientsModule, Transport } from '@nestjs/microservices'; // Sekarang ini harusnya sudah aman

import { AuthService } from './auth.service';
import { AuthController } from './auth.controller';
import { User } from './user.entity'; 

@Module({
  imports: [
    TypeOrmModule.forFeature([User]),
    // Registrasi ini yang membuat AuthService bisa kirim pesan
    ClientsModule.register([
      {
        name: 'USER_SERVICE',
        transport: Transport.RMQ,
        options: {
          urls: ['amqp://guest:guest@rabbitmq:5672'], // 'rabbitmq' adalah nama service di docker-compose
          queue: 'user_queue',
          queueOptions: {
            durable: false,
          },
        },
      },
    ]),
  ],
  controllers: [AuthController],
  providers: [AuthService],
})
export class AuthModule {}