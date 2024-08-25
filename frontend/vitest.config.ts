import {fileURLToPath} from 'node:url'
import {configDefaults, defineConfig, mergeConfig} from 'vitest/config'
import viteConfig from './vite.config'

export default defineConfig(
    options => mergeConfig(
        viteConfig(options),
        defineConfig({
            test: {
                environment: 'jsdom',
                exclude: [...configDefaults.exclude, 'e2e/**'],
                root: fileURLToPath(new URL('./', import.meta.url))
            }
        })
    )
);
