import { Controller, Get } from '@nestjs/common';
import { AppService } from './app.service';
import { EventPattern, Payload } from '@nestjs/microservices';

@Controller()
export class AppController {
  constructor(private readonly appService: AppService) {}

  @Get()
  getHello(): string {
    return this.appService.getHello();
  }

  // MENERIMA PESAN DARI RABBITMQ
  @EventPattern('user.created')
  async handleUserCreated(@Payload() data: any) {
    console.log('--- BOOKING SERVICE: EVENT DITERIMA! ---');
    console.log('Data User Baru:', data);
    
    // Logika sinkronisasi database booking ada di sini
    // return this.appService.syncUser(data);
  }
}