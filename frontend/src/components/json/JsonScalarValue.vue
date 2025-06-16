<script setup lang="ts">
import Numbers from '@/services/Numbers.ts';

const props = defineProps<{ value: unknown }>();
</script>

<template>
    <div v-if="props.value === null" class="text-info" title="type: null">null</div>
    <div v-else-if="typeof props.value === 'boolean'" class="text-info" title="type: boolean">{{ props.value ? 'true' : 'false' }}</div>
    <div v-else-if="Numbers.numeric(value)" class="text-info" :title="'type: ' + typeof props.value">{{ props.value }}</div>
    <div v-else :title="'type: ' + typeof props.value">{{ props.value }}</div>
</template>
