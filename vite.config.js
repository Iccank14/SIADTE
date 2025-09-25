import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    tailwindcss(),
  ],
  build: {
    outDir: 'dist',      // hasil build ke folder dist
    emptyOutDir: true,   // hapus isi folder dist sebelum build baru
  },
  server: {
    port: 5173,          // default Vite port, opsional
    strictPort: true
  }
});
