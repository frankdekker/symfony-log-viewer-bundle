<script setup lang="ts">
import Numbers from '@/services/Numbers.ts';
import {trim} from '@/services/Strings.ts';

const props = defineProps<{ path: string, data: unknown }>();
const emit  = defineEmits(['click']);

function click(value: unknown) {
    emit('click', `${trim(props.path, '.')}="${value}"`);
}
</script>

<template>
    <div v-if="props.data === null">
        <span class="slv-json-value text-info" title="type: null" @click="click(props.data)">null</span>
    </div>
    <div v-else-if="typeof props.data === 'boolean'">
        <span class="slv-json-value text-info" title="type: boolean" @click="click(props.data)">{{ props.data ? 'true' : 'false' }}</span>
    </div>
    <div v-else-if="Numbers.numeric(data)">
        <span class="slv-json-value text-info" :title="'type: ' + typeof props.data" @click="click(props.data)">{{ props.data }}</span>
    </div>
    <div v-else>
        <span class="slv-json-value" :title="'type: ' + typeof props.data" @click="click(props.data)">{{ props.data }}</span>
    </div>
</template>

<style scoped>
.slv-json-value:hover {
    text-decoration: underline;
    cursor: pointer;
}
</style>
