import path from "path"
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [react()],
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "./src"),
      "~bootstrap": path.resolve(__dirname, "node_modules/bootstrap"),
    },
  },
  css: {
    preprocessorOptions: {
      scss: {
        additionalData: `
          @use "sass:math";
          @use "~bootstrap/scss/functions" as *;
          @use "~bootstrap" as *;
          @use "@/styles/abstracts/variables" as var;
          @use "@/styles/abstracts/mixins" as mix;
        `
      }
    }
  }
})