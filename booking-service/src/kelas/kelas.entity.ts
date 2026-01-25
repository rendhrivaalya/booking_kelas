import { Entity, PrimaryGeneratedColumn, Column, OneToMany } from 'typeorm';
import { Jadwal } from '../jadwal/jadwal.entity';
import { Booking } from '../booking/booking.entity';

@Entity()
export class Kelas {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ unique: true })
  kode_kelas: string;

  @Column()
  nama_kelas: string;

  @Column()
  kapasitas: number;

  @Column({ type: 'text', nullable: true })
  deskripsi: string;

  @OneToMany(() => Jadwal, (jadwal) => jadwal.kelas)
  jadwal: Jadwal[];

  // === PASTIKAN INI SAMA PERSIS ===
  @OneToMany(() => Booking, (booking) => booking.kelas)
  bookings: Booking[];
  // ================================
}