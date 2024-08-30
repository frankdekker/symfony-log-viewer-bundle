<script setup lang="ts">

import DatePickerDropdown from '@/components/datepicker/DatePickerDropdown.vue';
import type DateSelection from '@/models/DateSelection';
import {reactive, ref, watch} from 'vue';

const active       = ref(false);
const dateExpanded = ref<'none' | 'startDate' | 'endDate'>('none');
defineProps<{ value: string }>();
const emit    = defineEmits(['input']);
let startDate = reactive<DateSelection>({date: new Date(), formatted: ''});
let endDate   = reactive<DateSelection>({date: new Date(), formatted: 'now'});

watch(startDate, () => {
    console.log('selection', startDate.date, startDate.formatted);
});
</script>

<template>
    <div>
        <button class="slv-date-picker-btn form-control dropdown-toggle"
                type="button"
                @click="dateExpanded = 'startDate';active = true"
                v-if="!active">
            <i class="bi bi-calendar3"></i>
        </button>
        <button class="slv-date-picker-btn form-control dropdown-toggle" type="button" v-if="active">
            <i class="bi bi-calendar3"></i>
            <span class="ms-1" @click="dateExpanded = 'startDate' ? 'none' : 'startDate'">{{ startDate.formatted }}</span>
            ~
            <span @click="dateExpanded = dateExpanded === 'endDate' ? 'none' : 'endDate'">{{ endDate.formatted }}</span>
        </button>
        <date-picker-dropdown v-if="dateExpanded === 'startDate'"
                              :class="{'d-block': dateExpanded === 'startDate'}"
                              active-tab="relative"
                              v-model="startDate"
                              label="Start date"/>
        <date-picker-dropdown v-if="dateExpanded === 'endDate'"
                              :class="{'d-block': dateExpanded === 'endDate'}"
                              active-tab="now"
                              v-model="endDate"
                              label="End date"/>
    </div>
</template>

<style scoped>
.slv-date-picker-btn {
    --bs-border-radius: 0;
}
</style>
