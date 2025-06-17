<script setup lang="ts">
import Numbers from '@/services/Numbers.ts';

const props = defineProps<{ data: unknown }>();
</script>

<template>
    <div v-if="props.data === null" class="text-info" title="type: null">null</div>
    <div v-else-if="typeof props.data === 'boolean'" class="text-info" title="type: boolean">{{ props.data ? 'true' : 'false' }}</div>
    <div v-else-if="Numbers.numeric(data)" class="text-info" :title="'type: ' + typeof props.data">{{ props.data }}</div>
    <div v-else :title="'type: ' + typeof props.data">{{ props.data }}</div>
</template>
