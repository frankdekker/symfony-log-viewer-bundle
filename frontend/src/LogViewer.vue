<script setup lang="ts">
import FileTree from '@/components/FileTree.vue'
import afterRouteOnce from '@/services/AfterRouteOnce.ts';
import handleOpenFile from '@/services/OpenFileHandler.ts';
import {useRoute} from 'vue-router';

const route   = useRoute();
const homeUri = document.head.querySelector<HTMLMetaElement>('[name=home-uri]')!.content;

// on the first route load, trigger opening a default log file
afterRouteOnce(() => handleOpenFile());

</script>

<template>
    <div class="slv-sidebar h-100 overflow-hidden">
        <header class="slv-header-height slv-header bg-body position-relative">
            <a :href="homeUri" class="slv-back text-decoration-none">
                <i class="bi bi-arrow-left-short"></i>Back
            </a>

            <h4 class="d-block text-center slv-app-title m-0">
                <i class="bi bi-substack slv-icon-color"></i>
                Log viewer </h4>
        </header>

        <FileTree/>
    </div>
    <RouterView :key="route.fullPath"></RouterView>
</template>

<style scoped>
.slv-app-title {
    color: rgb(2, 132, 199);
    height: var(--slv-min-header-height);
    line-height: var(--slv-min-header-height);
}

.slv-sidebar {
    display: grid;
    grid-template-rows: auto 1fr;
}

.slv-back {
    position: absolute;
    left: 0;
    height: var(--slv-min-header-height);
    line-height: var(--slv-min-header-height);
}

.slv-icon-color {
    color: #fff;
}
</style>
