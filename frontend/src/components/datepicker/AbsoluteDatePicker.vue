<script setup lang="ts">

const dateModel = defineModel<Date>();
defineProps<{ label: string }>();
const emit = defineEmits(['change']);

function getFirstDayOfMonth(date: Date): Date {
    return new Date(date.getFullYear(), date.getMonth(), 1);
}

function getFirstDayOfWeek(date: Date): Date {
    const newDate = new Date();
    console.log(date.getDay());
    date.setDate(date.getDate() - date.getDay());
    return newDate;
}

function getMonthCalendarDates(monthDate: Date | undefined): Date[] {
    const date          = getFirstDayOfWeek(getFirstDayOfMonth(monthDate ?? new Date()));
    const dates: Date[] = [];
    for (let i = 0; i < 35; i++) {
        const nextDate = new Date(date);
        nextDate.setDate(date.getDate() + i);
        dates.push(nextDate);
    }
    return dates;
}

</script>

<template>
    <div>
        <div class="day-of-the-month">
            <div v-for="date in getMonthCalendarDates(dateModel)">
                {{ date.getDate() }}
            </div>
        </div>

        <div class="input-group mt-3">
            <span class="input-group-text" id="absolute-date">{{ label }}</span>
            <input type="datetime-local" class="form-control" aria-describedby="absolute-date" value="06-12-2018T19:30">
        </div>
    </div>
</template>

<style scoped>
.day-of-the-month {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
}
</style>
