<script setup lang="ts">
import TimeSelect from '@/components/datepicker/TimeSelect.vue';
import type DateSelection from '@/models/DateSelection';
import {formatDateTime, getMonthCalendarDates, getMonths, isSameDay, isSameMonth, setDayOfTheYear} from '@/services/Dates';
import {ref, watch} from 'vue';

const selected      = defineModel<DateSelection>({required: true});
const props         = defineProps<{ activated: boolean }>();
const currentDate   = ref(new Date()); // the current date for the month and year selection
const preselected   = ref(new Date()); // an internal watch when "selected" date is set
const calendarDates = ref<Date[]>(getMonthCalendarDates(currentDate.value));

watch(selected, () => currentDate.value = new Date(selected.value.date));
watch(currentDate, () => calendarDates.value = getMonthCalendarDates(currentDate.value));
watch(preselected, () => {
    selected.value.date      = preselected.value;
    selected.value.value     = null;
    selected.value.mode      = 'absolute';
    selected.value.formatted = formatDateTime(preselected.value)
});
/*
 * When the absolute date picker is activated, update the format to absolute
 */
watch(() => props.activated, () => {
    if (props.activated) {
        selected.value.formatted = formatDateTime(selected.value.date);
        selected.value.mode      = 'absolute';
        selected.value.value     = null;
    }
});

function update(event: Event, field: 'month' | 'year'): void {
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
            <select class="form-control form-control-sm" @change="evt => update(evt, 'month')">
                <option v-bind:key="month" v-for="(monthName, month) in getMonths()" :value="month" :selected="month === currentDate.getMonth()">
                    {{ monthName }}
                </option>
            </select>
            <input type="number"
                   class="form-control form-control-sm"
                   @input="evt => update(evt, 'year')"
                   required
                   min="1000"
                   max="9999"
                   :value="currentDate.getFullYear()"/>
            <button class="btn btn-outline-primary btn-sm border-0"
                    @click="currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 1, 12, 0 ,0)">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        <div class="days-time">
            <div class="days">
                <div class="week-days">
                    <div v-bind:key="day" v-for="day in ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su']" class="text-center">
                        {{ day }}
                    </div>
                </div>
                <div class="day-of-the-month">
                    <button v-bind:key="date.toDateString()"
                            v-for="date in calendarDates"
                            class="btn btn-outline-primary border-0"
                            :class="{'btn-outline-primary-active': isSameDay(date, selected.date), 'opacity-50': isSameMonth(date, currentDate) === false}"
                            @click="preselected = setDayOfTheYear(selected.date, date.getFullYear(), date.getMonth(), date.getDate())">
                        {{ date.getDate() }}
                    </button>
                </div>
            </div>
            <time-select class="time" :class="{'time-6-weeks': calendarDates.length > 35}" v-model="preselected"/>
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
