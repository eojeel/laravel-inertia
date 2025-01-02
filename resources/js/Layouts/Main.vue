<script setup>
import {switchTheme} from "../theme.js";
import NavLink from "../Components/NavLink.vue";
</script>

<template>
    <header class="bg-slate-800 text-white">
        <nav class="p-6 mx-auto max-w-screen-lg flex items-center justify-between">
            <NavLink routeName="home">Home</NavLink>
            <div class="flex items-center space-x-6">
                <NavLink routeName="login">Login</NavLink>
                <NavLink routeName="register">Register</NavLink>
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
