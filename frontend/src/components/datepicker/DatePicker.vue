<script setup lang="ts">
import DatePickerDropdown from '@/components/datepicker/DatePickerDropdown.vue';
import type DateSelection from '@/models/DateSelection';
import {formatSelection, parseSelection} from '@/services/DatePickerService';
import {formatRelativeDate, getRelativeDate} from '@/services/Dates';
import {onMounted, reactive, ref, watch} from 'vue';

const between = defineModel<string>();
const emit    = defineEmits(['change']);

const active                    = ref(false);
const dateExpanded              = ref<'none' | 'startDate' | 'endDate'>('none');
const startDate                   = reactive<DateSelection>({
    date: getRelativeDate(15, 'i', true),
    mode: 'relative',
    value: '15i',
    formatted: formatRelativeDate(15, 'i')
});
const endDate                     = reactive<DateSelection>({date: new Date(), mode: 'now', value: null, formatted: 'now'});
let currentValue: string | null = null;

onMounted(() => onBetweenChanged());
watch(between, () => onBetweenChanged());

function onBetweenChanged(): void {
    if (between.value === currentValue) {
        return;
    }

    const result = parseSelection(between.value ?? '', startDate, endDate);
    currentValue = result === false ? null : between.value ?? null;
    active.value = result !== false;
}

function onExpand(): void {
    if (active.value !== true) {
        dateExpanded.value = 'startDate';
        active.value       = true
    }
}

function onApply(): void {
    dateExpanded.value = 'none';
    setBetween(formatSelection(startDate, endDate));
}

function onClear(): void {
    dateExpanded.value  = 'none';
    startDate.date      = getRelativeDate(15, 'i', true);
    startDate.mode      = 'relative';
    startDate.value     = '15i';
    startDate.formatted = formatRelativeDate(15, 'i');
    endDate.date        = new Date();
    endDate.mode        = 'now';
    endDate.value       = null;
    endDate.formatted   = 'now';
    active.value        = false;
    setBetween('');
}

function setBetween(value: string): void {
    if (between.value === value) {
        return;
    }

    currentValue  = value;
    between.value = value;
    emit('change');
}
</script>

<template>
    <div class="position-relative">
        <div class="slv-date-picker-btn form-control" @click="onExpand">
            <button class="border-0 bg-transparent" type="button">
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
        </div>

        <date-picker-dropdown v-if="dateExpanded === 'startDate'"
                              class="slv-start-date"
                              :class="{'d-block': dateExpanded === 'startDate'}"
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
