<script setup>
import UpdateInfo from './Partials/Updateinfo.vue';
import {Head} from "@inertiajs/vue3";
import UpdatePassword from "./Partials/UpdatePassword.vue";
import DeleteAccount from "./Partials/DeleteAccount.vue";

defineProps({
    user: Object,
    status: String
})
</script>

<template>
    <Head tite="- Profile" />
    <UpdateInfo :user="user" :status="status"/>
    <UpdatePassword/>
    <DeleteAccount/>
</template>
