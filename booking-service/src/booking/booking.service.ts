import { Injectable, NotFoundException, BadRequestException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Booking } from './booking.entity';
import { CreateBookingDto } from './dto/create-booking.dto'; 
import { Jadwal } from '../jadwal/jadwal.entity';

@Injectable()
export class BookingService {
  constructor(
    @InjectRepository(Booking)
    private bookingRepository: Repository<Booking>,

    @InjectRepository(Jadwal)
    private jadwalRepository: Repository<Jadwal>,
  ) {}

  // === FITUR UTAMA: CREATE BOOKING ===
  async create(dto: CreateBookingDto) {
    const { userId, jadwalId, keperluan } = dto;

    const jadwal = await this.jadwalRepository.findOne({ 
      where: { id: jadwalId },
      relations: ['kelas'] 
    });

    if (!jadwal) {
      throw new NotFoundException(`Jadwal dengan ID ${jadwalId} tidak ditemukan`);
    }

    if (jadwal.status === 'booked') {
      throw new BadRequestException('Jadwal ini sudah dibooking, silakan pilih jadwal lain.');
    }

    const newBooking = this.bookingRepository.create({
      userId,
      jadwalId: jadwal.id,
      kelasId: jadwal.kelas.id, 
      keperluan,
      status: 'booked'
    });

    await this.bookingRepository.save(newBooking);
    await this.jadwalRepository.update(jadwal.id, { status: 'booked' });

    return newBooking;
  }

  // === FITUR: LIHAT SEMUA BOOKING ===
  async findAll() {
    return this.bookingRepository.find({
      relations: ['jadwal', 'jadwal.kelas'],
    });
  }

  // === FITUR: CEK STATUS (YANG TADI MERAH) ===
  async checkAvailability(jadwalId: number): Promise<boolean> {
    const jadwal = await this.jadwalRepository.findOne({ where: { id: jadwalId } });
    
    // PERBAIKAN DISINI:
    if (!jadwal) {
      return false; // Jika jadwal tidak ketemu, dianggap tidak available
    }

    return jadwal.status === 'available'; // Pasti return true atau false
  }
}