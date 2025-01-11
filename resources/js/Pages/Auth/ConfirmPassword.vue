<script setup>

import Container from "../../Components/Container.vue";
import InputField from "../../Components/InputField.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import {useForm, Head} from "@inertiajs/vue3";
import Error from "../../Components/Error.vue";

const form = useForm({
    password:"",
});

defineProps({
    status: String,
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset()
    })
}

</script>

<template>
    <Head title="- Confirm Password " />
    <container class="w-1/2">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold mb-2">Please Confirm your password</h1>
        </div>

        <Error :errors="form.errors" />

        <form @submit.prevent="submit" class="space-y-6">
            <InputField label="Password" type="password" icon="key" v-model="form.password"/>
            <PrimaryButton :disabled="form.processing">Confirm</PrimaryButton>
        </form>
    </container>
</template>
