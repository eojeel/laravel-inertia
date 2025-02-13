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
    router.get(route('home'), {search: form.search, user_id:params.user_id})
}

</script>

<template>
    <Head title=" - Latest Listings"/>

    <div class="items-center flex justify-between mb-4">
        <div>
            Filters
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
        <div class="grid grid-cols-3 gap-4">
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
