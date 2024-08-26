<script setup lang="ts">
import CombiDatePicker from '@/components/datepicker/CombiDatePicker.vue';
import DateInput from '@/components/datepicker/DateInput.vue';
import type DateSelection from '@/models/DateSelection';

const selection = defineModel<DateSelection>({required: true});
defineProps<{ label: string, activeTab: 'absolute' | 'relative' | 'now' }>();
</script>

<template>
    <div class="dropdown-menu p-2 slv-dropdown-menu">
        <combi-date-picker :label="label" :active-tab="activeTab" v-model="selection"/>
        <date-input :label="label" v-model="selection.date"/>
    </div>
</template>

<style scoped>
.slv-dropdown-menu {
    top: 37px;
    right: 200px;
    display: grid;
    grid-template-rows: auto 1fr;
    grid-row-gap: 10px;
}
</style>
