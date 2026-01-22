import { Entity, PrimaryGeneratedColumn, Column, ManyToOne, JoinColumn } from 'typeorm';
import { Kelas } from '../kelas/kelas.entity';

@Entity()
export class Booking {
  @PrimaryGeneratedColumn()
  id: number;

  @Column()
  userId: number;

  // Kita simpan role di sini untuk history, atau validasi di service
  // Tapi biasanya role tidak disimpan di booking, cuma buat validasi.
  
  @Column({ type: 'date' })
  tanggal: string; // YYYY-MM-DD

  @Column({ type: 'time' })
  jam_mulai: string; // HH:mm:ss

  @Column({ type: 'time' })
  jam_selesai: string; // HH:mm:ss

  @Column({ type: 'text' })
  keperluan: string; // "Kelas Pengganti", "Seminar", dll

  @Column({ default: 'booked' })
  status: string;

  // Relasi ke Kelas
  @ManyToOne(() => Kelas, (kelas) => kelas.bookings)
  @JoinColumn({ name: 'kelasId' })
  kelas: Kelas;

  @Column()
  kelasId: number;
}