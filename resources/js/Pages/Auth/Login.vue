<script setup lang="ts">
import { computed, reactive } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { LoginForm, LoginError } from "@/types/loginTypes";

defineProps<{
    errors: LoginError;
}>();

const page = usePage();
const companyLogo = computed(() => page.props.companyLogo as string);
const hasPasswordRequestRoute = computed(
    () => route().has("password.request") as boolean
);

const form = reactive<LoginForm>({
    email: null,
    password: null,
    remember_me: false,
});

function submit() {
    router.post(route("login-submit"), form);
}
</script>

<template>
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div class="logo-name">
                <img :src="companyLogo" alt="company-logo" width="70%" />
            </div>
            <div v-if="errors?.email_token" class="text-danger text-left">
                {{ errors.email_token }}
            </div>
            <form class="m-t" @submit.prevent="submit">
                <div class="form-group">
                    <input
                        type="email"
                        name="email"
                        v-model="form.email"
                        class="form-control"
                        placeholder="Email"
                        required="true"
                    />
                    <div v-if="errors?.email" class="text-danger text-left">
                        {{ errors.email }}
                    </div>
                </div>
                <div class="form-group">
                    <input
                        type="password"
                        class="form-control"
                        name="password"
                        v-model="form.password"
                        placeholder="Password"
                        required="true"
                    />
                    <div v-if="errors?.password" class="text-danger text-left">
                        {{ errors.password }}
                    </div>
                </div>
                <div class="form-group">
                    <input
                        id="box1"
                        type="checkbox"
                        v-model="form.remember_me"
                        name="remember_me"
                    />
                    <label for="box1">Remember Me</label>
                </div>
                <button
                    type="submit"
                    class="btn btn-success block full-width m-b"
                >
                    Login
                </button>

                <div v-if="hasPasswordRequestRoute" class="form-group">
                    <Link
                        :href="route('password.request')"
                        class="underline text-sm text-gray-600 hover:text-gray-900"
                    >
                        Forgot your password?
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>