import { refreshAuthTokens } from './refreshAuthTokens';
import { getAuthTokensFromLocalStorage } from '../utils';

export const refreshAuth = () => {
  const { accessToken, refreshToken } = getAuthTokensFromLocalStorage();
  if (!accessToken || !refreshToken || !accessToken.exp) {
    return Promise.resolve();
  }
  if (accessToken.exp < new Date().getTime() / 1000) {
    return refreshAuthTokens(refreshToken);
  }
  return Promise.resolve();
};
