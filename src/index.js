import React, { Suspense, lazy } from 'react';
import ReactDOM from 'react-dom/client';
import { ErrorBoundary } from 'react-error-boundary';
import { Loading } from 'react-admin';
import './assets/css/index.css';
import {App} from "./assets/js/App";


const root = ReactDOM.createRoot(
  document.getElementById('root'),
);

const Fallback = () => (
  <Suspense fallback={<Loading />}>
  </Suspense>
);

root.render(
  <React.StrictMode>
    <ErrorBoundary FallbackComponent={Fallback}>
      <App />
    </ErrorBoundary>
  </React.StrictMode>,
);
