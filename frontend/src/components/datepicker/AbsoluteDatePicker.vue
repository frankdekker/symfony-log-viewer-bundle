<script setup lang="ts">
import {getMonthCalendarDates, isSameDay, isSameMonth} from '@/services/Dates';

const dateModel = defineModel<Date>();
dateModel.value ??= new Date();
defineProps<{ label: string }>();
const emit = defineEmits(['change']);

</script>

<template>
    <div>
        <div class="week-days">
            <div v-for="day in ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']" class="text-center">
                {{ day }}
            </div>
        </div>

        <div class="day-of-the-month">
            <button v-for="date in getMonthCalendarDates(dateModel)"
                    class="btn btn-outline-primary border-0"
                    :class="{'btn-outline-primary-active': isSameDay(date, dateModel), 'opacity-50': isSameMonth(date, dateModel) === false}">
                {{ date.getDate() }}
            </button>
        </div>

        <div class="input-group mt-3">
            <span class="input-group-text" id="absolute-date">{{ label }}</span>
            <input type="datetime-local" class="form-control" aria-describedby="absolute-date" value="06-12-2018T19:30">
        </div>
    </div>
</template>

<style scoped>
.day-of-the-month, .week-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
}
</style>
