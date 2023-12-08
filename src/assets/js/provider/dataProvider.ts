import { fetchHydra as baseFetchHydra, hydraDataProvider as baseHydraDataProvider } from '@api-platform/admin';
import { ENTRYPOINT, getHeaders } from '../utils';
import apiDocumentationParserProvider from './apiDocumentationParserProvider';
import { refreshAuth } from '../security/refreshAuth';
import { addRefreshAuthToDataProvider } from './addRefreshAuthProvider';

const fetchHydra = (url: URL, options = {}) => baseFetchHydra(url, {
  ...options,
  headers: getHeaders,
});

const dataProvider = addRefreshAuthToDataProvider(baseHydraDataProvider({
  entrypoint: ENTRYPOINT,
  httpClient: fetchHydra,
  apiDocumentationParser: apiDocumentationParserProvider,
}), refreshAuth);

export default dataProvider;
