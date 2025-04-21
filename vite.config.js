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
        //host: true, // Allows binding to all interfaces in local network(same network)
        //  host: "0.0.0.0", //This will allow the css to be rendered without any issue on other devices , allow access locally or externally
        host: "127.0.0.1",
        port: 3000,
        strictPort: true,
        cors: {
            //Cors configuration
            origin: "*", // Allow your Laravel app,http://10.36.30.187:8000
            methods: ["GET", "POST", "PUT", "DELETE"],
            allowedHeaders: ["Content-Type", "Authorization"],
            credentials: true,
        },
    },
});
