<script setup>

import Container from "../../Components/Container.vue";
import InputField from "../../Components/InputField.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import {useForm, Head} from "@inertiajs/vue3";
import Error from "../../Components/Error.vue";
import Message from "../../Components/Message.vue";

const form = useForm({
    email:"",
});

defineProps({
    status: String,
})

const submit = () => {
    form.post(route('password.email'), {
        onFinish: () => form.reset("password")
    })
}

</script>

<template>
    <Head title="- Reset Password" />
    <container class="w-1/2">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold mb-2">Reset your password!</h1>
        </div>

        <Error :errors="form.errors" />
        <Message :message="status"/>

        <form @submit.prevent="submit" class="space-y-6">
            <InputField label="Email" type="email" icon="at" v-model="form.email"/>
            <PrimaryButton :disabled="form.processing">Reset</PrimaryButton>
        </form>
    </container>
</template>
