import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Jadwal } from './jadwal.entity';
import { Kelas } from '../kelas/kelas.entity';

@Injectable()
export class JadwalService {
  constructor(
    @InjectRepository(Jadwal)
    private jadwalRepository: Repository<Jadwal>,
    
    @InjectRepository(Kelas)
    private kelasRepository: Repository<Kelas>,
  ) {}

  async create(data: any) {
    const kelas = await this.kelasRepository.findOne({ where: { id: data.kelasId } });
    
    if (!kelas) {
      throw new NotFoundException(`Kelas dengan ID ${data.kelasId} tidak ditemukan`);
    }

    const newJadwal = this.jadwalRepository.create({
      hari: data.hari,
      tanggal: data.tanggal,
      jam_mulai: data.jam_mulai,
      jam_selesai: data.jam_selesai,
      status: 'available',
      kelas: kelas,
      kelasId: kelas.id
    });

    return await this.jadwalRepository.save(newJadwal);
  }

  async findAll() {
    // === BAGIAN PENTING: RELATIONS 'bookings' HARUS ADA ===
    return this.jadwalRepository.find({ 
      relations: ['kelas', 'bookings'] 
    });
    // ======================================================
  }

  async update(id: number, data: any) {
    const jadwal = await this.jadwalRepository.findOne({ where: { id } });
    if (!jadwal) throw new NotFoundException(`Jadwal ${id} tidak ditemukan`);
    
    await this.jadwalRepository.update(id, data);
    return await this.jadwalRepository.findOne({ where: { id } });
  }

  async remove(id: number) {
    return await this.jadwalRepository.delete(id);
  }
}