<script setup lang="ts">
import { computed } from "vue";
import { Head } from '@inertiajs/vue3';
import { Link, usePage } from "@inertiajs/vue3";
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePermissions } from "@/composables/usePermissions";

const page = usePage();
const permissions = computed(() => page.props.user_permissions as string[]);
const roles = computed(() => page.props.user_roles as string[]);
const { can, hasRole, hasAnyRole, hasAllRoles, hasAllPermissions, hasAnyPermission } = usePermissions();
</script>

<template>
  <AppLayout>
    <!-- TODO::remove the unwanted code -->
    <!-- Non-Compliant -->

    <Head title="Welcome" />
    <h1>Welcome</h1>
    <p>Hello test, welcome to your first Inertia app!</p>
    {{ permissions }}
    {{ roles }}
    {{ can('view-user-access-levels') }}
    {{ hasRole('administrator') }}
    {{ hasAnyRole(['administrator','dd']) }}
    {{ hasAllRoles(['administrator','dd']) }}
    {{ hasAllPermissions(['view-user-access-levels']) }}
    {{ hasAnyPermission(['view-user-access-ledvels','dfdfd']) }}
    ddd

    {{ route().current('changePassword-new') }}
  </AppLayout>
</template>