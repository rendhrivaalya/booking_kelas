import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Kelas } from './kelas.entity';

@Injectable()
export class KelasService {
  constructor(
    @InjectRepository(Kelas)
    private readonly kelasRepository: Repository<Kelas>,
  ) {}

  // GET semua kelas
  findAll() {
    return this.kelasRepository.find();
  }

  // POST tambah kelas
  create(data: Partial<Kelas>) {
    const kelas = this.kelasRepository.create(data);
    return this.kelasRepository.save(kelas);
  }

  // PUT update kelas
  update(id: number, data: Partial<Kelas>) {
    return this.kelasRepository.update(id, data);
  }

  // DELETE hapus kelas
  remove(id: number) {
    return this.kelasRepository.delete(id);
  }
}