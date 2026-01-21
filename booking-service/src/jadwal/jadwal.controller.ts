import { Controller, Get, Post, Put, Delete, Body, Param } from '@nestjs/common';
import { JadwalService } from './jadwal.service';

@Controller('jadwal')
export class JadwalController {
  constructor(private readonly jadwalService: JadwalService) {}

  @Post()
  create(@Body() body: any) {
    return this.jadwalService.create(body);
  }

  @Get()
  findAll() {
    return this.jadwalService.findAll();
  }

  @Get(':id')
  findOne(@Param('id') id: string) {
    return this.jadwalService.findOne(+id);
  }

  @Put(':id')
  update(@Param('id') id: string, @Body() body: any) {
    return this.jadwalService.update(+id, body);
  }

  @Delete(':id')
  remove(@Param('id') id: string) {
    return this.jadwalService.remove(+id);
  }
}
