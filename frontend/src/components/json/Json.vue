<script setup lang="ts">
import JsonValue from '@/components/json/JsonValue.vue';
import Objects from '@/services/Objects.ts';

const props = defineProps<{ data: { [key: string]: unknown } | string }>();
</script>

<template>
    <div v-for="(value, key) in props.data"
         v-bind:key="key"
         class="slv-indent"
         :class="{'slv-key-value': Objects.isObject(value) === false && Array.isArray(value) === false }">
        <div class="text-warning">{{ key }}:</div>
        <json-value :value=value></json-value>
    </div>
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
