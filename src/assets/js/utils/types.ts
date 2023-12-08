import type { JwtPayload } from 'jwt-decode';

export type BloodbowlManagerJwtPayload = JwtPayload & {
  roles: Array<string>,
  username: string
};

export type BloodbowlManagerAuthToken = () => {
  accessToken: BloodbowlManagerJwtPayload | null,
  refreshToken: string | null,
  token: string | null,
};
