import type { AuthProvider } from 'react-admin';
import { addRefreshAuthToAuthProvider } from 'react-admin';
import { ENTRYPOINT, getAuthTokensFromLocalStorage } from '../utils';
import { refreshAuth } from '../security/refreshAuth';

const authProvider: AuthProvider = {
  async login({ username, password }) {
    const request = new Request(`${ENTRYPOINT}/login`, {
      method: 'POST',
      body: JSON.stringify({ username, password }),
      headers: new Headers({ 'Content-Type': 'application/json' }),
    });
    const response = await fetch(request);
    if (response.status < 200 || response.status >= 300) {
      throw new Error(response.statusText);
    }
    const { token, refresh_token: refreshToken } = await response.json();
    localStorage.setItem('token', token);
    localStorage.setItem('refreshToken', refreshToken);
  },
  logout: () => {
    localStorage.removeItem('token');
    return Promise.resolve();
  },
  checkAuth: () => {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        return Promise.reject();
      }
      const { accessToken } = getAuthTokensFromLocalStorage();
      if (new Date().getTime() / 1000 > (accessToken?.exp ?? 0)) {
        return Promise.reject();
      }
      return Promise.resolve();
    } catch (e) {
      // override possible jwtDecode error
      return Promise.reject();
    }
  },
  checkError: (err: { status: number; response: { status: number; }; }) => {
    if ([401, 403].includes(err?.status || err?.response?.status)) {
      localStorage.removeItem('token');
      return Promise.reject();
    }
    return Promise.resolve();
  },
  getIdentity: () => {
    const token = localStorage.getItem('token');
    if (!token) {
      return Promise.reject(new Error('Not connected'));
    }
    const { accessToken } = getAuthTokensFromLocalStorage();
    if (accessToken) {
      return Promise.resolve({
        id: accessToken.username,
        fullName: accessToken.username,
      });
    }
    return Promise.reject(new Error('Not connected'));
  },
  getPermissions: () => {
    const token = localStorage.getItem('token');
    if (!token) {
      return Promise.reject();
    }
    const { accessToken } = getAuthTokensFromLocalStorage();
    if (!accessToken) {
      return Promise.reject();
    }
    const { roles } = accessToken;
    return roles ? Promise.resolve(roles) : Promise.reject();
  },
};

export default addRefreshAuthToAuthProvider(authProvider, refreshAuth);
