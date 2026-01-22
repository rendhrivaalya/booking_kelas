import { Injectable, BadRequestException, ConflictException, Logger } from '@nestjs/common';
import { AmqpConnection } from '@golevelup/nestjs-rabbitmq';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
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
  // REGISTER
  // =====================
  async register(registerDto: RegisterDto) {
    const { email, username, password, role } = registerDto;

    // 1. Cek Duplikat
    const existing = await this.userRepository.findOne({
      where: [{ email }, { username }]
    });
    if (existing) throw new ConflictException('Email atau Username sudah ada');

    // 2. Buat User
    const newUser = this.userRepository.create({
      email,
      username,
      password,
      role: role || 'mahasiswa',
    });

    // 3. Simpan ke DB
    try {
      await this.userRepository.save(newUser);
    } catch (e) {
      this.logger.error('Gagal simpan ke DB:', e);
      throw new BadRequestException('Gagal menyimpan user.');
    }

    // 4. Kirim RabbitMQ (DENGAN PENGAMAN)
    // Kalau ini error, aplikasi TIDAK AKAN CRASH, user tetap terdaftar.
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
        }
      );
      this.logger.log(`Event user.created dikirim untuk ${newUser.email}`);
    } catch (error) {
      // Kita cuma log errornya, tapi tidak throw exception ke user
      this.logger.error('Gagal kirim event RabbitMQ:', error);
    }

    return { message: 'Register berhasil', data: newUser };
  }

  // =====================
  // LOGIN
  // =====================
  async login(loginDto: LoginDto) {
    const { username, password } = loginDto;
    const user = await this.userRepository.findOne({ where: { username } });

    if (!user || user.password !== password) {
      throw new BadRequestException('Username atau Password salah');
    }

    return { message: 'Login berhasil', user };
  }
}