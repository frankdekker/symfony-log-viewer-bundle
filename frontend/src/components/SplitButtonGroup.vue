<script setup lang="ts">
import {onUpdated, ref} from 'vue';

const active      = ref<boolean>(false);
const dropdownRef = ref();

const toggle = () => {
    active.value = !active.value;
}

const setDropdownPosition = () => {
    if (active.value === false) {
        return;
    }

    const dropdownEl = dropdownRef.value;
    const parentEl   = dropdownEl.parentElement;

    dropdownEl.style.left = (parentEl.offsetWidth - dropdownEl.offsetWidth) + 'px';
    dropdownEl.style.top  = parentEl.offsetHeight + 'px';

    // check if dropdown is outside of viewport
    if (dropdownEl.getBoundingClientRect().bottom > window.innerHeight) {
        // move dropdown above
        dropdownEl.style.top = (-dropdownEl.offsetHeight) + 'px';
    }
}

onUpdated(() => setDropdownPosition());
defineExpose({toggle});
</script>

<template>
    <div class="lsv-btn-group btn-group">
        <slot name="btn-left"></slot>
        <slot name="btn-right"></slot>
        <ul class="dropdown-menu" :class="{'d-block': active}" ref="dropdownRef">
            <slot name="dropdown"></slot>
        </ul>
    </div>
</template>
