<script setup lang="ts">
import LogFolder from '@/components/LogFolder.vue';
import {useFolderStore} from '@/stores/folders';
import {ref} from 'vue'

const selectedFolder = ref(0);
const folderStore    = useFolderStore()
</script>

<template>
    <!-- FileTree -->
    <div class="p-1 pe-2 overflow-auto">
        <div class="lsv-control-layout m-0">
            <div>
                Host: Local
            </div>
            <div></div>
            <div>
                <select class="form-control p-0 border-0" v-model="folderStore.direction" v-on:change="folderStore.update">
                    <option value="desc">Newest First</option>
                    <option value="asc">Oldest First</option>
                </select>
            </div>
        </div>

        <div class="lsv-loadable" v-bind:class="{ 'lsv-loading': folderStore.loading }">
            <LogFolder :folder="folder"
                       :expanded="index === selectedFolder"
                       v-for="(folder, index) in folderStore.folders"
                       @expand="selectedFolder = index"/>
        </div>
    </div>
</template>

<style scoped>
.lsv-control-layout {
    display: grid;
    grid-template-columns: auto 1fr auto;
}
</style>
