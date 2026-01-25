import { ValidationPipe } from '@nestjs/common';
import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';
import { MicroserviceOptions, Transport } from '@nestjs/microservices';

async function bootstrap() {
  const app = await NestFactory.create(AppModule);
  
  // 👇 TAMBAHKAN INI: Agar Booking Service bisa dengar RabbitMQ
  app.connectMicroservice<MicroserviceOptions>({
    transport: Transport.RMQ,
    options: {
      urls: ['amqp://guest:guest@rabbitmq:5672'],
      queue: 'user_queue', // Nama queue yang akan menampung pesan dari user_exchange
      queueOptions: {
        durable: false,
      },
    },
  });

  app.useGlobalPipes(new ValidationPipe());
  
  await app.startAllMicroservices(); // Jalankan Microservice-nya
  await app.listen(3002); // Jalankan HTTP-nya
  console.log('Booking Service is running on port 3002 and RabbitMQ');
}
bootstrap();