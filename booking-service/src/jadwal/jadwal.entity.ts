import { Entity, PrimaryGeneratedColumn, Column, ManyToOne, JoinColumn } from 'typeorm';
import { Kelas } from '../kelas/kelas.entity'; // Pastikan import Kelas

@Entity()
export class Jadwal {
  @PrimaryGeneratedColumn()
  id: number;

  @Column()
  hari: string; // Senin, Selasa...

  @Column({ type: 'time' })
  jam_mulai: string;

  @Column({ type: 'time' })
  jam_selesai: string;

  // === TAMBAHAN: RELASI KE KELAS ===
  @ManyToOne(() => Kelas, (kelas) => kelas.jadwals, { onDelete: 'CASCADE' })
  @JoinColumn({ name: 'kelasId' })
  kelas: Kelas;

  @Column()
  kelasId: number;
}