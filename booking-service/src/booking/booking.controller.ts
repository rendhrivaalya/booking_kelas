import { Controller, Post, Body, Get, Query, BadRequestException } from '@nestjs/common';
import { BookingService } from './booking.service';
import { CreateBookingDto } from './dto/create-booking.dto';

@Controller('bookings')
export class BookingController {
  constructor(private readonly bookingService: BookingService) {}

  // 1. Endpoint untuk Membuat Booking
  @Post('create')
  async create(@Body() createBookingDto: CreateBookingDto) {
    return this.bookingService.create(createBookingDto);
  }

  // 2. Endpoint untuk Melihat Semua Booking (Admin/Dosen)
  @Get()
  async findAll() {
    return this.bookingService.findAll();
  }

  // 3. Endpoint Cek Ketersediaan (Simple Version)
  // Logic yang rumit sudah ditangani saat 'create', jadi ini opsional.
  // Kalau mau cek, cukup cek by ID jadwal.
  @Get('check')
  async checkAvailability(@Query('jadwalId') jadwalId: string) {
    if (!jadwalId) {
      throw new BadRequestException('Jadwal ID harus diisi');
    }
    // Panggil service yang cuma menerima 1 argumen
    const isAvailable = await this.bookingService.checkAvailability(+jadwalId);
    return { available: isAvailable };
  }
}