<script setup lang="ts">
import LogFolder from '@/components/LogFolder.vue';
import bus from '@/services/EventBus';
import {useFolderStore} from '@/stores/folders';

const folderStore = useFolderStore();

bus.on('file-deleted', () => folderStore.update());
bus.on('folder-deleted', () => folderStore.update());
</script>

<template>
    <!-- FileTree -->
    <div class="p-1 pe-2 overflow-auto">
        <div class="slv-control-layout m-0">
            <div><!-- Host: Local --></div>
            <div></div>
            <div>
                <select class="form-control p-0 border-0" v-model="folderStore.direction" v-on:change="folderStore.update">
                    <option value="desc">Newest First</option>
                    <option value="asc">Oldest First</option>
                </select>
            </div>
        </div>

        <div class="slv-loadable" v-bind:class="{ 'slv-loading': folderStore.loading }">
            <log-folder :folder="folder" :expand="true" :key="index" v-for="(folder, index) in folderStore.folders"/>
        </div>
    </div>
</template>

<style scoped>
.slv-control-layout {
    display: grid;
    grid-template-columns: auto 1fr auto;
}
</style>
