<script setup lang="ts">
import SplitButtonGroup from '@/components/SplitButtonGroup.vue';
import type LogFile from '@/models/LogFile';
import bus from '@/services/EventBus';
import axios from 'axios';
import {ref, watch} from 'vue';
import {useRoute, useRouter} from 'vue-router';

defineProps<{
    file: LogFile
}>()

const toggleRef    = ref();
const selectedFile = ref<string | null>(null);
const route        = useRoute();
const router       = useRouter();
const baseUri      = axios.defaults.baseURL;
const deleteFile   = (identifier: string) => {
    axios.delete('/api/file/' + encodeURI(identifier))
        .then(() => {
            if (selectedFile.value === identifier) {
                router.push({name: 'home'});
            }
            bus.emit('file-deleted', identifier);
        });
}

watch(() => route.query.file, () => selectedFile.value = String(route.query.file));
</script>

<template>
    <!-- LogFile -->
    <split-button-group ref="toggleRef">
        <template v-slot:btn-left>
            <router-link :to="'/log?file=' + encodeURI(file.identifier)"
                         class="btn btn-file text-start btn-outline-primary"
                         v-bind:class="{'btn-outline-primary-active': selectedFile === file.identifier }"
                         :title="file.name">
                <span class="d-block text-nowrap overflow-hidden">{{ file.name }}</span>
                <span class="d-block file-size text-secondary text-nowrap overflow-hidden">{{ file.size_formatted }}</span>
            </router-link>
        </template>
        <template v-slot:btn-right>
            <button type="button"
                    class="slv-toggle-btn btn btn-outline-primary dropdown-toggle dropdown-toggle-split"
                    v-bind:class="{'btn-outline-primary-active': selectedFile === file.identifier }"
                    @click="toggleRef.toggle"
                    v-if="file.can_download || file.can_delete">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
        </template>
        <template v-slot:dropdown>
            <li>
                <a class="dropdown-item" :href="baseUri + 'api/file/' + encodeURI(file.identifier)" v-if="file.can_download">
                    <i class="bi bi-cloud-download me-3"></i>Download
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="javascript:" @click="deleteFile(file.identifier)" v-if="file.can_delete">
                    <i class="bi bi-trash3 me-3"></i>Delete
                </a>
            </li>
        </template>
    </split-button-group>
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
