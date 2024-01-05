<script setup lang="ts">
import FileTree from '@/components/FileTree.vue'
import {useRoute} from 'vue-router';

const route = useRoute();
</script>

<template>
    <div class="lsv-sidebar h-100 overflow-hidden">
        <header class="lsv-header-height bg-body">
            <h3 class="d-block text-center lsv-app-title">
                <i class="bi bi-substack lsv-icon-color"></i>
                Log viewer
            </h3>
        </header>

        <FileTree/>
    </div>
    <RouterView :key="route.fullPath"></RouterView>
</template>

<style scoped>
.lsv-sidebar {
    display: grid;
    grid-template-rows: auto 1fr;
}

.lsv-icon-color {
    color: #fff;
}
</style>
