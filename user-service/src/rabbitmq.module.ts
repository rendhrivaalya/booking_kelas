import { Module, Global } from '@nestjs/common';
import { RabbitMQModule } from '@golevelup/nestjs-rabbitmq';

@Global() // <--- INI KUNCINYA (Supaya AuthModule bisa baca)
@Module({
  imports: [
    RabbitMQModule.forRootAsync({
  useFactory: () => ({
    uri: process.env.RABBITMQ_URI || 'amqp://guest:guest@rabbitmq:5672',
    // ... config lainnya
    connectionInitOptions: { wait: true },
    // TAMBAHKAN INI:
    connectionManagerOptions: {
        heartbeatIntervalInSeconds: 60, // Perpanjang jadi 60 detik (default cuma sebentar)
        reconnectTimeInSeconds: 5,      // Coba connect lagi tiap 5 detik kalau putus
    },
  }),
}),
  ],
  exports: [RabbitMQModule], // Export supaya module lain bisa pakai
})
export class RabbitMQConfigModule {}