export interface LoginForm {
    email: string | null;
    password: string | null;
    remember_me: boolean;
}

export interface LoginError {
    email?: string;
    email_token?: string;
    password?: string;
}

export interface EmailVerifyForm {
    email_token: string | null;
}

export interface EmailVerifyError {
    email_token?: string;
}