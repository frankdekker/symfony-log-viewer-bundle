<script setup lang="ts">
import ButtonGroup from '@/components/ButtonGroup.vue';
import type Checklist from '@/models/Checklist';
import {ref} from 'vue';

const toggleRef = ref();

defineProps<{
    label: string,
    checklist: Checklist
}>();
</script>

<template>
    <button-group v-show="Object.keys(checklist.choices).length > 0" ref="toggleRef" class="pe-1" alignment="left">
        <template v-slot:btn>
            <button class="btn btn-outline-primary text-nowrap" type="button" @click="toggleRef.toggle">
                {{ label }} <span class="badge text-bg-primary">{{ checklist.selected.length }}</span>
            </button>
        </template>
        <template v-slot:dropdown>
            <li class="ps-3 pe-3 text-nowrap d-block">
                <a href="javascript:" class="pe-2" @click="checklist.selected = Object.keys(checklist.choices)">Select all</a>
                <a href="javascript:" @click="checklist.selected = []">Select none</a>
            </li>

            <li v-for="(label, key) in checklist.choices" v-bind:key="key">
                <label class="dropdown-item">
                    <input type="checkbox" class="me-2" :value="key" :name="String(key)" v-model="checklist.selected">
                    <span>{{ label }}</span>
                </label>
            </li>
        </template>
    </button-group>
</template>
