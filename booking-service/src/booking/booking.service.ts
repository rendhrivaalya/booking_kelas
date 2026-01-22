import { Injectable, BadRequestException, ForbiddenException, NotFoundException, Logger } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Booking } from './booking.entity';
import { Kelas } from '../kelas/kelas.entity';
import { CreateBookingDto } from './dto/create-booking.dto';
import { RabbitSubscribe } from '@golevelup/nestjs-rabbitmq'; // <--- WAJIB IMPORT

@Injectable()
export class BookingService {
  // Tambahkan Logger supaya log-nya muncul rapi di terminal
  private readonly logger = new Logger(BookingService.name);

  constructor(
    @InjectRepository(Booking)
    private bookingRepository: Repository<Booking>,
    @InjectRepository(Kelas)
    private kelasRepository: Repository<Kelas>,
  ) {}

  // ==========================================
  // 0. RABBITMQ LISTENER (INI YANG KEMARIN HILANG)
  // ==========================================
  @RabbitSubscribe({
    exchange: 'user_exchange',  // Harus SAMA dengan di AuthService
    routingKey: 'user.created', // Harus SAMA dengan di AuthService
    queue: 'booking_user_created_queue', // Nama antrian unik untuk service ini
  })
  public async handleUserCreated(msg: any) {
    // Log ini akan membuktikan koneksi berhasil!
    this.logger.log(`[RabbitMQ] Menerima Event User Baru!`);
    this.logger.log(`Data payload: ${JSON.stringify(msg)}`);

    // (Nanti di sini bisa ditambah logic simpan user ke database lokal booking)
  }

  // ==========================================
  // LOGIC 1: Cek Ketersediaan (Bisa dipanggil Mahasiswa)
  // ==========================================
  async checkAvailability(kode_kelas: string, tanggal: string, jam_mulai: string, jam_selesai: string): Promise<boolean> {
    const kelas = await this.kelasRepository.findOne({ where: { kode_kelas } });
    if (!kelas) throw new NotFoundException(`Kelas ${kode_kelas} tidak ditemukan`);

    const existingBooking = await this.bookingRepository.createQueryBuilder('booking')
      .where('booking.kelasId = :kelasId', { kelasId: kelas.id })
      .andWhere('booking.tanggal = :tanggal', { tanggal })
      .andWhere(
        '(booking.jam_mulai < :jam_selesai AND booking.jam_selesai > :jam_mulai)',
        { jam_mulai, jam_selesai }
      )
      .getOne();

    return !existingBooking; 
  }

  // ==========================================
  // LOGIC 2: Create Booking (Hanya Dosen/Staf)
  // ==========================================
  async create(dto: CreateBookingDto) {
    const { role, kode_kelas, tanggal, jam_mulai, jam_selesai, userId, keperluan } = dto;

    // 1. Validasi Role
    if (role.toLowerCase() === 'mahasiswa') {
      throw new ForbiddenException('Maaf, Mahasiswa hanya boleh cek ketersediaan. Silakan hubungi Admin/Staf untuk booking.');
    }

    // 2. Cek Kelas
    const kelas = await this.kelasRepository.findOne({ where: { kode_kelas } });
    if (!kelas) {
      throw new NotFoundException(`Kelas dengan kode ${kode_kelas} tidak ditemukan`);
    }

    // 3. Cek Tabrakan
    const isAvailable = await this.checkAvailability(kode_kelas, tanggal, jam_mulai, jam_selesai);
    
    if (!isAvailable) {
      throw new BadRequestException(`Gagal! Kelas ${kode_kelas} pada tanggal ${tanggal} jam ${jam_mulai}-${jam_selesai} SUDAH TERISI.`);
    }

    // 4. Save Booking
    const newBooking = this.bookingRepository.create({
      userId,
      kelas,
      kelasId: kelas.id,
      tanggal,
      jam_mulai,
      jam_selesai,
      keperluan,
      status: 'booked'
    });

    return await this.bookingRepository.save(newBooking);
  }
}