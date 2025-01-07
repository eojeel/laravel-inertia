<script setup>
import Container from '../../Components/Container.vue'
import {Head, useForm} from "@inertiajs/vue3";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import Message from "../../Components/Message.vue";

const form = useForm({});

defineProps({
    message: String
})
const submit = () => {
    form.post(route('verification.send'))
}
</script>

<template>
    <Head title="- Verify Email" />

    <Container class="w-1/2">
        <div class="mb-8">
            <p>
                Thank you for signing up! please verify your email.
            </p>
        </div>

        <Message :message="message"/>
        <form @submit.prevent="submit">
            <PrimaryButton :disabled="form.processing">Resend Verification Email</PrimaryButton>
        </form>
    </Container>
</template>
