<script setup lang="ts">
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();

const user = computed(() => page.props.auth.user);

</script>

<template>
    <main>
        <header>You are logged in as: {{ user.email }}</header>
        <article>
            <slot />
        </article>
    </main>
</template>