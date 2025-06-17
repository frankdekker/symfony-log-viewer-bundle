<script setup lang="ts">
import JsonValue from '@/components/json/JsonValue.vue';
import Objects from '@/services/Objects.ts';

const props = defineProps<{path: string, data: { [key: string]: unknown } | string}>();
const emit  = defineEmits(['click']);

function click(value: string) {
    emit('click', value);
}
</script>

<template>
    <div v-if="Objects.isObject(props.data)" v-for="(value, key) in props.data"
         v-bind:key="key"
         class="slv-indent"
         :class="{'slv-key-value': Objects.isObject(value) === false && Array.isArray(value) === false }">
        <div class="text-warning">{{ key }}:</div>
        <json-value :path="props.path + key + '.'" :data=value @click=click></json-value>
    </div>
    <json-value v-else :path=props.path :data=props.data @click=click></json-value>
</template>

<style scoped>
.slv-key-value {
    display: grid;
    grid-column-gap: 10px;
    grid-template-columns: auto 1fr;
}

.slv-indent {
    padding-left: 15px;
}
</style>
