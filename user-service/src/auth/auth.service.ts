import {
  Injectable,
  BadRequestException,
  ConflictException,
  Logger,
} from '@nestjs/common';

import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { AmqpConnection } from '@golevelup/nestjs-rabbitmq';
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
    private readonly amqpConnection: AmqpConnection,
  ) {}

  // =====================
  // HELPER HASH PASSWORD (BUILT-IN NODE)
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
    const { email, username, password, role } = registerDto;

    // 1. Cek duplikat email / username
    const existing = await this.userRepository.findOne({
      where: [{ email }, { username }],
    });

    if (existing) {
      throw new ConflictException('Email atau Username sudah ada');
    }

    // 2. HASH PASSWORD
    const hashedPassword = this.hashPassword(password);

    // 3. Buat user baru
    const newUser = this.userRepository.create({
      email,
      username,
      password: hashedPassword,
      role: role || 'mahasiswa',
    });

    // 4. Simpan ke DB
    await this.userRepository.save(newUser);

    // 5. Kirim event RabbitMQ (optional)
    try {
      await this.amqpConnection.publish(
        'user_exchange',
        'user.created',
        {
          event: 'user.created',
          data: {
            userId: newUser.id,
            email: newUser.email,
            role: newUser.role,
          },
        },
      );
    } catch (error) {
      this.logger.error('RabbitMQ error:', error);
    }

    // 6. Response
    return {
      message: 'Register berhasil',
      data: {
        id: newUser.id,
        email: newUser.email,
        username: newUser.username,
        role: newUser.role,
      },
    };
  }

  // =====================
  // LOGIN (USERNAME + PASSWORD)
  // =====================
  async login(loginDto: LoginDto) {
    const { username, password } = loginDto;

    // 1. Cari user
    const user = await this.userRepository.findOne({
      where: { username },
    });

    if (!user) {
      throw new BadRequestException('Username atau Password salah');
    }

    // 2. HASH password input
    const hashedInput = this.hashPassword(password);

    // 3. Bandingkan hash
    if (user.password !== hashedInput) {
      throw new BadRequestException('Username atau Password salah');
    }

    // 4. Login sukses
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
