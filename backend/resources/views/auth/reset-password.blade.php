<x-guest-layout>
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">Créer un nouveau mot de passe</h1>
            <p class="mt-2 text-sm text-slate-500">
                Sécurise ton compte en choisissant un mot de passe robuste et unique.
            </p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="space-y-2">
                <x-input-label for="email" value="Adresse email" class="text-sm font-medium text-slate-600" />
                <x-text-input
                    id="email"
                    class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-800 shadow-inner focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/60"
                    type="email"
                    name="email"
                    :value="old('email', $request->email)"
                    required
                    autofocus
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="space-y-2">
                <x-input-label for="password" value="Nouveau mot de passe" class="text-sm font-medium text-slate-600" />
                <x-text-input id="password" class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-800 shadow-inner focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/60" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="space-y-2">
                <x-input-label for="password_confirmation" value="Confirmer le mot de passe" class="text-sm font-medium text-slate-600" />
                <x-text-input id="password_confirmation" class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-800 shadow-inner focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/60" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end">
                <x-primary-button>
                    Réinitialiser le mot de passe
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
