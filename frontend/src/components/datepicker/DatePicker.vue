<script setup lang="ts">
import DatePickerDropdown from '@/components/datepicker/DatePickerDropdown.vue';
import type DateSelection from '@/models/DateSelection';
import {format, formatDateTime} from '@/services/Dates';
import {reactive, ref, watch} from 'vue';

const between = defineModel<string>();
const emit    = defineEmits(['change']);

const active       = ref(false);
const dateExpanded = ref<'none' | 'startDate' | 'endDate'>('none');
let startDate      = reactive<DateSelection>({date: new Date(), mode: 'relative', formatted: ''});
let endDate        = reactive<DateSelection>({date: new Date(), mode: 'now', formatted: 'now'});

watch(between, () => {
    const match = (between.value ?? '').match(/(.*)~(.*)/);
    if (match !== null && match.length === 3) {
        const start         = match[1];
        const end           = match[2];
        startDate.date      = start === 'now' ? new Date() : new Date(start);
        startDate.mode      = start === 'now' ? 'now' : 'absolute';
        startDate.formatted = start === 'now' ? 'now' : formatDateTime(startDate.date);
        endDate.date        = end === 'now' ? new Date() : new Date(end);
        endDate.mode        = start === 'now' ? 'now' : 'absolute';
        endDate.formatted   = end === 'now' ? 'now' : formatDateTime(endDate.date);
    }
});

function onApply(): void {
    dateExpanded.value   = 'none';
    const formattedStart = startDate.mode === 'now' ? 'now' : format('Y-m-d H:i:s', startDate.date);
    const formattedEnd   = startDate.mode === 'now' ? 'now' : format('Y-m-d H:i:s', endDate.date);
    if (between.value !== `${formattedStart}~${formattedEnd}`) {
        between.value = `${formattedStart}~${formattedEnd}`;
        emit('change');
    }
}

function onClear(): void {
    dateExpanded.value  = 'none';
    startDate.date      = new Date();
    startDate.mode      = 'relative';
    startDate.formatted = '';
    endDate.date        = new Date();
    endDate.mode        = 'now';
    endDate.formatted   = 'now';
    active.value        = false;
    if (between.value !== '') {
        between.value = '';
        emit('change');
    }
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
