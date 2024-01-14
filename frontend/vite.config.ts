import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import {fileURLToPath, URL} from 'node:url'
import {defineConfig} from 'vite'

// https://vitejs.dev/config/
export default defineConfig((options) => {
    return {
        plugins: [
            vue(),
            vueJsx()
        ],
        base: '/bundles/fdlogviewer/',
        build: {
            sourcemap: options.mode === 'development',
            emptyOutDir: true,
            copyPublicDir: false,
            cssCodeSplit: false,
            outDir: '../src/Resources/public',
            manifest: true,
            minify: options.mode === 'production',
            rollupOptions: {
                input: 'src/main.ts'
            }
        },
        resolve: {
            alias: {
                '@': fileURLToPath(new URL('./src', import.meta.url))
            }
        }
    }
})
