import { Controller, Get, Post, Body, Param, Delete, UsePipes, ValidationPipe } from '@nestjs/common';
import { JadwalService } from './jadwal.service';
// PERHATIKAN BARIS INI: Import dari folder 'dto', bukan file sejajar
import { CreateJadwalDto } from './dto/create-jadwal.dto'; 

@Controller('jadwal')
export class JadwalController {
  constructor(private readonly jadwalService: JadwalService) {}

  @Post()
  @UsePipes(new ValidationPipe({ whitelist: true }))
  create(@Body() createJadwalDto: CreateJadwalDto) {
    return this.jadwalService.create(createJadwalDto);
  }

  @Get()
  findAll() {
    return this.jadwalService.findAll();
  }

  @Get(':id')
  findOne(@Param('id') id: string) {
    return this.jadwalService.findOne(+id);
  }

  @Delete(':id')
  remove(@Param('id') id: string) {
    return this.jadwalService.remove(+id);
  }
}