
import { defineConfig, loadEnv } from 'vite';
import path from 'path';

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), '');
  return {
    plugins: [],
    root: '.', // Project root
    base: env.VITE_BASE_URL || '/dist/', // Base URL from env or default
    build: {
      outDir: 'dist', // Output to dist folder
      emptyOutDir: false, // Don't empty dist as it contains generated HTML
      manifest: true,
      rollupOptions: {
        input: path.resolve(__dirname, 'themes/simple/assets/main.js'),
      },
    },
    server: {
      strictPort: true,
      port: parseInt(env.VITE_PORT) || 5173
    }
  };
});
