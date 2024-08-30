<script setup lang="ts">

import DatePickerDropdown from '@/components/datepicker/DatePickerDropdown.vue';
import type DateSelection from '@/models/DateSelection';
import {format} from '@/services/Dates';
import {reactive, ref} from 'vue';

const active       = ref(false);
const dateExpanded = ref<'none' | 'startDate' | 'endDate'>('none');
defineProps<{ value: string }>();
const emit    = defineEmits(['change']);
let startDate = reactive<DateSelection>({date: new Date(), formatted: ''});
let endDate   = reactive<DateSelection>({date: new Date(), formatted: 'now'});

function onApply(): void {
    dateExpanded.value   = 'none';
    const formattedStart = startDate.formatted === 'now' ? 'now' : format('Y-m-d H:i:s', startDate.date);
    const formattedEnd   = startDate.formatted === 'now' ? 'now' : format('Y-m-d H:i:s', endDate.date);
    emit('change', `${formattedStart}~${formattedEnd}`);
}

function onClear(): void {
    dateExpanded.value = 'none';
    startDate.date = new Date();
    startDate.formatted = '';
    endDate.date = new Date();
    endDate.formatted = 'now';
    active.value       = false;
    emit('change', '');
}
</script>

<template>
    <div class="form-control position-relative">
        <button class="slv-date-picker-btn border-0 bg-transparent" type="button" @click="dateExpanded = 'startDate';active = true">
            <i class="bi bi-calendar3"></i>
        </button>

        <button v-if="active"
                class="ms-1 slv-btn-input"
                :class="{'slv-btn-input-focus': dateExpanded === 'startDate'}"
                @click="dateExpanded = dateExpanded === 'startDate' ? 'none' : 'startDate'">{{ startDate.formatted }}
        </button>
        <span v-if="active" class="ms-1 me-1">~</span>
        <button v-if="active"
                class="slv-btn-input"
                :class="{'slv-btn-input-focus': dateExpanded === 'endDate'}"
                @click="dateExpanded = dateExpanded === 'endDate' ? 'none' : 'endDate'">{{ endDate.formatted }}
        </button>

        <date-picker-dropdown v-if="dateExpanded === 'startDate'"
                              class="slv-start-date"
                              :class="{'d-block': dateExpanded === 'startDate'}"
                              active-tab="relative"
                              v-model="startDate"
                              @clear="onClear"
                              @apply="onApply"
                              label="Start date"/>
        <date-picker-dropdown v-if="dateExpanded === 'endDate'"
                              class="slv-end-date"
                              :class="{'d-block': dateExpanded === 'endDate'}"
                              active-tab="now"
                              v-model="endDate"
                              @clear="onClear"
                              @apply="onApply"
                              label="End date"/>
    </div>
</template>

<style scoped>
.slv-date-picker-btn {
    --bs-border-radius: 0;
}

.slv-btn-input {
    border: 0;
    background-color: transparent;
}

.slv-btn-input-focus {
    text-decoration-line: underline;
    text-decoration-color: #86b7fe;
}

.slv-start-date {
    left: 0;
}

.slv-end-date {
    right: 0;
}
</style>
