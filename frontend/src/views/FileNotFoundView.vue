<template>
    <div class="not-found">
        <div class="alert alert-danger label">
            Log file not found.
        </div>
    </div>
</template>

<style scoped>
.not-found {
    position: relative;

    .label {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
}
</style>
<script setup lang="ts"></script>
