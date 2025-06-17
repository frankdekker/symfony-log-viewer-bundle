<script setup lang="ts">
import Json from '@/components/json/Json.vue';
import JsonScalarValue from '@/components/json/JsonScalarValue.vue';
import JsonValue from '@/components/json/JsonValue.vue';
import Objects from '@/services/Objects.ts';

const props = defineProps<{ path: string, data: unknown }>();
</script>

<template>
    <div v-if="Array.isArray(props.data)">
        <ul class="m-0 slv-array-list">
            <li v-for="(val, key) in props.data">
                <json v-if="Objects.isObject(val)" :path="props.path + key + '.'" :data=val></json>
                <json-value v-else :path="props.path + key + '.'" :data=val></json-value>
            </li>
        </ul>
    </div>
    <json v-else-if="Objects.isObject(props.data)" :path=props.path :data="props.data"></json>
    <json-scalar-value v-else :path=props.path :data=props.data></json-scalar-value>
</template>

<style scoped>
.slv-array-list {
    list-style: square;
}
</style>
