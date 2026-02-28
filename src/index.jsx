import React from 'react';
import { createRoot } from 'react-dom/client';
import App from './App';

// Mount React app to element added in Blade
const mount = () => {
  const el = document.getElementById('portal-root');
  if (!el) return;
  const root = createRoot(el);
  root.render(<App />);
};

// For HMR and multiple mounts in dev
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', mount);
} else {
  mount();
}

export default mount;
