import { Controller, Post, Body, Get, Query, UsePipes, ValidationPipe } from '@nestjs/common';
import { BookingService } from './booking.service';
import { CreateBookingDto } from './dto/create-booking.dto';

@Controller('bookings')
export class BookingController {
  constructor(private readonly bookingService: BookingService) {}

  // 1. Endpoint Cek Ketersediaan (Mahasiswa pakai ini)
  // URL: GET /bookings/check?kode=7.1.1&tanggal=2026-01-22&jam_mulai=08:00&jam_selesai=10:00
  @Get('check')
  async check(
    @Query('kode') kode: string,
    @Query('tanggal') tanggal: string,
    @Query('jam_mulai') jam_mulai: string,
    @Query('jam_selesai') jam_selesai: string,
  ) {
    const isAvailable = await this.bookingService.checkAvailability(kode, tanggal, jam_mulai, jam_selesai);
    
    return {
      kode_kelas: kode,
      tanggal,
      jam: `${jam_mulai} - ${jam_selesai}`,
      status: isAvailable ? 'AVAILABLE' : 'FULL (Terisi)',
      message: isAvailable 
        ? 'Kelas kosong, silakan lapor Dosen/Staf untuk booking.' 
        : 'Kelas sedang dipakai.'
    };
  }

  // 2. Endpoint Booking (Role dicek di Service)
  @Post('create')
  @UsePipes(new ValidationPipe({ whitelist: true }))
  async create(@Body() createBookingDto: CreateBookingDto) {
    const booking = await this.bookingService.create(createBookingDto);
    return {
      message: 'Booking berhasil dibuat!',
      data: booking
    };
  }
}