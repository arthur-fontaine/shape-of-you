import { defineConfig } from 'vite'

import symfonyPlugin from 'vite-plugin-symfony';
import { svelte } from '@sveltejs/vite-plugin-svelte'
import tailwindcss from '@tailwindcss/vite'


export default defineConfig({
  plugins: [
    svelte(),
    symfonyPlugin({
      stimulus: true
    }),
    tailwindcss(),
  ],
  build: {
    rollupOptions: {
      input: {
        "app": "./src/UI/app.js",
      }
    },
  },
});
