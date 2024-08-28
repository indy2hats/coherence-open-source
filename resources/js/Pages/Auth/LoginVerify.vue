<script setup lang="ts">
import { computed, reactive } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { EmailVerifyForm, EmailVerifyError } from "@/types/loginTypes";

defineProps<{
    errors: EmailVerifyError;
}>();

const page = usePage();
const companyLogo = computed(() => page.props.companyLogo as string);
const form = reactive<EmailVerifyForm>({
    email_token: null,
});

function submit() {
    router.post(route("email-verify"), form);
}
</script>

<template>
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div class="logo-name">
            <img :src="companyLogo" alt="company-logo" width="70%" />
        </div>
        <p>Check Your Email</p>
        <p>We have sent you an email with a code.</p>
        <form class="form-horizontal" @submit.prevent="submit">
            <div v-if="errors?.email_token" class="text text-danger m-b">
                {{ errors.email_token }}
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <input
                        type="text"
                        name="email_token"
                        v-model="form.email_token"
                        class="form-control"
                        placeholder="Enter Code"
                        required="true"
                    />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <button
                        type="submit"
                        class="btn btn-success block full-width m-b"
                    >
                        Submit
                    </button>
                </div>
            </div>
            <div class="form-group">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900"
                >
                    Login another user?
                </Link>
            </div>
        </form>
    </div>
</template>