import { Controller, Get, Post, Body, Param, Delete } from '@nestjs/common';
import { KelasService } from './kelas.service';
import { CreateKelasDto } from './kelas.dto';

@Controller('kelas')
export class KelasController {
  constructor(private readonly kelasService: KelasService) {}

  @Post()
  create(@Body() body: CreateKelasDto) {
    return this.kelasService.create(body);
  }

  @Get()
  findAll() {
    return this.kelasService.findAll();
  }

  @Get(':id')
  findOne(@Param('id') id: string) {
    return this.kelasService.findOne(+id);
  }

  @Delete(':id')
  remove(@Param('id') id: string) {
    return this.kelasService.remove(+id);
  }
}
