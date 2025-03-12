<script setup>
import {Head, router, useForm,} from "@inertiajs/vue3";
import Title from "../../Components/Title.vue";
import Link from "../../Components/Link.vue";
import PaginationLinks from "../../Components/PaginationLinks.vue";
import InputField from "../../Components/InputField.vue";
import Message from "../../Components/Message.vue";

const props = defineProps({
    user: Object,
    listings: Object,
    status: String
})

const params = route().params
const form = useForm({search: params.search})
const search = () => {
    router.get(route('user.show', {user: props.user.id}), {
        search: form.search,
    })
};

const toggleApproved = (listing) => {
    let msg = listing.approved ? 'disapprove' : 'approve';
    if(confirm(`Are you sure you want to ${msg} this listing?`)) {
        router.put(route('listing.toggleApproval', listing.id
        ));
    }
};


const toggleListing = (event) => {
    router.get(route('user.show', {
            user: props.user.id,
            approved: event.target.checked ? true : null,
        })
    )
};

</script>

<template>
    <Head :title="`${user.name}  Listings`" />

    <message :message="status"/>

    <div class="mb-6">
        <Title>{{ user.name }} listings</Title>
        <div class="flex items-end justify-between">
            <div class="flex items-end gap-2">
                <form @submit.prevent="search">
                    <InputField
                        label=""
                        icon="magnifying-glass"
                        placeholder="Search..."
                        v-model="form.search"
                    />
                </form>
                <Link
                    class="px-6 py-2 rounded-md bg-indigo-500 text-white"
                    v-if="params.search"
                    :href="route('user.show', { ...params, search: null, page: null, user: user.id})">
                    {{ params.search }}
                    <i class="fa-solid fa-xmark"></i>
                </Link>
            </div>
            <div class="flex items-center gap-1 text-xs hover:bg-slate-300 dark:hover:bg-slate-800 px-2 py-1 rounded-md">
                <input
                    @input="toggleListing"
                    :checked="params.approved"
                    type="checkbox"
                    id="toggleListing"
                    class="rounded-md border-1 outline-0 text-indigo-500 ring-indigo-500 border-slate-700 cursor-pointer" />

                <label for="toggleListing"
                       class="block text-sm font-medium text-slate-700 dark:text-slate-300 cursor-pointer">Toggle Listings</label>
            </div>
        </div>

    </div>

    <table class="mb-2 bg-white dark:bg-slate-800 w-full rounded-lg overflow-hidden ring-1 ring-slate-300">
        <thead>
        <tr class="bg-slate-600 text-slate-300 uppercase text-xs text-left">
            <th class="w-4/6 p-3">Title</th>
            <th class="w-2/6 p-3 text-center">Approved</th>
            <th class="w-1/6 p-3 text-right">View</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-slate-300 divide-dashed">
        <tr v-for="listing in listings.data" :key="listing.id">
            <td  class="py-5 px-3"> {{ listing.title }}</td>
            <td class="py-5 px-3 text-2xl text-center">
                <button @click.prevent="toggleApproved(listing)">
                    <i :class="`fa-solid fa-${listing.approved
                    ? 'circle-check text-green-400'
                    : 'circle-xmark text-red-400'}`">
                    </i>
                </button>
            </td>
            <td class="w-1/6 py-5 px-3 text-right">
                <Link :route="route('listing.show', listing.id)"
                class="fa-solid fa-up-right-from-square px-3 text-indigo-400">
                </Link>
            </td>
        </tr>
        </tbody>
    </table>
    <PaginationLinks :paginator="listings"/>
</template>
