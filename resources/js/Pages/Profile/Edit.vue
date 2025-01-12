<script setup>
import UpdateInfo from './Partials/Updateinfo.vue';
import {Head} from "@inertiajs/vue3";

defineProps({
    user: Object,
    status: String
})
</script>

<template>
    <Head tite="- Profile" />
    <UpdateInfo :user="user" :status="status"/>
</template>
