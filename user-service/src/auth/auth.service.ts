import {
  Injectable,
  BadRequestException,
  ConflictException,
  Logger,
  Inject, // <--- TAMBAHKAN INI
} from '@nestjs/common';

import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { ClientProxy } from '@nestjs/microservices';
import * as crypto from 'crypto';

import { User } from './user.entity';
import { RegisterDto } from './dto/register.dto';
import { LoginDto } from './dto/login.dto';

@Injectable()
export class AuthService {
  private readonly logger = new Logger(AuthService.name);

  constructor(
  @InjectRepository(User)
  private readonly userRepository: Repository<User>,
  @Inject('USER_SERVICE') private readonly client: ClientProxy, // Ganti inject-nya
) {}

  // =====================
  // HASH PASSWORD
  // =====================
  private hashPassword(password: string): string {
    return crypto
      .createHash('sha256')
      .update(password)
      .digest('hex');
  }

  // =====================
  // REGISTER
  // =====================
  async register(registerDto: RegisterDto) {
    this.logger.log('REGISTER CALLED');
    this.logger.log(`BODY: ${JSON.stringify(registerDto)}`);

    const { email, username, password, role } = registerDto;

    // 1. cek duplikat
    const existing = await this.userRepository.findOne({
      where: [{ email }, { username }],
    });

    if (existing) {
      this.logger.warn('EMAIL / USERNAME SUDAH ADA');
      throw new ConflictException('Email atau Username sudah ada');
    }

    // 2. hash password
    const hashedPassword = this.hashPassword(password);

    // 3. create entity
    const newUser = this.userRepository.create({
      email,
      username,
      password: hashedPassword,
      role: role || 'mahasiswa',
    });

    // 4. save ke DB (WAJIB pakai return value)
    let savedUser: User;

    try {
      this.logger.log('MENYIMPAN USER KE DATABASE...');
      savedUser = await this.userRepository.save(newUser);
      this.logger.log(`USER TERSIMPAN DENGAN ID = ${savedUser.id}`);
    } catch (error) {
      this.logger.error('ERROR SAAT SAVE USER', error);
      throw new BadRequestException('Gagal menyimpan user');
    }

    // 5. kirim event rabbitmq
    try {
  this.logger.log('MENGIRIM EVENT KE RABBITMQ...');
  // Gunakan emit (Standard NestJS Microservices)
  this.client.emit('user.created', {
      event: 'user.created',
      data: {
        userId: savedUser.id,
        email: savedUser.email,
        role: savedUser.role,
      },
  });
  this.logger.log('EVENT user.created TERKIRIM KE QUEUE user_queue');
} catch (error) {
  this.logger.error('ERROR RABBITMQ', error);
}

    // 6. response
    return {
      message: 'Register berhasil',
      data: {
        id: savedUser.id,
        email: savedUser.email,
        username: savedUser.username,
        role: savedUser.role,
      },
    };
  }

  // =====================
  // LOGIN
  // =====================
  async login(loginDto: LoginDto) {
    const { username, password } = loginDto;

    const user = await this.userRepository.findOne({
      where: { username },
    });

    if (!user) {
      throw new BadRequestException('Username atau Password salah');
    }

    const hashedInput = this.hashPassword(password);

    if (user.password !== hashedInput) {
      throw new BadRequestException('Username atau Password salah');
    }

    return {
      message: 'Login berhasil',
      user: {
        id: user.id,
        email: user.email,
        username: user.username,
        role: user.role,
      },
    };
  }
}
