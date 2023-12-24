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
        <div class="btn-group btn-folder-group">
            <button type="button" class="btn btn-outline-primary text-start" @click="$emit('expand')">
                <i class="lvs-indicator bi bi-chevron-right me-2"></i>
                <span class="text-nowrap">{{ folder.path }}</span>
            </button>
        </div>

        <div class="ms-2 mt-1" v-show="expanded">
            <LogFile :file="file" v-for="file in folder.files"/>
        </div>
    </div>
</template>

<style scoped>
.btn-folder-group {
    display: grid;
}
</style>
