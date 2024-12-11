import { defineConfig } from 'vite'

import symfonyPlugin from 'vite-plugin-symfony';
import { svelte } from '@sveltejs/vite-plugin-svelte'

export default defineConfig({
  plugins: [
    svelte(),
    symfonyPlugin({
      stimulus: true
    }),
  ],
  build: {
    rollupOptions: {
      input: {
        "app": "./assets/app.js",
      }
    },
  },
});
