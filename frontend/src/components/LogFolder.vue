<script setup lang="ts">
import ButtonGroup from '@/components/ButtonGroup.vue';
import LogFile from '@/components/LogFile.vue';
import type LogFileModel from '@/models/LogFile';
import type LogFolder from '@/models/LogFolder';
import ParameterBag from '@/models/ParameterBag';
import bus from '@/services/EventBus';
import {useHostsStore} from '@/stores/hosts';
import {useSearchStore} from '@/stores/search';
import axios from 'axios';
import {onMounted, ref} from 'vue';
import {useRouter} from 'vue-router';

const toggleRef   = ref();
const baseUri     = axios.defaults.baseURL;
const router      = useRouter();
const expanded    = ref(false);
const hostsStore  = useHostsStore();
const searchStore = useSearchStore();

const props = defineProps<{
    expand: boolean,
    folder: LogFolder
}>()

const deleteFile = (identifier: string) => {
    const params = new ParameterBag().set('host', hostsStore.selected, 'localhost').all();
    axios.delete('/api/folder/' + encodeURI(identifier), {params: params})
        .then(() => {
            router.push({name: 'home'});
            bus.emit('folder-deleted', identifier);
        });
}

const selectAll = (files: LogFileModel[]) => {
    files.forEach(file => searchStore.addFile(file.identifier));
    router.push('/log?' + searchStore.toQueryString());
}

onMounted(() => expanded.value = props.expand);
</script>

<template>
    <!-- LogFolder -->
    <div class="folder-group mt-1" :aria-expanded="expanded">
        <button-group ref="toggleRef" alignment="right" :split="folder.can_download || folder.can_delete" :hide-on-selected="true">
            <template v-slot:btn_left>
                <button type="button" class="btn btn-outline-primary text-start w-100" @click="expanded = !expanded">
                    <i class="slv-indicator bi bi-chevron-right me-2"></i>
                    <span class="text-nowrap">{{ folder.path }}</span>
                </button>
            </template>
            <template v-slot:btn_right>
                <button type="button" class="slv-toggle-btn btn btn-outline-primary dropdown-toggle dropdown-toggle-split" @click="toggleRef.toggle">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
            </template>
            <template v-slot:dropdown>
                <li>
                    <a class="dropdown-item" href="javascript:" @click="selectAll(folder.files)">
                        <i class="bi bi-check2-circle me-3"></i>
                        Select all
                    </a>
                </li>
                <li v-if="folder.can_download">
                    <a class="dropdown-item"
                       :href="baseUri + 'api/folder/' + encodeURI(folder.identifier) + '?' + new ParameterBag().set('host', hostsStore.selected, 'localhost').toString()">
                        <i class="bi bi-cloud-download me-3"></i>Download
                    </a>
                </li>
                <li v-if="folder.can_delete">
                    <a class="dropdown-item" href="javascript:" @click="deleteFile(folder.identifier)">
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
