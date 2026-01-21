import { Entity, PrimaryGeneratedColumn, Column, ManyToOne } from 'typeorm';
import { Kelas } from '../kelas/kelas.entity';

@Entity()
export class Jadwal {
  @PrimaryGeneratedColumn()
  id: number;

  @Column()
  namaKelas: string;

  @Column()
  hari: string;

  @Column()
  jamMulai: string;

  @Column()
  jamSelesai: string;

  @ManyToOne(() => Kelas, (kelas) => kelas.jadwal)
  kelas: Kelas;
}
