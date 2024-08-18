<script setup lang="ts">
import {formatDateTime, getRelativeDate} from '@/services/Dates';
import Numbers from '@/services/Numbers';
import {ref} from 'vue';

const dateModel = defineModel<Date>();
defineProps<{ label: string }>();

const valueRef  = ref<HTMLInputElement>();
const unitRef   = ref<HTMLSelectElement>();

function validate(payload: Event): void {
    const el = <HTMLInputElement>payload.target;
    el.classList.toggle('is-invalid', el.checkValidity() === false);
}

function onValueChanged(): void {
    const valueInput = <HTMLInputElement>valueRef.value;
    const unitInput  = <HTMLSelectElement>unitRef.value;
    if (valueInput.checkValidity() === false) {
        return;
    }

    dateModel.value = getRelativeDate(Numbers.parseInt(valueInput.value), unitInput.value, true);
}

</script>

<template>
    <div class="d-flex flex-row mt-3" @input="onValueChanged" @change="onValueChanged">
        <input type="number"
               ref="valueRef"
               class="flex-fill form-control me-1"
               value="1"
               min="0"
               pattern="^[1-9][0-9]*$"
               @input="validate"
               required
               aria-required="true">
        <select class="flex-fill form-control" ref="unitRef">
            <option value="s">Seconds ago</option>
            <option value="i">Minutes ago</option>
            <option value="h">Hours ago</option>
            <option value="d">Days ago</option>
            <option value="w">Weeks ago</option>
            <option value="m">Months ago</option>
            <option value="y">Years ago</option>
        </select>
    </div>
    <div class="input-group mb-2 mt-3">
        <span class="input-group-text" id="relative-date">{{ label }}</span>
        <input type="text" class="form-control" readonly aria-readonly="true" :value="formatDateTime(dateModel)" aria-describedby="relative-date">
    </div>
</template>
