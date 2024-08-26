<script setup lang="ts">

import DatePickerDropdown from '@/components/datepicker/DatePickerDropdown.vue';
import type DateSelection from '@/models/DateSelection';
import {reactive, ref, watch} from 'vue';

const expanded = ref(false);
defineProps<{ value: string }>();
const emit    = defineEmits(['input']);
const startDate = reactive<DateSelection>({date: new Date(), formatted: ''});

watch(startDate, () => {
    console.log('selection', startDate.date, startDate.formatted);
});
</script>

<template>
    <div>
        <button class="slv-date-picker-btn form-control dropdown-toggle" type="button" :aria-expanded="expanded" @click="expanded = !expanded">
            <i class="bi bi-calendar3"></i>
            {{ startDate.formatted }}
        </button>
        <date-picker-dropdown v-if="expanded" :class="{'d-block': expanded}" active-tab="relative" v-model="startDate" label="Start date"/>
    </div>
</template>

<style scoped>
.slv-date-picker-btn {
    --bs-border-radius: 0;
}
</style>
