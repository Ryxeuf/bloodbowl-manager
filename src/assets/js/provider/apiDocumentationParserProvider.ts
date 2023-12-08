import { parseHydraDocumentation } from '@api-platform/api-doc-parser';
import { ENTRYPOINT, getHeaders } from '../utils';

const apiDocumentationParserProvider = async () => {
  try {
    return await parseHydraDocumentation(ENTRYPOINT, { headers: getHeaders });
    // eslint-disable-next-line
  } catch (result: any) {
    const { api, response, status } = result;
    if (status !== 401 || !response) {
      throw result;
    }
    localStorage.removeItem('token');
    return {
      api,
      response,
      status,
    };
  }
};

export default apiDocumentationParserProvider;
