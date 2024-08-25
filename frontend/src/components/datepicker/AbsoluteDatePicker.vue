<script setup lang="ts">
import TimeSelect from '@/components/datepicker/TimeSelect.vue';
import {format, getMonthCalendarDates, getMonths, isSameDay, isSameMonth, setDayOfTheYear} from '@/services/Dates';
import {ref, watch} from 'vue';

const selectedDate = defineModel<Date>({required: true});
defineProps<{ label: string }>();

const currentDate   = ref(new Date());
const calendarDates = ref<Date[]>(getMonthCalendarDates(currentDate.value));

watch(selectedDate, () => currentDate.value = new Date(selectedDate.value));
watch(currentDate, () => calendarDates.value = getMonthCalendarDates(currentDate.value));

function updateModel(event: Event, field: 'time' | 'day' | 'month' | 'year'): void {
    switch (field) {
        case 'month':
            currentDate.value!.setMonth(parseInt((<HTMLSelectElement>event.target).value));
            break;
        case 'year':
            const element = <HTMLInputElement>event.target;
            if (element.reportValidity() === false) {
                return;
            }
            currentDate.value!.setFullYear(parseInt(element.value));
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
            <input type="number" class="form-control form-control-sm"
                   @input="evt => updateModel(evt, 'year')"
                   required
                   min="1000"
                   max="9999"
                   :value="currentDate.getFullYear()" />
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
                    <button v-for="date in calendarDates"
                            class="btn btn-outline-primary border-0"
                            :class="{'btn-outline-primary-active': isSameDay(date, selectedDate), 'opacity-50': isSameMonth(date, currentDate) === false}"
                            @click="selectedDate = setDayOfTheYear(selectedDate, date.getFullYear(), date.getMonth(), date.getDate())">
                        {{ date.getDate() }}
                    </button>
                </div>
            </div>
            <time-select class="time" :class="{'time-6-weeks': calendarDates.length > 35}" v-model="selectedDate"/>
        </div>

        <div class="input-group mt-3">
            <span class="input-group-text" id="absolute-date">{{ label }}</span>
            <input type="datetime-local"
                   class="form-control form-control-sm"
                   aria-describedby="absolute-date"
                   :value="format('Y-m-dTH:i', selectedDate)">
        </div>
    </div>
</template>

<style scoped>
.days-time {
    display: grid;
    grid-template-columns: 1fr 60px;
    grid-gap: 5px;
}

.time {
    max-height: 204px;

    &.time-6-weeks {
        max-height: 240px;
    }
}

.month-year {
    display: grid;
    grid-gap: 5px;
    grid-template-columns: auto 1fr 100px auto;
}

.day-of-the-month, .week-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
}
</style>
