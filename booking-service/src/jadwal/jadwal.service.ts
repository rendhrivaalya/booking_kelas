import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Jadwal } from './jadwal.entity';
import { Kelas } from '../kelas/kelas.entity';
import { CreateJadwalDto } from './dto/create-jadwal.dto';

@Injectable()
export class JadwalService {
  constructor(
    @InjectRepository(Jadwal)
    private readonly jadwalRepository: Repository<Jadwal>,
    
    @InjectRepository(Kelas)
    private readonly kelasRepository: Repository<Kelas>,
  ) {}

  // CREATE
  async create(createJadwalDto: CreateJadwalDto) {
    const { kelasId, hari, jam_mulai, jam_selesai } = createJadwalDto;

    const kelas = await this.kelasRepository.findOne({ where: { id: kelasId } });
    if (!kelas) {
      throw new NotFoundException(`Kelas dengan ID ${kelasId} tidak ditemukan`);
    }

    const jadwal = this.jadwalRepository.create({
      hari,
      jam_mulai,
      jam_selesai,
      kelas: kelas,
      kelasId: kelas.id
    });

    return await this.jadwalRepository.save(jadwal);
  }

  // FIND ALL
  async findAll() {
    return await this.jadwalRepository.find({
      relations: ['kelas'], 
    });
  }

  // === TAMBAHAN UNTUK MEMPERBAIKI ERROR ===
  
  // FIND ONE
  async findOne(id: number) {
    const jadwal = await this.jadwalRepository.findOne({ 
      where: { id },
      relations: ['kelas'] 
    });
    if (!jadwal) throw new NotFoundException(`Jadwal ID ${id} tidak ditemukan`);
    return jadwal;
  }

  // REMOVE
  async remove(id: number) {
    const result = await this.jadwalRepository.delete(id);
    if (result.affected === 0) {
      throw new NotFoundException(`Jadwal ID ${id} tidak ditemukan`);
    }
    return { message: 'Jadwal berhasil dihapus' };
  }
}