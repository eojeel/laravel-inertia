<script setup>
import {Head, useForm, router} from "@inertiajs/vue3";
import Card from "../Components/Card.vue";
import PaginationLinks from "../Components/PaginationLinks.vue";
import InputField from "../Components/InputField.vue";

const params = route().params;

const props = defineProps({
    listings: Object,
    searchTerm: String,
})

const form = useForm({
    search: props.searchTerm,
})

const search = () => {
    router.get(route('home'), {search: form.search, user_id:params.user_id, tag:params.tag})
}

const username = params.user_id ? props.listings.data.find(i => i.user_id === Number(params.user_id)).user.name : null;

</script>

<template>
    <Head title=" - Latest Listings"/>

    <div class="items-center flex justify-between mb-4">
        <div class="flex items-center gap-2">
            <Link
                class="px-2 py-1 rounded-md bg-indigo-500 text-white"
                v-if="params.tag"
                :href="route('home', { ...params, tag: null, page: null})">
                {{ params.tag }}
                <i class="fa-solid fa-xmark"></i>
            </Link>
            <Link
                class="px-2 py-1 rounded-md bg-indigo-500 text-white"
                v-if="params.search"
                :href="route('home', { ...params, search: null, page: null})">
                {{ params.search }}
                <i class="fa-solid fa-xmark"></i>
            </Link>
            <Link
                class="px-2 py-1 rounded-md bg-indigo-500 text-white"
                v-if="params.user_id "
                :href="route('home', { ...params, user_id: null, page: null})">
                {{ username }}
                <i class="fa-solid fa-xmark"></i>
            </Link>
        </div>

        <div class="w-1/4">
            <form @submit.prevent="search">
                <inputField
                    type="search"
                    label=""
                    icon="magnifying-glass"
                    placeholder="Search..."
                    v-model="form.search"
                />
            </form>
        </div>
    </div>

    <div v-if="Object.keys(listings.data).length">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="listing in listings.data" :key="listing.id">
                <Card :listing="listing"></Card>
            </div>
        </div>

        <div class="mt-8">
            <PaginationLinks :paginator="listings"/>
        </div>
    </div>
    <div v-else>
        There are no Listings.
    </div>
</template>
