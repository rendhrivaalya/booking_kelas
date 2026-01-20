import * as amqp from 'amqplib';

export async function publishUserCreated(data: any) {
  const connection = await amqp.connect('amqp://localhost');
  const channel = await connection.createChannel();

  const queue = 'user_created';

  await channel.assertQueue(queue, { durable: false });
  channel.sendToQueue(queue, Buffer.from(JSON.stringify(data)));

  setTimeout(() => {
    channel.close();
    connection.close();
  }, 500);
}
