<script setup lang="ts">
import type LogFile from '@/models/LogFile';
import {ref, watch} from 'vue';
import {useRoute} from 'vue-router';

defineProps<{
    file: LogFile
}>()

const selectedFile = ref<string|null>(null);
const route        = useRoute();
watch(() => route.query.file, () => selectedFile.value = String(route.query.file));
</script>

<template>
    <!-- LogFile -->
    <div class="mb-1 lsv-btn-group btn-group">
        <router-link :to="'/log?file=' + encodeURI(file.identifier)"
                     class="btn btn-file text-start btn-outline-primary"
                     v-bind:class="{'btn-outline-primary-active': selectedFile === file.identifier }"
                     :title="file.name">
            <span class="d-block text-nowrap overflow-hidden">{{ file.name }}</span>
            <span class="d-block file-size text-secondary text-nowrap overflow-hidden">{{ file.size_formatted }}</span>
        </router-link>
        <button type="button"
                class="lsv-toggle-btn btn btn-outline-primary dropdown-toggle dropdown-toggle-split"
                v-bind:class="{'btn-outline-primary-active': selectedFile === file.identifier }"
                v-if="file.can_download">
            <i class="bi bi-three-dots-vertical"></i>
        </button>
        <ul class="dropdown-menu" v-if="file.can_download">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Separated link</a></li>
        </ul>
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
</style>
