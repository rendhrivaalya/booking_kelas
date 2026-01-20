import { Module } from '@nestjs/common';
import { AppController } from './app.controller';
import { AppService } from './app.service';
import { KelasModule } from './kelas/kelas.module';

@Module({
  imports: [KelasModule],
  controllers: [AppController],
  providers: [AppService],
})
export class AppModule {}
