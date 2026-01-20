<script setup lang="ts">
import LogFolder from '@/components/LogFolder.vue';
import bus from '@/services/EventBus';
import {useFolderStore} from '@/stores/folders';
import {useHostsStore} from '@/stores/hosts';
import {watch} from 'vue';
import {useRoute, useRouter} from 'vue-router';

const folderStore = useFolderStore();
const hostsStore  = useHostsStore();
const route       = useRoute();
const router      = useRouter();

// TODO fix route path
if (route.path !== '/log' && !route.query.file) {
    for (const folder of folderStore.folders) {
        for (const file of folder.files) {
            if (file.open) {
                router.push('/log?file=' + encodeURI(file.identifier));
                break;
            }
        }
    }
}

watch(() => hostsStore.selected, () => folderStore.update());

bus.on('file-deleted', () => folderStore.update());
bus.on('folder-deleted', () => folderStore.update());
</script>

<template>
    <!-- FileTree -->
    <div class="p-1 pe-2 overflow-auto">
        <div class="slv-control-layout m-0">
            <div>
                <select class="form-select pb-0 pt-0 ps-0 slv-form-select border-0"
                        v-model="hostsStore.selected"
                        v-if="Object.keys(hostsStore.hosts).length > 0">
                    <option v-for="(name, key) in hostsStore.hosts" :value="key" :key="key">{{ name }}</option>
                </select>
            </div>
            <div></div>
            <div>
                <select class="form-select pb-0 pt-0 ps-0 slv-form-select border-0" v-model="folderStore.direction" v-on:change="folderStore.update">
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

.slv-form-select {
    padding-right: 1.8rem;
    background-position: right 0.35rem center;
}
</style>
