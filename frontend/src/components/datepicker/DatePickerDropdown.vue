<script setup lang="ts">
import CombiDatePicker from '@/components/datepicker/CombiDatePicker.vue';
import DateInput from '@/components/datepicker/DateInput.vue';
import {ref} from 'vue';

const startDate = ref<Date>(new Date());
const endDate   = ref<Date>(new Date());
</script>

<template>
    <div class="dropdown-menu p-2 slv-dropdown-menu">
        <combi-date-picker label="Start date" active-tab="relative" v-model="startDate"/>
        <combi-date-picker label="End date" active-tab="now" v-model="endDate"/>
        <date-input label="Start date" v-model="startDate"/>
        <date-input label="End date" v-model="endDate"/>
    </div>
</template>

<style scoped>
.slv-dropdown-menu {
    top: 37px;
    right: 200px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-column-gap: 20px;
    grid-row-gap: 10px;
}
</style>
