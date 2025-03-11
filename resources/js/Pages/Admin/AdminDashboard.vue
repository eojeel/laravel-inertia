<script setup>
import {Head, router, useForm} from "@inertiajs/vue3";
import PaginationLinks from "../../Components/PaginationLinks.vue";
import Message from "../../Components/Message.vue";
import InputField from "../../Components/InputField.vue";
import RoleSelect from "../../Components/RoleSelect.vue";

defineProps({
    users: Object,
    status: String
})

const params = route().params
const form = useForm({search: params.search})
const search = () => {
    router.get(route('admin.index'), {
        search: form.search,
        role: params.role
    })
};

const toggleRole = (event) => {
    router.get(route('admin.index', {
        search: params.search,
        role: event.target.checked ? 'suspended' : null
        })
    )
};

</script>

<template>
    <Head title="- admin"/>

    <Message :message="status"></Message>

    <div class="flex items-end justify-between mb-4">
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
                :href="route('admin.index', { ...params, search: null, page: null})">
                {{ params.search }}
                <i class="fa-solid fa-xmark"></i>
            </Link>
        </div>

        <div class="flex items-center gap-1 text-xs hover:bg-slate-300 dark:hover:bg-slate-800 px-2 py-1 rounded-md">
            <input
                @input="toggleRole"
                :checked="params.role"
                type="checkbox"
                id="toggleRole"
                class="rounded-md border-1 outline-0 text-indigo-500 ring-indigo-500 border-slate-700 cursor-pointer" />

            <label for="toggleRole"
                   class="block text-sm font-medium text-slate-700 dark:text-slate-300 cursor-pointer">Show suspended Users</label>
        </div>
    </div>

    <table class="bg-white dark:bg-slate-800 w-full rounded-lg overflow-hidden ring-1 ring-slate-300">
        <thead>
        <tr class="bg-slate-600 text-slate-300 uppercase text-xs text-left">
            <th class="w-3/6 p-3">Name</th>
            <th class="w-2/6 p-3">Role</th>
            <th class="w-1/6 p-3">Listings</th>
            <th class="w-1/6 p-3 text-right">View</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-slate-400 divide-dashed">
        <tr v-for="user in users.data" :key="user.id">
            <td class="w-3/6 py-5 px-3">
                <p class="font-bold mb-1">{{ user.name }}</p>
                <p class="font-light text-xs">{{ user.email }}</p>
            </td>
            <td class="w-2/6 py-5 px-3">
                <RoleSelect :user="user" />
            </td>
            <td class="w-1/6 py-5 px-3">
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-1">
                        <p>{{ user.listing.filter(l => l.approved).length }}</p>
                        <i class="fa-solid fa-circle-check text-green-400"></i>
                    </div>
                    <div class="flex items-center gap-1">
                        <p>{{ user.listing.filter(l => !l.approved).length }}</p>
                        <i class="fa-solid fa-circle-xmark text-red-400"></i>
                    </div>
                </div>
            </td>
            <td class="w-1/6 py-5 px-3 text-right">
                Link
            </td>
        </tr>
        </tbody>
    </table>

    <div class="mt-6">
        <PaginationLinks :paginator="users"/>
    </div>
</template>
