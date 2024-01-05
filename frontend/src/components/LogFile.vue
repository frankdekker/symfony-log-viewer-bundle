<script setup lang="ts">
import type LogFile from '@/models/LogFile';
import {ref, watch} from 'vue';
import {useRoute} from 'vue-router';

defineProps<{
    file: LogFile
}>()

const selectedFile = ref<string|null>(null);
const route        = useRoute();
watch(() => route.query.file, () => selectedFile.value = <string>route.query.file);
</script>

<template>
    <!-- LogFile -->
    <div class="mb-1 btn-group lsv-btn-folder-group">
        <router-link :to="'/log?file=' + encodeURI(file.identifier)"
                     class="btn btn-file text-start w-100 btn-outline-primary"
                     v-bind:class="{'btn-outline-primary-active': selectedFile === file.identifier }"
                     :title="file.name">
            <span class="d-block text-nowrap overflow-hidden">{{ file.name }}</span>
            <span class="d-block file-size text-secondary text-nowrap overflow-hidden">{{ file.size_formatted }}</span>
        </router-link>
        <button class="lsv-btn-options btn btn-outline-primary p-0 text-center" v-if="file.can_download">
            <i class="bi bi-three-dots-vertical"></i>
        </button>
    </div>
</template>

<style scoped>
.file-size {
    font-size: 0.75rem;
    padding-top: 6px;
}

.btn-file {
    display: grid;
    grid-column-gap: 5px;
    grid-template-columns: 1fr auto;
}

.lsv-btn-folder-group {
    display: flex;
    flex-flow: row nowrap;
    align-items: stretch;
}

.lsv-btn-options {
    flex-grow: 0;
    width: 32px;
}
</style>
