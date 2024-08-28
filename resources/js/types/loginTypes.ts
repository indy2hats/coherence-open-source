export interface LoginForm {
    email: string | null;
    password: string | null;
    remember_me: boolean;
}

export interface LoginError {
    email?: string;
    password?: string;
}