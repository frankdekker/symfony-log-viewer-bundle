<script setup lang="ts">

import AbsoluteDatePicker from '@/components/datepicker/AbsoluteDatePicker.vue';
import NowDatePicker from '@/components/datepicker/NowDatePicker.vue';
import RelativeDatePicker from '@/components/datepicker/RelativeDatePicker.vue';
import {formatDateTime} from '@/services/Dates';
import {ref} from 'vue';

const date      = ref(new Date());
const activeTab = ref('absolute');

function parseDate(evt: Event, update: boolean): void {
    const el         = <HTMLInputElement>evt.target;
    const dateString = el.value.trim();
    const timestamp  = Date.parse(dateString.replace(/\s*@\s*/, ' '));

    if (isNaN(timestamp)) {
        el.classList.toggle('is-invalid', true);
        el.setCustomValidity('Invalid date');
        el.reportValidity();
        return;
    }

    el.classList.toggle('is-invalid', false);
    el.setCustomValidity('');
    if (update) {
        date.value = new Date(timestamp);
    }
}
</script>

<template>
    <div class="dropdown-menu p-2 slv-dropdown-menu">
        <ul class="nav nav-tabs w-100">
            <li class="nav-item">
                <a class="nav-link" :class="{'active': activeTab === 'absolute'}" href="javascript:" @click="activeTab='absolute'">Absolute</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="{'active': activeTab === 'relative'}" href="javascript:" @click="activeTab='relative'">Relative</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="{'active': activeTab === 'now'}" href="javascript:" @click="activeTab='now'">Now</a>
            </li>
        </ul>

        <div class="panel1" v-show="activeTab === 'absolute'">
            <absolute-date-picker v-model="date" label="Start date"/>
        </div>
        <div class="panel2" v-show="activeTab === 'relative'">
            <relative-date-picker v-model="date" label="Start date"/>
        </div>
        <div class="panel3" v-show="activeTab === 'now'">
            <now-date-picker label="Start date"/>
        </div>
        <div class="input-group mb-2 mt-3">
            <span class="input-group-text" id="start-date">Start date</span>
            <input type="text"
                   class="form-control"
                   :value="formatDateTime(date)"
                   aria-describedby="start-date"
                   @change="evt => parseDate(evt, true)"
                   @input="evt => parseDate(evt, false)">
        </div>
    </div>
</template>

<style scoped>
.slv-dropdown-menu {
    top: 37px;
    right: 200px;
}
</style>
