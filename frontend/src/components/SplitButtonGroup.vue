<script setup lang="ts">
import ClickOutside from '@/services/ClickOutside';
import {setRelativeTo} from '@/services/Positioner';
import {watch, onUpdated, ref} from 'vue';

const active       = ref<boolean>(false);
const dropdownRef  = ref();
const clickOutside = new ClickOutside([], () => toggle(false));
const props = defineProps<{ alignment: 'left' | 'right' }>();

const toggle = (forceActive: boolean | null = null): void => {
    active.value = forceActive ?? !active.value;
}

watch(active, () => setTimeout(() => clickOutside.enable(active.value), 1));

onUpdated(() => {
    if (active.value === false) {
        return;
    }
    setRelativeTo(dropdownRef.value.parentElement, dropdownRef.value, props.alignment);
});
defineExpose({toggle});
</script>

<template>
    <div class="slv-btn-group btn-group">
        <slot name="btn-left"></slot>
        <slot name="btn-right"></slot>
        <ul class="dropdown-menu" :class="{'d-block': active}" ref="dropdownRef">
            <slot name="dropdown"></slot>
        </ul>
    </div>
</template>
