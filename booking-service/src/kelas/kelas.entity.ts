import { Entity, Column, PrimaryGeneratedColumn, OneToMany } from 'typeorm';
import { Jadwal } from '../jadwal/jadwal.entity';

@Entity()
export class Kelas {
  @PrimaryGeneratedColumn()
  id: number;

  @Column()
  nama_kelas: string;

  @Column()
  gedung: string;

  @Column()
  kapasitas: number;

  @OneToMany(() => Jadwal, (jadwal) => jadwal.kelas)
  jadwal: Jadwal[];
}
