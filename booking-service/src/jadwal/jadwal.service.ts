import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Jadwal } from './jadwal.entity';
import { Kelas } from '../kelas/kelas.entity';

@Injectable()
export class JadwalService {
  constructor(
    @InjectRepository(Jadwal)
    private readonly jadwalRepo: Repository<Jadwal>,

    @InjectRepository(Kelas)
    private readonly kelasRepo: Repository<Kelas>,
  ) {}

  // CREATE jadwal baru
  async create(data: {
    namaKelas: string;
    hari: string;
    jamMulai: string;
    jamSelesai: string;
    kelasId: number;
  }): Promise<Jadwal> {
    const kelas = await this.kelasRepo.findOneBy({ id: data.kelasId });
    if (!kelas) throw new Error('Kelas tidak ditemukan');

    const jadwal = this.jadwalRepo.create({
      ...data,
      kelas,
    });

    return await this.jadwalRepo.save(jadwal);
  }

  // READ all jadwal
  async findAll(): Promise<Jadwal[]> {
    return this.jadwalRepo.find({ relations: ['kelas'] });
  }

  // READ by id
  async findOne(id: number): Promise<Jadwal> {
    const jadwal = await this.jadwalRepo.findOne({
      where: { id },
      relations: ['kelas'],
    });
    if (!jadwal) throw new Error('Jadwal tidak ditemukan');
    return jadwal;
  }

  // UPDATE jadwal
  async update(
    id: number,
    data: {
      namaKelas?: string;
      hari?: string;
      jamMulai?: string;
      jamSelesai?: string;
      kelasId?: number;
    },
  ): Promise<Jadwal> {
    const jadwal = await this.findOne(id);

    if (data.kelasId) {
      const kelas = await this.kelasRepo.findOneBy({ id: data.kelasId });
      if (!kelas) throw new Error('Kelas tidak ditemukan');
      jadwal.kelas = kelas;
    }

    Object.assign(jadwal, data);
    return await this.jadwalRepo.save(jadwal);
  }

  // DELETE jadwal
  async remove(id: number): Promise<void> {
    await this.jadwalRepo.delete(id);
  }
}
