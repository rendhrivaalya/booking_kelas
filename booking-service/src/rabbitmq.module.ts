import { Module, Global } from '@nestjs/common';
import { RabbitMQModule } from '@golevelup/nestjs-rabbitmq';

@Global()
@Module({
  imports: [
    RabbitMQModule.forRootAsync({
      useFactory: () => ({
        uri: process.env.RABBITMQ_URI || 'amqp://guest:guest@rabbitmq:5672',
        exchanges: [
          {
            name: 'user_exchange',
            type: 'topic',
          },
        ],
        connectionInitOptions: { wait: true },
        // --- TAMBAHAN PENTING (Supaya gak gampang disconnect) ---
        connectionManagerOptions: {
          heartbeatIntervalInSeconds: 60, // Cek detak jantung tiap 60 detik
          reconnectTimeInSeconds: 5,      // Coba connect lagi tiap 5 detik kalau putus
        },
      }),
    }),
  ],
  exports: [RabbitMQModule],
})
export class RabbitMQConfigModule {}