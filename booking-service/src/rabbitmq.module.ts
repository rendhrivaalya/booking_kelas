import { Module, Global } from '@nestjs/common';
import { RabbitMQModule } from '@golevelup/nestjs-rabbitmq';

@Global()
@Module({
  imports: [
    RabbitMQModule.forRootAsync({
      useFactory: () => ({
        // Ganti hostname 'rabbitmq' ke '127.0.0.1' supaya bisa connect dari host Windows
        uri: process.env.RABBITMQ_URI || 'amqp://guest:guest@127.0.0.1:5672',

        exchanges: [
          {
            name: 'user_exchange',
            type: 'topic',
          },
        ],

        connectionInitOptions: { wait: true },

        // --- Tetap pakai supaya koneksi stabil ---
        connectionManagerOptions: {
          heartbeatIntervalInSeconds: 60, // Cek heartbeat tiap 60 detik
          reconnectTimeInSeconds: 5,      // Coba reconnect tiap 5 detik kalau putus
        },
      }),
    }),
  ],
  exports: [RabbitMQModule],
})
export class RabbitMQConfigModule {}
