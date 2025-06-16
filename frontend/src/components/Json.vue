<script setup lang="ts">
import Json from '@/components/Json.vue';

const props = defineProps<{ data: { [key: string]: unknown } | string }>();
</script>

<template>
    <div v-for="(value, key) in props.data" v-bind:key="key" :class="{'slv-key-value': typeof value !== 'object' }">
        <div class="text-warning">{{ key }}:</div>

        <div v-if="Array.isArray(value)">
            <ul>
                <li v-for="val in value">{{ val }}</li>
            </ul>
        </div>
        <div v-else-if="typeof value === 'object'" class="slv-indent">
            <json :data="<{ [key: string]: unknown }> value"></json>
        </div>
        <div v-else>{{ value }}</div>
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
