import { Injectable, BadRequestException } from '@nestjs/common';
import { publishUserCreated } from '../rabbitmq';


@Injectable()
export class AuthService {
  private users: any[] = [];

  register(username: string, password: string) {
    const exist = this.users.find(u => u.username === username);
    if (exist) {
      throw new BadRequestException('User sudah ada');
    }

    const user = { username, password };
    this.users.push(user);
    publishUserCreated({ username });
    return { message: 'Register berhasil', user };
  }

  login(username: string, password: string) {
    const user = this.users.find(
      u => u.username === username && u.password === password,
    );

    if (!user) {
      throw new BadRequestException('Login gagal');
    }

    return { message: 'Login berhasil', user };
  }
}
