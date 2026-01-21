import { Entity, PrimaryGeneratedColumn, Column, ManyToOne } from 'typeorm';
import { Kelas } from '../kelas/kelas.entity';
import { Jadwal } from '../jadwal/jadwal.entity';

@Entity()
export class Booking {
  @PrimaryGeneratedColumn()
  id: number;

  @Column()
  namaUser: string;

  @Column()
  tanggalBooking: string;

  @ManyToOne(() => Kelas)
  kelas: Kelas;

  @ManyToOne(() => Jadwal)
  jadwal: Jadwal;
}
