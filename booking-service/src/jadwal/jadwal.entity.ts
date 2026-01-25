import { Entity, PrimaryGeneratedColumn, Column, ManyToOne, JoinColumn, OneToMany } from 'typeorm'; // Tambah OneToMany
import { Kelas } from '../kelas/kelas.entity';
import { Booking } from '../booking/booking.entity'; // Import Booking

@Entity()
export class Jadwal {
  @PrimaryGeneratedColumn()
  id: number;

  @Column()
  hari: string;

  @Column({ type: 'date', nullable: true })
  tanggal: string;

  @Column()
  jam_mulai: string;

  @Column()
  jam_selesai: string;

  @Column({ default: 'available' })
  status: string;

  @Column()
  kelasId: number;

  @ManyToOne(() => Kelas)
  @JoinColumn({ name: 'kelasId' })
  kelas: Kelas;

  // === TAMBAHKAN RELASI INI DI PALING BAWAH ===
  @OneToMany(() => Booking, (booking) => booking.jadwal)
  bookings: Booking[];
  // ============================================
}