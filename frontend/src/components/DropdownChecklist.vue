<script setup lang="ts">
import type Checklist from '@/models/Checklist';
import {ref} from 'vue';

const dropdownEl = ref<HTMLElement | null>(null);
const open       = ref(false);
defineProps<{
    label: string,
    checklist: Checklist
}>()

const onDocumentClick = (event: MouseEvent) => {
    const target = event.target;

    if (dropdownEl.value === null || target instanceof Node === false || (<HTMLElement>dropdownEl.value).contains(<Node>target) === false) {
        toggle();
    }
};

function toggle() {
    open.value = !open.value;
    if (open.value) {
        document.addEventListener('click', onDocumentClick);
    } else {
        document.removeEventListener('click', onDocumentClick);
    }
}
</script>

<template>
    <div class="dropdown pe-1" v-show="Object.keys(checklist.choices).length > 0" ref="dropdownEl">
        <button class="btn btn-outline-primary" type="button" @click="toggle">
            {{ label }} <span class="badge text-bg-primary">{{ checklist.selected.length }}</span>
        </button>
        <ul class="dropdown-menu" v-bind:class="{ 'show': open }">
            <div class="ps-3 pe-3 text-nowrap">
                <a href="javascript:" class="pe-1" @click="checklist.selected = Object.keys(checklist.choices)">Select all</a>
                <a href="javascript:" @click="checklist.selected = []">Select none</a>
            </div>

            <li v-for="(label, key) in checklist.choices">
                <label class="dropdown-item">
                    <input type="checkbox" class="me-2" :value="<string>key" :name="<string>key" v-model="checklist.selected">
                    <span>{{ label }}</span>
                </label>
            </li>
        </ul>
    </div>
</template>
