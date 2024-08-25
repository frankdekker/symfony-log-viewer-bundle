<script setup lang="ts">
import AbsoluteDatePicker from '@/components/datepicker/AbsoluteDatePicker.vue';
import NowDatePicker from '@/components/datepicker/NowDatePicker.vue';
import RelativeDatePicker from '@/components/datepicker/RelativeDatePicker.vue';
import {onMounted, ref} from 'vue';

const date      = defineModel<Date>({required: true});
const props     = defineProps<{ label: string, activeTab: 'absolute' | 'relative' | 'now' }>();
const activeTab = ref('absolute');

onMounted(() => {
    activeTab.value = props.activeTab;
    console.log('setting props.activeTab', props.activeTab);
});
</script>

<template>
    <div class="combi-date-picker">
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
            <absolute-date-picker v-model="date"/>
        </div>
        <div class="panel2" v-show="activeTab === 'relative'">
            <relative-date-picker v-model="date"/>
        </div>
        <div class="panel3" v-show="activeTab === 'now'">
            <now-date-picker v-model="date" :label="label"/>
        </div>
    </div>
</template>

<style scoped>
.combi-date-picker {
    width: 354px;
}
</style>
