import { Entity, PrimaryGeneratedColumn, Column, OneToMany } from 'typeorm';
import { Booking } from '../booking/booking.entity';
import { Jadwal } from '../jadwal/jadwal.entity'; // Import Jadwal

@Entity()
export class Kelas {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ unique: true })
  kode_kelas: string;

  @Column()
  nama_kelas: string; // Misal: Lab Komputer 1

  @Column()
  kapasitas: number;

  @Column({ nullable: true })
  deskripsi: string;

  @OneToMany(() => Booking, (booking) => booking.kelas)
  bookings: Booking[];

  // === TAMBAHAN: RELASI KE JADWAL ===
  @OneToMany(() => Jadwal, (jadwal) => jadwal.kelas)
  jadwals: Jadwal[];
}