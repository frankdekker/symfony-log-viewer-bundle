<script setup lang="ts">
import CombiDatePicker from '@/components/datepicker/CombiDatePicker.vue';
import {ref} from 'vue';

const startDate = ref<Date>(new Date());
</script>

<template>
    <div class="dropdown-menu p-2 slv-dropdown-menu">
        <combi-date-picker label="Start date" v-model="startDate"/>
    </div>
</template>

<style scoped>
.slv-dropdown-menu {
    top: 37px;
    right: 200px;
}
</style>
