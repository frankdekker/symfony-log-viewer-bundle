<script setup lang="ts">
import Json from '@/components/json/Json.vue';
import JsonScalarValue from '@/components/json/JsonScalarValue.vue';
import JsonValue from '@/components/json/JsonValue.vue';
import Objects from '@/services/Objects.ts';

const props = defineProps<{ data: unknown }>();
</script>

<template>
    <div v-if="Array.isArray(props.data)">
        <ul class="m-0 slv-array-list">
            <li v-for="val in props.data">
                <json v-if="Objects.isObject(val)" :data=val></json>
                <json-value v-else :data=val></json-value>
            </li>
        </ul>
    </div>
    <json v-else-if="Objects.isObject(props.data)" :data="props.data"></json>
    <json-scalar-value v-else :data=props.data></json-scalar-value>
</template>

<style scoped>
.slv-array-list {
    list-style: square;
}
</style>
