<script setup lang="ts">
import CombiDatePicker from '@/components/datepicker/CombiDatePicker.vue';
import DateInput from '@/components/datepicker/DateInput.vue';
import type DateSelection from '@/models/DateSelection';

const selection = defineModel<DateSelection>({required: true});
defineProps<{ label: string }>();
const emit = defineEmits(['clear', 'apply']);
</script>

<template>
    <div class="dropdown-menu p-2 slv-dropdown-menu">
        <div class="calendar">
            <combi-date-picker :label="label" v-model="selection"/>
            <date-input :label="label" v-model="selection.date"/>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-secondary btn-sm me-2" @click="emit('clear')">Clear</button>
            <button class="btn btn-primary btn-sm" @click="emit('apply')">Apply</button>
        </div>
    </div>
</template>

<style scoped>
.slv-dropdown-menu {
    top: 37px;
}

.calendar {
    display: grid;
    grid-template-rows: auto 1fr;
    grid-row-gap: 10px;
}
</style>
