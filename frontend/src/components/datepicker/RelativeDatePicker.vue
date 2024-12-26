<script setup lang="ts">
import type DateSelection from '@/models/DateSelection';
import {formatRelativeDate, getRelativeDate} from '@/services/Dates';
import Numbers from '@/services/Numbers';
import {ref, watch} from 'vue';

const selected = defineModel<DateSelection>({required: true});
const props    = defineProps<{ activated: boolean }>();
const valueRef = ref<HTMLInputElement>();
const unitRef  = ref<HTMLSelectElement>();

watch(() => props.activated, () => {
    if (props.activated === false) {
        return;
    }

    if (selected.value.mode === 'relative' && selected.value.value !== null) {
        const value           = selected.value.value;
        valueRef.value!.value = value.substring(0, value.length - 1);
        unitRef.value!.value  = value.substring(value.length - 1);
    } else {
        update();
    }
});

function validate(): void {
    const valueInput = <HTMLInputElement>valueRef.value;
    valueInput.classList.toggle('is-invalid', valueInput.checkValidity() === false);
}

function update(): void {
    const valueInput = <HTMLInputElement>valueRef.value;
    const unitInput  = <HTMLSelectElement>unitRef.value;
    if (valueInput.checkValidity() === false) {
        return;
    }

    selected.value.date      = getRelativeDate(Numbers.parseInt(valueInput.value), unitInput.value, true);
    selected.value.formatted = formatRelativeDate(Numbers.parseInt(valueInput.value), unitInput.value);
    selected.value.value     = valueInput.value + unitInput.value;
    selected.value.mode      = 'relative';
}
</script>

<template>
    <div class="d-flex flex-row mt-5 mb-5">
        <input type="number"
               ref="valueRef"
               class="flex-fill form-control me-1 value-input"
               value="15"
               min="0"
               @input="validate();update();"
               required
               aria-required="true">
        <select class="flex-fill form-control" ref="unitRef" @change="update">
            <option value="s">seconds ago</option>
            <option value="i" selected>minutes ago</option>
            <option value="h">hours ago</option>
            <option value="d">days ago</option>
            <option value="w">weeks ago</option>
            <option value="m">months ago</option>
            <option value="y">years ago</option>
        </select>
    </div>
</template>

<style scoped>
.value-input {
    width: 100px;
}
</style>
