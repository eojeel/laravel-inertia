<script setup>
import {switchTheme} from "../theme.js";
import NavLink from "../Components/NavLink.vue";
import {usePage} from "@inertiajs/vue3";
import {computed, ref} from "vue";

const page = usePage();
const user = computed(() => page.props.auth.user)

const show = ref(false)
</script>

<template>
    <header class="bg-slate-800 text-white">
        <nav class="p-6 mx-auto max-w-screen-lg flex items-center justify-between">
            <NavLink routeName="home">Home</NavLink>
            <div class="flex items-center space-x-6">
                <div v-if="user" class="relative" @click="show = !show">
                    <div class="flex items-center gap-2 px-3 py-1 rounded-lg hover:bg-slate-7000 cursor-pointer">
                        Account
                        <i class="fa-solid fa-angle-down"></i>
                    </div>
                    <div v-show="show"
                         @click="show=false"
                         class="absolute z-50 top-16 right-0 bg-slate-800 text-white rounded-lg border-slate-700 border overflow-hidden w-40">
                        <p>Logout</p>
                    </div>
                </div>


                <div v-else>
                    <NavLink routeName="login">Login</NavLink>
                    <NavLink routeName="register">Register</NavLink>
                </div>
                <button
                    @click="switchTheme"
                    class="hover:bg-slate-700 w-6 h-4 grid placed-items-center rounded-full hover:outline outline-1 outline-white">
                <i class="fa-solid fa-circle-half-stroke"></i>
                </button>
            </div>
        </nav>
    </header>

    <main class="p-6 mx-auto max-w-screen-lg">
        <slot />
    </main>
</template>
