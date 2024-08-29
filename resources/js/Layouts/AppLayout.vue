<script setup lang="ts">
import SideBarMenu from "@/Components/Navigation/SideBarMenu.vue";
import TopMenu from "@/Components/Navigation/TopMenu.vue";
import Footer from "@/Components/Navigation/Footer.vue";
</script>

<template>
    <div id="wrapper">
        <SideBarMenu />
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom top-header">
                <TopMenu />
            </div>
            <div class="wrapper wrapper-content page-wrapper">
                <slot />
            </div>
            <Footer />
        </div>
    </div>
</template>