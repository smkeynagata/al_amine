<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-950 text-gray-100">
        <div class="relative min-h-screen overflow-hidden">
            <div class="absolute -top-32 -left-40 h-96 w-96 rounded-full bg-blue-500/40 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 h-[28rem] w-[28rem] rounded-full bg-indigo-600/30 blur-3xl"></div>
            <div class="absolute top-1/3 right-1/4 h-64 w-64 rounded-full bg-cyan-400/20 blur-3xl"></div>

            <div class="relative z-10 flex min-h-screen items-center justify-center px-6 py-12">
                <div class="w-full max-w-5xl">
                    <div class="grid gap-0 lg:grid-cols-[1.05fr_0.95fr] rounded-[2.5rem] bg-white/5 backdrop-blur-xl shadow-[0_25px_60px_-20px_rgba(15,23,42,0.55)] ring-1 ring-white/10 overflow-hidden">
                        <div class="hidden lg:flex flex-col justify-between bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-800 p-10 text-white">
                            <div>
                                <a href="/" class="inline-flex items-center gap-3 text-white/95">
                                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 shadow-lg shadow-black/20">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                        </svg>
                                    </span>
                                    <div>
                                        <h2 class="text-xl font-semibold leading-tight">Al-Amine RDV</h2>
                                        <p class="text-sm text-white/80">Plateforme de santé connectée</p>
                                    </div>
                                </a>
                                <h1 class="mt-10 text-3xl font-semibold leading-tight">Accédez à vos services médicaux en toute sérénité</h1>
                                <p class="mt-4 text-white/80">
                                    Gestion des rendez-vous, suivi des documents, paiements sécurisés et rappels automatisés — tout est centralisé pour votre parcours patient.
                                </p>
                            </div>

                            <ul class="mt-10 space-y-4 text-sm text-white/85">
                                <li class="flex items-start gap-3">
                                    <span class="mt-1 flex h-6 w-6 items-center justify-center rounded-full bg-white/15">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </span>
                                    <span>
                                        Notifications instantanées pour chaque étape de votre parcours médical.
                                    </span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-1 flex h-6 w-6 items-center justify-center rounded-full bg-white/15">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75c-2.623 0-4.75 2.127-4.75 4.75s2.127 4.75 4.75 4.75 4.75-2.127 4.75-4.75S14.623 6.75 12 6.75z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.25 12c0-3.182 2.568-5.75 5.75-5.75" />
                                        </svg>
                                    </span>
                                    <span>
                                        Protection des données conforme aux standards hospitaliers du Sénégal.
                                    </span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-1 flex h-6 w-6 items-center justify-center rounded-full bg-white/15">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                        </svg>
                                    </span>
                                    <span>
                                        Assistance 7j/7 pour les patients, praticiens et secrétaires.
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <div class="relative bg-white/96 text-slate-900 p-8 sm:p-10">
                            <div class="mb-8 flex items-center gap-3">
                                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 shadow-md shadow-blue-500/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-blue-500">Espace sécurisé</p>
                                    <h2 class="text-lg font-semibold text-slate-800">Plateforme patient Al-Amine RDV</h2>
                                </div>
                            </div>
                            <div class="space-y-6">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
