<script setup lang="ts">
import Numbers from '@/services/Numbers.ts';
import Strings from '@/services/Strings.ts';

const props = defineProps<{ path: string, data: unknown }>();
</script>

<template>
    <span>{{ Strings.trim(props.path, '.') }}</span>
    <div v-if="props.data === null" class="text-info" title="type: null">null</div>
    <div v-else-if="typeof props.data === 'boolean'" class="text-info" title="type: boolean">{{ props.data ? 'true' : 'false' }}</div>
    <div v-else-if="Numbers.numeric(data)" class="text-info" :title="'type: ' + typeof props.data">{{ props.data }}</div>
    <div v-else :title="'type: ' + typeof props.data">{{ props.data }}</div>
</template>
