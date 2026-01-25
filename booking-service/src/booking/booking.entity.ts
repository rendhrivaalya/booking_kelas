import { Entity, PrimaryGeneratedColumn, Column, ManyToOne, JoinColumn } from 'typeorm';
import { Jadwal } from '../jadwal/jadwal.entity';
import { Kelas } from '../kelas/kelas.entity'; // <--- Import Kelas

@Entity()
export class Booking {
  @PrimaryGeneratedColumn()
  id: number;

  @Column()
  userId: number;

  @Column()
  jadwalId: number;

  @Column()
  kelasId: number;

  @Column()
  keperluan: string;

  @Column()
  status: string;

  // === Relasi ke Jadwal ===
  @ManyToOne(() => Jadwal, (jadwal) => jadwal.bookings)
  jadwal: Jadwal;

  // === TAMBAHKAN RELASI KE KELAS (INI YANG BIKIN ERROR ENTITY) ===
  @ManyToOne(() => Kelas, (kelas) => kelas.bookings)
  @JoinColumn({ name: 'kelasId' })
  kelas: Kelas;
  // ==============================================================
}