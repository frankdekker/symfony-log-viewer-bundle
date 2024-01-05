<script setup lang="ts">
import LogFile from '@/components/LogFile.vue';
import type LogFolder from '@/models/LogFolder';

defineProps<{
    expanded: boolean,
    folder: LogFolder
}>()
</script>

<template>
    <!-- LogFolder -->
    <div class="folder-group mt-1" :aria-expanded="expanded">
        <div class="lsv-btn-group btn-group">
            <button type="button" class="btn btn-outline-primary text-start" @click="$emit('expand')">
                <i class="lvs-indicator bi bi-chevron-right me-2"></i>
                <span class="text-nowrap">{{ folder.path }}</span>
            </button>
            <button type="button" class="lsv-toggle-btn btn btn-outline-primary dropdown-toggle dropdown-toggle-split" v-if="folder.can_download">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu" v-if="folder.can_download">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Separated link</a></li>
            </ul>
        </div>

        <div class="ms-2 mt-1" v-show="expanded">
            <LogFile :file="file" :key="index" v-for="(file, index) in folder.files"/>
        </div>
    </div>
</template>
