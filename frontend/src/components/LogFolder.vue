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
        <div class="btn-group lsv-btn-folder-group">
            <button type="button" class="btn btn-outline-primary text-start" @click="$emit('expand')">
                <i class="lvs-indicator bi bi-chevron-right me-2"></i>
                <span class="text-nowrap">{{ folder.path }}</span>
            </button>
            <button class="lsv-btn-options btn btn-outline-primary p-0 text-center" v-if="folder.can_download">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
        </div>

        <div class="ms-2 mt-1" v-show="expanded">
            <LogFile :file="file" :key="index" v-for="(file, index) in folder.files"/>
        </div>
    </div>
</template>

<style scoped>
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
