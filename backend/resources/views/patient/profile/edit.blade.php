@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="{ activeTab: 'personal' }">
    <div class="max-w-5xl mx-auto">
        <!-- Header with Photo -->
        <div class="mb-6 flex items-start justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Mon Profil</h1>
                <p class="text-gray-600">Gérez vos informations personnelles et de santé</p>
            </div>
            
            <!-- Photo de profil -->
            <div class="flex flex-col items-center gap-3">
                <div class="relative">
                    @if(auth()->user()->photo_profil)
                        <img src="{{ Storage::url(auth()->user()->photo_profil) }}" 
                             alt="Photo de profil" 
                             class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                    @else
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                            {{ substr(auth()->user()->prenom, 0, 1) }}{{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <form method="POST" action="{{ route('patient.profile.update.photo') }}" enctype="multipart/form-data" x-data="{ uploading: false }">
                    @csrf
                    <input type="file" name="photo" id="photo" class="hidden" accept="image/*" 
                           @change="uploading = true; $el.form.submit()">
                    <label for="photo" class="cursor-pointer text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Changer la photo
                    </label>
                </form>
                @if(auth()->user()->photo_profil)
                    <form method="POST" action="{{ route('patient.profile.delete.photo') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium">
                            Supprimer
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="flex border-b border-gray-200 overflow-x-auto">
                <button 
                    @click="activeTab = 'personal'" 
                    :class="activeTab === 'personal' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-800'"
                    class="flex-1 px-4 py-4 text-sm font-medium transition-colors duration-200 whitespace-nowrap"
                >
                    <i class="fas fa-user mr-2"></i>
                    Informations Personnelles
                </button>
                <button 
                    @click="activeTab = 'health'" 
                    :class="activeTab === 'health' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-800'"
                    class="flex-1 px-4 py-4 text-sm font-medium transition-colors duration-200 whitespace-nowrap"
                >
                    <i class="fas fa-heartbeat mr-2"></i>
                    Informations de Santé
                </button>
                <button 
                    @click="activeTab = 'insurance'" 
                    :class="activeTab === 'insurance' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-800'"
                    class="flex-1 px-4 py-4 text-sm font-medium transition-colors duration-200 whitespace-nowrap"
                >
                    <i class="fas fa-shield-alt mr-2"></i>
                    Assurance
                </button>
                <button 
                    @click="activeTab = 'password'" 
                    :class="activeTab === 'password' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-800'"
                    class="flex-1 px-4 py-4 text-sm font-medium transition-colors duration-200 whitespace-nowrap"
                >
                    <i class="fas fa-lock mr-2"></i>
                    Mot de Passe
                </button>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Personal Information Tab -->
        <div x-show="activeTab === 'personal'" class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Informations Personnelles</h2>
            
            <form method="POST" action="{{ route('patient.profile.update.personal') }}">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            value="{{ old('name', auth()->user()->name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prénom -->
                    <div>
                        <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">
                            Prénom <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="prenom" 
                            id="prenom" 
                            value="{{ old('prenom', auth()->user()->prenom) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                        @error('prenom')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email', auth()->user()->email) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">
                            Téléphone <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="telephone" 
                            id="telephone" 
                            value="{{ old('telephone', auth()->user()->telephone) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                        @error('telephone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date de naissance -->
                    <div>
                        <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de naissance <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            name="date_naissance" 
                            id="date_naissance" 
                            value="{{ old('date_naissance', auth()->user()->date_naissance) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                        @error('date_naissance')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sexe -->
                    <div>
                        <label for="sexe" class="block text-sm font-medium text-gray-700 mb-2">
                            Sexe <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="sexe" 
                            id="sexe" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                            <option value="M" {{ auth()->user()->sexe === 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ auth()->user()->sexe === 'F' ? 'selected' : '' }}>Féminin</option>
                        </select>
                        @error('sexe')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CNI -->
                    <div>
                        <label for="cni" class="block text-sm font-medium text-gray-700 mb-2">
                            Carte CNI
                        </label>
                        <input 
                            type="text" 
                            id="cni" 
                            value="{{ auth()->user()->numero_cni }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed"
                            readonly
                        >
                        <p class="text-xs text-gray-400 mt-1">Le numéro CNI est défini lors de votre inscription et ne peut pas être modifié.</p>
                    </div>
                </div>

                <!-- Adresse (full width) -->
                <div class="mt-6">
                    <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">
                        Adresse <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="adresse" 
                        id="adresse" 
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >{{ old('adresse', auth()->user()->adresse) }}</textarea>
                    @error('adresse')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end">
                    <button 
                        type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200"
                    >
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>

        <!-- Health Information Tab -->
        <div x-show="activeTab === 'health'" class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Informations de Santé</h2>
            
            <form method="POST" action="{{ route('patient.profile.update.health') }}">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Poids -->
                    <div>
                        <label for="poids" class="block text-sm font-medium text-gray-700 mb-2">
                            Poids (kg)
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="poids" 
                            id="poids" 
                            value="{{ old('poids', auth()->user()->patient->poids) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Ex: 70.5"
                        >
                        @error('poids')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Taille -->
                    <div>
                        <label for="taille" class="block text-sm font-medium text-gray-700 mb-2">
                            Taille (cm)
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="taille" 
                            id="taille" 
                            value="{{ old('taille', auth()->user()->patient->taille) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Ex: 175"
                        >
                        @error('taille')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Groupe Sanguin -->
                    <div>
                        <label for="groupe_sanguin" class="block text-sm font-medium text-gray-700 mb-2">
                            Groupe Sanguin
                        </label>
                        <select 
                            name="groupe_sanguin" 
                            id="groupe_sanguin" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="">Sélectionnez...</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $groupe)
                                <option value="{{ $groupe }}" {{ auth()->user()->patient->groupe_sanguin === $groupe ? 'selected' : '' }}>
                                    {{ $groupe }}
                                </option>
                            @endforeach
                        </select>
                        @error('groupe_sanguin')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Personne contact urgence -->
                    <div>
                        <label for="personne_contact_urgence" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact d'urgence
                        </label>
                        <input 
                            type="text" 
                            name="personne_contact_urgence" 
                            id="personne_contact_urgence" 
                            value="{{ old('personne_contact_urgence', auth()->user()->patient->personne_contact_urgence) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Nom et téléphone"
                        >
                        @error('personne_contact_urgence')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fumeur -->
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="fumeur" 
                            id="fumeur" 
                            value="1"
                            {{ auth()->user()->patient->fumeur ? 'checked' : '' }}
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                        >
                        <label for="fumeur" class="ml-3 text-sm font-medium text-gray-700">
                            Fumeur
                        </label>
                    </div>

                    <!-- Consommation alcool -->
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="consommation_alcool" 
                            id="consommation_alcool" 
                            value="1"
                            {{ auth()->user()->patient->consommation_alcool ? 'checked' : '' }}
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                        >
                        <label for="consommation_alcool" class="ml-3 text-sm font-medium text-gray-700">
                            Consommation d'alcool
                        </label>
                    </div>
                </div>

                <!-- Maladies chroniques -->
                <div class="mt-6">
                    <label for="maladies_chroniques" class="block text-sm font-medium text-gray-700 mb-2">
                        Maladies chroniques
                    </label>
                    <textarea 
                        name="maladies_chroniques" 
                        id="maladies_chroniques" 
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Décrivez vos maladies chroniques (diabète, hypertension, asthme, etc.)"
                    >{{ old('maladies_chroniques', auth()->user()->patient->maladies_chroniques) }}</textarea>
                    @error('maladies_chroniques')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Médicaments actuels -->
                <div class="mt-6">
                    <label for="medicaments_actuels" class="block text-sm font-medium text-gray-700 mb-2">
                        Médicaments actuels
                    </label>
                    <textarea 
                        name="medicaments_actuels" 
                        id="medicaments_actuels" 
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Listez vos médicaments actuels avec les dosages"
                    >{{ old('medicaments_actuels', auth()->user()->patient->medicaments_actuels) }}</textarea>
                    @error('medicaments_actuels')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Chirurgies passées -->
                <div class="mt-6">
                    <label for="chirurgies_passees" class="block text-sm font-medium text-gray-700 mb-2">
                        Chirurgies passées
                    </label>
                    <textarea 
                        name="chirurgies_passees" 
                        id="chirurgies_passees" 
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Listez vos chirurgies antérieures avec les dates"
                    >{{ old('chirurgies_passees', auth()->user()->patient->chirurgies_passees) }}</textarea>
                    @error('chirurgies_passees')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes supplémentaires -->
                <div class="mt-6">
                    <label for="notes_supplementaires" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes supplémentaires
                    </label>
                    <textarea 
                        name="notes_supplementaires" 
                        id="notes_supplementaires" 
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Allergies, intolérances, autres informations importantes..."
                    >{{ old('notes_supplementaires', auth()->user()->patient->notes_supplementaires) }}</textarea>
                    @error('notes_supplementaires')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end">
                    <button 
                        type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200"
                    >
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer les informations de santé
                    </button>
                </div>
            </form>
        </div>

        <!-- Insurance Information Tab -->
        <div x-show="activeTab === 'insurance'" class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Informations d'Assurance</h2>
            
            <form method="POST" action="{{ route('patient.profile.update.insurance') }}">
                @csrf
                @method('PATCH')

                <div class="max-w-2xl">
                    <!-- Numéro de Sécurité Sociale -->
                    <div class="mb-6">
                        <label for="numero_securite_sociale" class="block text-sm font-medium text-gray-700 mb-2">
                            Numéro de Sécurité Sociale
                        </label>
                        <input 
                            type="text" 
                            name="numero_securite_sociale" 
                            id="numero_securite_sociale" 
                            value="{{ old('numero_securite_sociale', auth()->user()->patient->numero_securite_sociale) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Ex: 1 85 12 75 123 456 78"
                        >
                        @error('numero_securite_sociale')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mutuelle -->
                    <div class="mb-6">
                        <label for="mutuelle" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom de la Mutuelle
                        </label>
                        <input 
                            type="text" 
                            name="mutuelle" 
                            id="mutuelle" 
                            value="{{ old('mutuelle', auth()->user()->patient->mutuelle) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Ex: IPM, IPRESS, UNACOIS, etc."
                        >
                        @error('mutuelle')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Numéro de Mutuelle -->
                    <div class="mb-6">
                        <label for="numero_mutuelle" class="block text-sm font-medium text-gray-700 mb-2">
                            Numéro d'Adhérent Mutuelle
                        </label>
                        <input 
                            type="text" 
                            name="numero_mutuelle" 
                            id="numero_mutuelle" 
                            value="{{ old('numero_mutuelle', auth()->user()->patient->numero_mutuelle) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Numéro d'adhérent"
                        >
                        @error('numero_mutuelle')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">Information importante</p>
                                <p>Ces informations permettront une meilleure prise en charge de vos frais médicaux. Elles restent strictement confidentielles.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button 
                            type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200"
                        >
                            <i class="fas fa-save mr-2"></i>
                            Enregistrer les informations d'assurance
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Password Tab -->
        <div x-show="activeTab === 'password'" class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Changer le mot de passe</h2>
            
            <form method="POST" action="{{ route('patient.profile.update.password') }}">
                @csrf
                @method('PATCH')

                <div class="max-w-md">
                    <!-- Current Password -->
                    <div class="mb-6">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Mot de passe actuel <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="current_password" 
                            id="current_password" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Nouveau mot de passe <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                        <p class="text-gray-500 text-xs mt-1">Minimum 8 caractères</p>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmer le nouveau mot de passe <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button 
                            type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200"
                        >
                            <i class="fas fa-key mr-2"></i>
                            Changer le mot de passe
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
