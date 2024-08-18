<script setup lang="ts">

import RelativeDatePicker from '@/components/datepicker/RelativeDatePicker.vue';
import {ref} from 'vue';

const date      = ref(new Date());
const activeTab = ref('relative');

</script>

<template>
    <div class="dropdown-menu p-2 slv-dropdown-menu">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" :class="{'active': activeTab === 'relative'}" href="javascript:" @click="activeTab='relative'">Relative</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="{'active': activeTab === 'absolute'}" href="javascript:" @click="activeTab='absolute'">Absolute</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="{'active': activeTab === 'now'}" href="javascript:" @click="activeTab='now'">Now</a>
            </li>
        </ul>

        <div class="panel1" v-show="activeTab === 'relative'">
            <relative-date-picker :date="date" label="Start date"/>
        </div>
        <div class="panel2" v-show="activeTab === 'absolute'">
            <h2>Absolute</h2>
        </div>
        <div class="panel3" v-show="activeTab === 'now'">
            Now
        </div>
    </div>
</template>

<style scoped>
.slv-dropdown-menu {
    top: 37px;
}
</style>
