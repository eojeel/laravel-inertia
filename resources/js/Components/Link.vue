<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    name: String,
    route: {
        type: String,
        required: true
    }
})
</script>

<template>
    <Link class="text-slate-600" :href="route">{{ name }}</Link>
</template>
