import { Entity, Column, PrimaryGeneratedColumn } from 'typeorm';

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
}