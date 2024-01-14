<script setup lang="ts">
import LogFile from '@/components/LogFile.vue';
import ButtonGroup from '@/components/ButtonGroup.vue';
import type LogFolder from '@/models/LogFolder';
import bus from '@/services/EventBus';
import axios from 'axios';
import {ref} from 'vue';
import {useRouter} from 'vue-router';

const toggleRef = ref();
const baseUri   = axios.defaults.baseURL;
const router    = useRouter();

defineProps<{
    expanded: boolean,
    folder: LogFolder
}>()

const deleteFile = (identifier: string) => {
    axios.delete('/api/folder/' + encodeURI(identifier))
        .then(() => {
            router.push({name: 'home'});
            bus.emit('folder-deleted', identifier);
        });
}

</script>

<template>
    <!-- LogFolder -->
    <div class="folder-group mt-1" :aria-expanded="expanded">
        <button-group ref="toggleRef" alignment="right" :split="folder.can_download || folder.can_delete">
            <template v-slot:btn_left>
                <button type="button" class="btn btn-outline-primary text-start w-100" @click="$emit('expand')">
                    <i class="lvs-indicator bi bi-chevron-right me-2"></i>
                    <span class="text-nowrap">{{ folder.path }}</span>
                </button>
            </template>
            <template v-slot:btn_right>
                <button type="button"
                        class="slv-toggle-btn btn btn-outline-primary dropdown-toggle dropdown-toggle-split"
                        @click="toggleRef.toggle"
                        v-if="folder.can_download || folder.can_delete">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
            </template>
            <template v-slot:dropdown>
                <li>
                    <a class="dropdown-item" :href="baseUri + 'api/folder/' + encodeURI(folder.identifier)" v-if="folder.can_download">
                        <i class="bi bi-cloud-download me-3"></i>Download
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="javascript:" @click="deleteFile(folder.identifier)" v-if="folder.can_delete">
                        <i class="bi bi-trash3 me-3"></i>Delete
                    </a>
                </li>
            </template>
        </button-group>

        <div class="ms-2 mt-1" v-show="expanded">
            <log-file :file="file" :key="index" v-for="(file, index) in folder.files"/>
        </div>
    </div>
</template>
