<script setup lang="ts">
import JsonData from '@/components/json/JsonData.vue';
import JsonScalarValue from '@/components/json/JsonScalarValue.vue';
import JsonValue from '@/components/json/JsonValue.vue';
import Objects from '@/services/Objects.ts';

const props = defineProps<{ path: string, data: unknown }>();
const emit  = defineEmits(['click']);

function click(value: string) {
    emit('click', value);
}
</script>

<template>
    <div v-if="Array.isArray(props.data)">
        <ul class="m-0 slv-array-list">
            <li v-for="(val, key) in props.data" :key=key>
                <json-data v-if="Objects.isObject(val)" :path="props.path + key + '.'" :data=val @click=click></json-data>
                <json-value v-else :path="props.path + key + '.'" :data=val @click=click></json-value>
            </li>
        </ul>
    </div>
    <json-data v-else-if="Objects.isObject(props.data)" :path=props.path :data="props.data" @click=click></json-data>
    <json-scalar-value v-else :path=props.path :data=props.data @click=click></json-scalar-value>
</template>

<style scoped>
.slv-array-list {
    list-style: square;
}
</style>
