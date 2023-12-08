import { ENTRYPOINT } from '../utils';

export const refreshAuthTokens = async (refreshToken: string) => {
  const request = new Request(`${ENTRYPOINT}/token/refresh`, {
    method: 'POST',
    body: JSON.stringify({ refresh_token: refreshToken }),
    headers: new Headers({ 'Content-Type': 'application/json' }),
  });
  const response = await fetch(request);
  if (response.status !== 200) {
    localStorage.removeItem('token');
    localStorage.removeItem('refreshToken');
    return Promise.resolve();
  }
  const { token, refresh_token: newRefresh } = await response.json();
  if (!token || !newRefresh) {
    localStorage.removeItem('token');
    localStorage.removeItem('refreshToken');
    return Promise.resolve();
  }
  localStorage.setItem('token', token);
  localStorage.setItem('refreshToken', newRefresh);
  return Promise.resolve();
};
