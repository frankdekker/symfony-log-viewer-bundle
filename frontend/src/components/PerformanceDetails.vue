<script setup lang="ts">
import type Performance from '@/models/Performance';

defineProps<{
    performance?: Performance
}>()
</script>

<template>
    <div class="me-4 small d-inline-block" v-if="performance !== undefined">
        <span class="small">Memory: {{ performance.memoryUsage }}</span>
        &centerdot;
        <span class="small">Duration: {{ performance.requestTime }}</span>
        &centerdot;
        <span class="small">Version: {{ performance.version }}</span>
    </div>
</template>
