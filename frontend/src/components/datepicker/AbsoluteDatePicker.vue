<script setup lang="ts">
import {getHours, getMonthCalendarDates, getMonths, getYears, isSameDay, isSameMonth} from '@/services/Dates';
import {ref, watch} from 'vue';

const currentDate  = ref(new Date());
const selectedDate = defineModel<Date>({required: true});
defineProps<{ label: string }>();
const emit = defineEmits(['change']);

watch(selectedDate, () => currentDate.value = new Date(selectedDate.value));

function updateModel(event: Event, field: 'time' | 'month' | 'year'): void {
    switch (field) {
        case 'month':
            currentDate.value!.setMonth(parseInt((<HTMLSelectElement>event.target).value));
            break;
        case 'year':
            currentDate.value!.setFullYear(parseInt((<HTMLSelectElement>event.target).value));
            break;
    }
    currentDate.value = new Date(currentDate.value!);
}
</script>

<template>
    <div>
        <div class="month-year mt-2 mb-2">
            <button class="btn btn-outline-primary btn-sm border-0"
                    @click="currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1, 12, 0 ,0)">
                <i class="bi bi-chevron-left"></i>
            </button>
            <select class="form-control form-control-sm" @change="evt => updateModel(evt, 'month')">
                <option v-for="(monthName, month) in getMonths()" :value="month" :selected="month === currentDate.getMonth()">
                    {{ monthName }}
                </option>
            </select>
            <select class="form-control form-control-sm" @change="evt => updateModel(evt, 'year')">
                <option v-for="year in getYears(10)" :value="year" :selected="year === currentDate.getFullYear()">
                    {{ year }}
                </option>
            </select>
            <button class="btn btn-outline-primary btn-sm border-0"
                    @click="currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 1, 12, 0 ,0)">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        <div class="days-time">
            <div class="days">
                <div class="week-days">
                    <div v-for="day in ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']" class="text-center">
                        {{ day }}
                    </div>
                </div>
                <div class="day-of-the-month">
                    <button v-for="date in getMonthCalendarDates(currentDate)"
                            class="btn btn-outline-primary border-0"
                            :class="{'btn-outline-primary-active': isSameDay(date, selectedDate), 'opacity-50': isSameMonth(date, currentDate) === false}"
                            @click="selectedDate = date">
                        {{ date.getDate() }}
                    </button>
                </div>
            </div>
            <div class="time overflow-auto">
                <button class="btn btn-outline-primary btn-sm border-0 d-block"
                        v-for="hour in getHours()"
                        :data-time="hour"
                        @click="evt => updateModel(evt, 'time')"
                >
                    {{ hour }}
                </button>
            </div>
        </div>

        <div class="input-group mt-3">
            <span class="input-group-text" id="absolute-date">{{ label }}</span>
            <input type="datetime-local" class="form-control" aria-describedby="absolute-date" value="06-12-2018T19:30">
        </div>
    </div>
</template>

<style scoped>
.time {
    max-height: 204px;
}

.days-time {
    display: grid;
    grid-template-columns: 1fr 60px;
    grid-gap: 5px;
}

.month-year {
    display: grid;
    grid-gap: 5px;
    grid-template-columns: auto 1fr auto auto;
}

.day-of-the-month, .week-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
}
</style>
