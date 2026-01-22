import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
// Hapus import RabbitMQModule di sini
// import { RabbitMQModule } from '@golevelup/nestjs-rabbitmq'; 

import { AppController } from './app.controller';
import { AppService } from './app.service';
import { AuthModule } from './auth/auth.module';
import { RabbitMQConfigModule } from './rabbitmq.module'; // Import file baru tadi

@Module({
  imports: [
    // TypeORM connection
    TypeOrmModule.forRoot({
      type: 'mysql',
      host: 'mysql-user',
      port: 3306,
      username: 'root',
      password: 'root',
      database: 'user_db',
      autoLoadEntities: true,
      synchronize: true,
    }),

    // Panggil Config Global yang baru dibuat
    RabbitMQConfigModule, 

    // Feature Modules
    AuthModule,
  ],
  controllers: [AppController],
  providers: [AppService],
})
export class AppModule {}