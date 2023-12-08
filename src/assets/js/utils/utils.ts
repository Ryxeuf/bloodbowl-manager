import { jwtDecode } from 'jwt-decode';
import type {
  BloodbowlManagerAuthToken, BloodbowlManagerJwtPayload,
} from './index';

export const getHeaders = () => (localStorage.getItem('token') ? {
  Authorization: `Bearer ${localStorage.getItem('token')}`,
} : { Authorization: '' });

export const getAuthTokensFromLocalStorage: BloodbowlManagerAuthToken = () => {
  const token = localStorage.getItem('token');
  const refreshToken = localStorage.getItem('refreshToken');
  if (!token || !refreshToken) {
    return { accessToken: null, refreshToken: null, token: null };
  }
  const decodedToken: BloodbowlManagerJwtPayload = jwtDecode(token);
  return { accessToken: decodedToken, refreshToken, token };
};
