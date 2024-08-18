<script setup lang="ts">

import DatePickerDropdown from '@/components/datepicker/DatePickerDropdown.vue';
import {ref} from 'vue';

const expanded = ref(false);
defineProps<{ value: string }>();
const emit = defineEmits(['input']);

</script>

<template>
    <div>
        <button class="slv-date-picker-btn form-control dropdown-toggle" type="button" :aria-expanded="expanded" @click="expanded = !expanded">
            <i class="bi bi-calendar3"></i>
            {{ value }}
        </button>
        <date-picker-dropdown :class="{'d-block': expanded}"/>
    </div>
</template>

<style scoped>
.slv-date-picker-btn {
    --bs-border-radius: 0;
}
</style>
