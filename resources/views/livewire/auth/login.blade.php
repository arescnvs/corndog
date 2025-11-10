<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Log in to your account')" :description="__('Masukkan username dan password untuk login')" />

    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="login" class="flex flex-col gap-6">
        <flux:input
            wire:model="username"
            :label="__('Username')"
            type="text"
            required
            autofocus
            autocomplete="username"
            placeholder="Masukkan username Anda"
        />

        <div class="relative">
            <flux:input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />
        </div>

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                {{ __('Log in') }}
            </flux:button>
        </div>
    </form>

    {{-- Bagian Sign Up & Forgot Password di-nonaktifkan --}}
    {{-- 
    <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
        <span>{{ __("Don't have an account?") }}</span>
        <flux:link :href="route('register.unayaha')" wire:navigate>{{ __('Sign up') }}</flux:link>
    </div>
    --}}
</div>
