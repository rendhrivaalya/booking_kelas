import { Controller, Get, Post, Body, Param, Delete, Put } from '@nestjs/common';
import { JadwalService } from './jadwal.service';

@Controller('jadwal')
export class JadwalController {
  constructor(private readonly jadwalService: JadwalService) {}

  // === 1. ENDPOINT TAMBAH JADWAL ===
  // Frontend mengirim ke /jadwal (POST)
  @Post() 
  create(@Body() body: any) {
    return this.jadwalService.create(body);
  }

  // === 2. ENDPOINT LIHAT SEMUA ===
  @Get()
  findAll() {
    return this.jadwalService.findAll();
  }

  // === 3. ENDPOINT UPDATE JADWAL ===
  @Put(':id')
  update(@Param('id') id: string, @Body() body: any) {
    return this.jadwalService.update(+id, body);
  }

  // === 4. ENDPOINT HAPUS JADWAL ===
  @Delete(':id')
  remove(@Param('id') id: string) {
    return this.jadwalService.remove(+id);
  }
}