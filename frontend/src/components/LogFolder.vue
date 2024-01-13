<script setup lang="ts">
import LogFile from '@/components/LogFile.vue';
import SplitButtonGroup from '@/components/SplitButtonGroup.vue';
import type LogFolder from '@/models/LogFolder';
import {ref} from 'vue';

const toggleRef = ref();

defineProps<{
    expanded: boolean,
    folder: LogFolder
}>()
</script>

<template>
    <!-- LogFolder -->
    <div class="folder-group mt-1" :aria-expanded="expanded">
        <split-button-group ref="toggleRef">
            <template v-slot:btn-left>
                <button type="button" class="btn btn-outline-primary text-start" @click="$emit('expand')">
                    <i class="lvs-indicator bi bi-chevron-right me-2"></i>
                    <span class="text-nowrap">{{ folder.path }}</span>
                </button>
            </template>
            <template v-slot:btn-right>
                <button type="button"
                        class="slv-toggle-btn btn btn-outline-primary dropdown-toggle dropdown-toggle-split"
                        @click="toggleRef.toggle"
                        v-if="folder.can_download">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
            </template>
            <template v-slot:dropdown>
                <li><a class="dropdown-item" :href="folder.download_url">Download</a></li>
            </template>
        </split-button-group>

        <div class="ms-2 mt-1" v-show="expanded">
            <LogFile :file="file" :key="index" v-for="(file, index) in folder.files"/>
        </div>
    </div>
</template>
