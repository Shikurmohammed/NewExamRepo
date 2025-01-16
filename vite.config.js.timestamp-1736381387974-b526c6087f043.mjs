// vite.config.js
import path from "path";
import { defineConfig } from "file:///C:/Users/shikurm/Documents/laravel/laravel-tailwindcss/node_modules/vite/dist/node/index.js";
import laravel from "file:///C:/Users/shikurm/Documents/laravel/laravel-tailwindcss/node_modules/laravel-vite-plugin/dist/index.js";
var __vite_injected_original_dirname = "C:\\Users\\shikurm\\Documents\\laravel\\laravel-tailwindcss";
var vite_config_default = defineConfig({
  plugins: [
    laravel({
      input: [
        "resources/css/app.css",
        "resources/js/app.js"
      ],
      refresh: true
    })
  ],
  resolve: {
    alias: {
      "@tailwindConfig": path.resolve(__vite_injected_original_dirname, "tailwind.config.js")
    }
  },
  optimizeDeps: {
    include: [
      "@tailwindConfig"
    ]
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJDOlxcXFxVc2Vyc1xcXFxzaGlrdXJtXFxcXERvY3VtZW50c1xcXFxsYXJhdmVsXFxcXGxhcmF2ZWwtdGFpbHdpbmRjc3NcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIkM6XFxcXFVzZXJzXFxcXHNoaWt1cm1cXFxcRG9jdW1lbnRzXFxcXGxhcmF2ZWxcXFxcbGFyYXZlbC10YWlsd2luZGNzc1xcXFx2aXRlLmNvbmZpZy5qc1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vQzovVXNlcnMvc2hpa3VybS9Eb2N1bWVudHMvbGFyYXZlbC9sYXJhdmVsLXRhaWx3aW5kY3NzL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHBhdGggZnJvbSAncGF0aCdcbmltcG9ydCB7IGRlZmluZUNvbmZpZyB9IGZyb20gJ3ZpdGUnO1xuaW1wb3J0IGxhcmF2ZWwgZnJvbSAnbGFyYXZlbC12aXRlLXBsdWdpbic7XG5cbmV4cG9ydCBkZWZhdWx0IGRlZmluZUNvbmZpZyh7XG4gIHBsdWdpbnM6IFtcbiAgICBsYXJhdmVsKHtcbiAgICAgIGlucHV0OiBbXG4gICAgICAgICdyZXNvdXJjZXMvY3NzL2FwcC5jc3MnLFxuICAgICAgICAncmVzb3VyY2VzL2pzL2FwcC5qcycsXG4gICAgICBdLFxuICAgICAgcmVmcmVzaDogdHJ1ZSxcbiAgICB9KSxcbiAgXSxcbiAgcmVzb2x2ZToge1xuICAgIGFsaWFzOiB7XG4gICAgICAnQHRhaWx3aW5kQ29uZmlnJzogcGF0aC5yZXNvbHZlKF9fZGlybmFtZSwgJ3RhaWx3aW5kLmNvbmZpZy5qcycpLFxuICAgIH0sXG4gIH0sXG4gIG9wdGltaXplRGVwczoge1xuICAgIGluY2x1ZGU6IFtcbiAgICAgICdAdGFpbHdpbmRDb25maWcnLFxuICAgIF1cbiAgfSwgICBcbn0pO1xuIl0sCiAgIm1hcHBpbmdzIjogIjtBQUFnVyxPQUFPLFVBQVU7QUFDalgsU0FBUyxvQkFBb0I7QUFDN0IsT0FBTyxhQUFhO0FBRnBCLElBQU0sbUNBQW1DO0FBSXpDLElBQU8sc0JBQVEsYUFBYTtBQUFBLEVBQzFCLFNBQVM7QUFBQSxJQUNQLFFBQVE7QUFBQSxNQUNOLE9BQU87QUFBQSxRQUNMO0FBQUEsUUFDQTtBQUFBLE1BQ0Y7QUFBQSxNQUNBLFNBQVM7QUFBQSxJQUNYLENBQUM7QUFBQSxFQUNIO0FBQUEsRUFDQSxTQUFTO0FBQUEsSUFDUCxPQUFPO0FBQUEsTUFDTCxtQkFBbUIsS0FBSyxRQUFRLGtDQUFXLG9CQUFvQjtBQUFBLElBQ2pFO0FBQUEsRUFDRjtBQUFBLEVBQ0EsY0FBYztBQUFBLElBQ1osU0FBUztBQUFBLE1BQ1A7QUFBQSxJQUNGO0FBQUEsRUFDRjtBQUNGLENBQUM7IiwKICAibmFtZXMiOiBbXQp9Cg==
