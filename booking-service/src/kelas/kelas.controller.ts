import {
  Controller,
  Get,
  Post,
  Put,
  Delete,
  Body,
  Param,
} from '@nestjs/common';
import { KelasService } from './kelas.service';

@Controller('kelas')
export class KelasController {
  constructor(private readonly kelasService: KelasService) {}

  // GET /kelas
  @Get()
  findAll() {
    return this.kelasService.findAll();
  }

  // POST /kelas
  @Post()
  create(@Body() body) {
    return this.kelasService.create(body);
  }

  // PUT /kelas/:id
  @Put(':id')
  update(@Param('id') id: string, @Body() body) {
    return this.kelasService.update(Number(id), body);
  }

  // DELETE /kelas/:id
  @Delete(':id')
  remove(@Param('id') id: string) {
    return this.kelasService.remove(Number(id));
  }
}