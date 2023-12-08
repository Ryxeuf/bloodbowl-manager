import type { ApiPlatformAdminDataProvider } from '@api-platform/admin/src/types';

export const addRefreshAuthToDataProvider = (
  provider: ApiPlatformAdminDataProvider,
  refreshAuth: () => Promise<void>,
): ApiPlatformAdminDataProvider => new Proxy(provider, {
  get(_, name) {
    /* eslint-disable-next-line  @typescript-eslint/no-explicit-any */
    return async (...args: any[]) => {
      await refreshAuth();
      return provider[name.toString()](...args);
    };
  },
});
