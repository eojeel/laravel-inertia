<script setup>
import Container from "../../../Components/Container.vue";
import InputField from "../../../Components/InputField.vue";
import PrimaryButton from "../../../Components/PrimaryButton.vue";
import {router, useForm} from "@inertiajs/vue3";
import Message from "../../../Components/Message.vue";
import Error from "../../../Components/Error.vue";

const props = defineProps({
    user: Object,
    status: String
});

const form = useForm({
    name: props.user.name,
    email: props.user.email
});

const resendEmail = (e) => {
    router.post(route('verification.send'), {}, {
        onStart: () => {
            e.target.disable = true
        },
        onFinish: () => {
            e.target.disable = false
        }
    })
}

</script>

<template>
    <Container clas="mb-6">
        <div class="mb-4">
            <h1>Update Information</h1>
            <p>Update your account's profile information.</p>
        </div>

        <Message :message="status"/>
        <Error :errors="form.errors" />

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="space-y-6">
            <InputField
                label="Name"
                icon="id-badge"
                v-model="form.name"
                class="w-1/2"
                />

            <InputField
                label="Email"
                icon="at"
                v-model="form.email"
                class="w-1/2"
            />

            <div v-if="user.email_verified_at === null"
            class="flex items-center gap-2">
                <p>Your email address is unverified</p>
                <button
                class="text-indigo-500 font-medium underline dark:text-indigo-400 disabled:text-slate-400 disabled:cursor-wait"
                @click="resendEmail">
                    Click here to re-send email verification!
                </button>
            </div>
            <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
        </form>
    </Container>
</template>
