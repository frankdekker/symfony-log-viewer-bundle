<script setup lang="ts">

import DatePickerDropdown from '@/components/datepicker/DatePickerDropdown.vue';
import type DateSelection from '@/models/DateSelection';
import {reactive, ref, watch} from 'vue';

const expanded = ref(false);
defineProps<{ value: string }>();
const emit    = defineEmits(['input']);
const endDate = reactive<DateSelection>({date: new Date(), formatted: 'now'});

watch(endDate, () => {
    console.log('selection', endDate.date, endDate.formatted);
});
</script>

<template>
    <div>
        <button class="slv-date-picker-btn form-control dropdown-toggle" type="button" :aria-expanded="expanded" @click="expanded = !expanded">
            <i class="bi bi-calendar3"></i>
            {{ value }}
        </button>
        <date-picker-dropdown v-show="expanded" active-tab="now" v-model="endDate" label="End date"/>
    </div>
</template>

<style scoped>
.slv-date-picker-btn {
    --bs-border-radius: 0;
}
</style>
