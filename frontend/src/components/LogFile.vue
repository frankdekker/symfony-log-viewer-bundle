<script setup lang="ts">
import ButtonGroup from '@/components/ButtonGroup.vue';
import type LogFile from '@/models/LogFile';
import ParameterBag from '@/models/ParameterBag';
import bus from '@/services/EventBus';
import {useHostsStore} from '@/stores/hosts';
import {useSearchStore} from '@/stores/search';
import axios from 'axios';
import {ref} from 'vue';
import {useRouter} from 'vue-router';

defineProps<{
    file: LogFile
}>();

const toggleRef   = ref();
const router      = useRouter();
const searchStore = useSearchStore();
const hostsStore  = useHostsStore();

const baseUri    = axios.defaults.baseURL;
const deleteFile = (identifier: string) => {
    const params = new ParameterBag().set('host', hostsStore.selected, 'localhost').all();
    axios.delete('/api/file/' + encodeURI(identifier), {params: params})
        .then(() => {
            if (searchStore.files.length === 0) {
                router.push({name: 'home'});
            }
            bus.emit('file-deleted', identifier);
        });
}

const navigate = (identifier: string, multiSelect: boolean) => {
    if (multiSelect) {
        searchStore.toggleFile(identifier);
    } else {
        searchStore.setFile(identifier);
    }
    if (searchStore.files.length === 0) {
        router.push({name: 'home'});
        return;
    }
    router.push('/log?' + searchStore.toQueryString());
}
</script>

<template>
    <!-- LogFile -->
    <button-group ref="toggleRef" alignment="right" :split="file.can_download || file.can_delete" class="mb-1" :hide-on-selected="true">
        <template v-slot:btn_left>
            <a @click="(event) => {event.preventDefault(); navigate(file.identifier, event.ctrlKey || event.metaKey)}"
               href="#"
               class="btn btn-file text-start btn-outline-primary w-100"
               v-bind:class="{'btn-outline-primary-active': searchStore.files.includes(file.identifier) }"
               :title="file.name">
                <span class="d-block text-nowrap overflow-hidden">{{ file.name }}</span>
                <span class="d-block file-size text-secondary text-nowrap overflow-hidden">{{ file.size_formatted }}</span>
            </a>
        </template>
        <template v-slot:btn_right>
            <button type="button"
                    class="slv-toggle-btn btn btn-outline-primary dropdown-toggle dropdown-toggle-split"
                    v-bind:class="{'btn-outline-primary-active': searchStore.files.includes(file.identifier) }"
                    @click="toggleRef.toggle">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
        </template>
        <template v-slot:dropdown>
            <li>
                <a class="dropdown-item" href="#" @click="(event) => {event.preventDefault(); navigate(file.identifier, true)}">
                    <i class="bi bi-check2-circle me-3"></i>
                    {{ searchStore.files.includes(file.identifier) ? 'Remove from selection' : 'Add to selection' }}
                    <code>(ctrl+click)</code>
                </a>
            </li>
            <li v-if="file.can_download">
                <a class="dropdown-item"
                   :href="baseUri + 'api/file/' + encodeURI(file.identifier) + '?' + new ParameterBag().set('host', hostsStore.selected, 'localhost').toString()">
                    <i class="bi bi-cloud-download me-3"></i>Download
                </a>
            </li>
            <li v-if="file.can_delete">
                <a class="dropdown-item" href="javascript:" @click="deleteFile(file.identifier)">
                    <i class="bi bi-trash3 me-3"></i>Delete
                </a>
            </li>
        </template>
    </button-group>
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
