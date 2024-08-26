<script setup lang="ts">
import AbsoluteDatePicker from '@/components/datepicker/AbsoluteDatePicker.vue';
import NowDatePicker from '@/components/datepicker/NowDatePicker.vue';
import RelativeDatePicker from '@/components/datepicker/RelativeDatePicker.vue';
import type DateSelection from '@/models/DateSelection';

const selection = defineModel<DateSelection>({required: true});
defineProps<{ label: string }>();

</script>

<template>
    <div class="combi-date-picker">
        <ul class="nav nav-tabs w-100">
            <li class="nav-item">
                <a class="nav-link"
                   :class="{'active': selection.mode === 'absolute'}"
                   href="javascript:"
                   @click="selection.mode='absolute'">Absolute</a>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                   :class="{'active': selection.mode === 'relative'}"
                   href="javascript:"
                   @click="selection.mode='relative'">Relative</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="{'active': selection.mode === 'now'}" href="javascript:" @click="selection.mode='now'">Now</a>
            </li>
        </ul>

        <div class="panel1" v-show="selection.mode === 'absolute'">
            <absolute-date-picker v-model="selection.date"/>
        </div>
        <div class="panel2" v-show="selection.mode === 'relative'">
            <relative-date-picker v-model="selection.date"/>
        </div>
        <div class="panel3" v-show="selection.mode === 'now'">
            <now-date-picker v-model="selection.date" :label="label"/>
        </div>
    </div>
</template>

<style scoped>
.combi-date-picker {
    width: 354px;
}
</style>
