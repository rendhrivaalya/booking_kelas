import { Injectable, BadRequestException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { User } from './user.entity';
import { publishUserCreated } from '../rabbitmq';

@Injectable()
export class AuthService {
  constructor(
    @InjectRepository(User)
    private userRepo: Repository<User>,
  ) {}

  async register(username: string, password: string, role: string) {
    const exist = await this.userRepo.findOne({ where: { username } });
    if (exist) {
      throw new BadRequestException('User sudah ada');
    }

    const user = this.userRepo.create({ username, password, role });
    await this.userRepo.save(user);

    // 🔔 KIRIM EVENT KE RABBITMQ
    publishUserCreated({
      id: user.id,
      username: user.username,
      role: user.role,
    });

    return { message: 'Register berhasil', user };
  }

  async login(username: string, password: string) {
    const user = await this.userRepo.findOne({
      where: { username, password },
    });

    if (!user) {
      throw new BadRequestException('Login gagal');
    }

    return {
      message: 'Login berhasil',
      user: {
        id: user.id,
        username: user.username,
        role: user.role,
      },
    };
  }
}
