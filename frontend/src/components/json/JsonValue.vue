<script setup lang="ts">
import Json from '@/components/json/Json.vue';
import JsonScalarValue from '@/components/json/JsonScalarValue.vue';
import JsonValue from '@/components/json/JsonValue.vue';
import Objects from '@/services/Objects.ts';

const props = defineProps<{ value: unknown }>();
</script>

<template>
    <div v-if="Array.isArray(props.value)">
        <ul class="m-0 slv-array-list">
            <li v-for="val in props.value">
                <json-value :value=val></json-value>
            </li>
        </ul>
    </div>
    <json v-else-if="Objects.isObject(props.value)" :data="props.value"></json>
    <json-scalar-value v-else :value=props.value></json-scalar-value>
</template>

<style scoped>
.slv-array-list {
    list-style: square;
}
</style>
