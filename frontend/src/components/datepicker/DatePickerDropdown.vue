<script setup lang="ts">
import CombiDatePicker from '@/components/datepicker/CombiDatePicker.vue';
import DateInput from '@/components/datepicker/DateInput.vue';
import {ref} from 'vue';

const endDate = ref<Date>(new Date());
</script>

<template>
    <div class="dropdown-menu p-2 slv-dropdown-menu">
        <combi-date-picker label="End date" active-tab="now" v-model="endDate"/>
        <date-input label="End date" v-model="endDate"/>
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
