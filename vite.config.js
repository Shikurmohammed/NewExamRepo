import path from "path";
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            "@tailwindConfig": path.resolve(__dirname, "tailwind.config.js"),
        },
    },
    optimizeDeps: {
        include: ["@tailwindConfig"],
    },
    server: {
        host: "0.0.0.0", //This will allow the css to be rendered without any issue on other devices
    },
});
