<script setup lang="ts">
import { computed } from "vue";
import { Head } from '@inertiajs/vue3';
import { Link, usePage } from "@inertiajs/vue3";
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePermissions } from "@/composables/usePermissions";
import { SiteSettings } from "@/types/site-settings-types";

const page = usePage();
const permissions = computed(() => page.props.user_permissions as string[]);
const roles = computed(() => page.props.user_roles as string[]);
const siteSettings = computed(() => page.props.site_settings as SiteSettings);
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
    {{ siteSettings.show_daily_status_report_page }}
    {{ route().current('changePassword-new') }}
  </AppLayout>
</template>