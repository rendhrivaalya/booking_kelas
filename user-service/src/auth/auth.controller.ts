import { Controller, Post, Body, UsePipes, ValidationPipe } from '@nestjs/common';
import { AuthService } from './auth.service';
import { RegisterDto } from './dto/register.dto';
import { LoginDto } from './dto/login.dto';

@Controller('auth')
export class AuthController {
  constructor(private readonly authService: AuthService) {}

  @Post('register')
@UsePipes(new ValidationPipe({ whitelist: true }))
async register(@Body() body: RegisterDto) {
  console.log('BODY MASUK REGISTER:', body);
  return await this.authService.register(body);
}


  @Post('login')
  @UsePipes(new ValidationPipe({ whitelist: true }))
  async login(@Body() body: LoginDto) {
    return await this.authService.login(body);
  }
}