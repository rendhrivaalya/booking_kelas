import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Booking } from './booking.entity';
import { Kelas } from '../kelas/kelas.entity';
import { Jadwal } from '../jadwal/jadwal.entity';

@Injectable()
export class BookingService {
  constructor(
    @InjectRepository(Booking)
    private readonly bookingRepo: Repository<Booking>,

    @InjectRepository(Kelas)
    private readonly kelasRepo: Repository<Kelas>,

    @InjectRepository(Jadwal)
    private readonly jadwalRepo: Repository<Jadwal>,
  ) {}

  async create(data: {
    namaUser: string;
    tanggalBooking: string;
    kelasId: number;
    jadwalId: number;
  }): Promise<Booking> {
    const kelas = await this.kelasRepo.findOneBy({ id: data.kelasId });
    if (!kelas) throw new Error('Kelas tidak ditemukan');

    const jadwal = await this.jadwalRepo.findOne({
      where: { id: data.jadwalId },
    });
    if (!jadwal) throw new Error('Jadwal tidak ditemukan');

    const booking = this.bookingRepo.create({
      namaUser: data.namaUser,
      tanggalBooking: data.tanggalBooking,
      kelas,
      jadwal,
    });

    return await this.bookingRepo.save(booking);
  }

  async findAll(): Promise<Booking[]> {
    return this.bookingRepo.find({ relations: ['kelas', 'jadwal'] });
  }

  async findOne(id: number): Promise<Booking> {
    const booking = await this.bookingRepo.findOne({
      where: { id },
      relations: ['kelas', 'jadwal'],
    });
    if (!booking) throw new Error('Booking tidak ditemukan');
    return booking;
  }

  async remove(id: number): Promise<void> {
    await this.bookingRepo.delete(id);
  }
}
