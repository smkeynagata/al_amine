<x-guest-layout>
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">Mot de passe oublié</h1>
            <p class="mt-2 text-sm text-slate-500">
                Entrez votre adresse email et nous vous enverrons un lien sécurisé pour réinitialiser votre accès.
            </p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <x-input-label for="email" value="Adresse email" class="text-sm font-medium text-slate-600" />
                <x-text-input
                    id="email"
                    class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-800 shadow-inner focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/60"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
                <a class="text-sm font-medium text-blue-600 hover:text-blue-500" href="{{ route('login') }}">
                    ← Retour à la connexion
                </a>

                <x-primary-button>
                    Envoyer le lien
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
