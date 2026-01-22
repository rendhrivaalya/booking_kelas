export class UserResponseDto {
  id?: number;
  email: string;
  username: string;
  password?: string; // optional, bisa dihapus kalau gak mau expose password
}
