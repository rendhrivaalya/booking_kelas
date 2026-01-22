import { Entity, Column, PrimaryGeneratedColumn } from 'typeorm';

@Entity('users')
export class User {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ unique: true })
  email: string;

  @Column({ unique: true })
  username: string;

  @Column()
  password: string;

  // TAMBAHAN: Kolom Role
  // Defaultnya 'mahasiswa' supaya aman
  @Column({ default: 'mahasiswa' }) 
  role: string; // Isinya nanti: 'mahasiswa', 'dosen', 'staf'
}