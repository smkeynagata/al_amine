@extends('layouts.app')

@section('title', 'Mes Notifications')
@section('page-title', 'Notifications')

@section('sidebar')
    @include('patient.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Mes Notifications</h2>
            <p class="text-gray-600 mt-1">Restez informé de tous vos rendez-vous et factures</p>
        </div>
        
        @if(auth()->user()->unreadNotifications->count() > 0)
            <form method="POST" action="{{ route('patient.notifications.mark-all-as-read') }}">
                @csrf
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-check-double mr-2"></i>
                    Tout marquer comme lu
                </button>
            </form>
        @endif
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-full p-3 mr-4">
                    <i class="fas fa-bell text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Notifications totales</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $notifications->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="bg-yellow-100 rounded-full p-3 mr-4">
                    <i class="fas fa-envelope text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Non lues</p>
                    <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->unreadNotifications->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-full p-3 mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Lues</p>
                    <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->readNotifications->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @forelse($notifications as $notification)
            <div class="border-b border-gray-200 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }} hover:bg-gray-50 transition-colors">
                <div class="p-4 flex items-start gap-4">
                    <!-- Icon -->
                    <div class="flex-shrink-0">
                        @php
                            $iconClass = 'fas fa-info-circle text-blue-600';
                            if(isset($notification->data['type'])) {
                                switch($notification->data['type']) {
                                    case 'rdv_rappel':
                                        $iconClass = 'fas fa-calendar-check text-green-600';
                                        break;
                                    case 'demande_rdv_status':
                                        $iconClass = $notification->data['status'] === 'validee' 
                                            ? 'fas fa-check-circle text-green-600' 
                                            : 'fas fa-times-circle text-red-600';
                                        break;
                                    case 'facture_impayee':
                                        $iconClass = 'fas fa-exclamation-triangle text-yellow-600';
                                        break;
                                }
                            }
                        @endphp
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="{{ $iconClass }} text-xl"></i>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $notification->data['message'] ?? 'Nouvelle notification' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 ml-4">
                                @if(!$notification->read_at)
                                    <form method="POST" action="{{ route('patient.notifications.mark-as-read', $notification->id) }}">
                                        @csrf
                                        <button type="submit" 
                                                class="text-blue-600 hover:text-blue-700 text-sm font-medium"
                                                title="Marquer comme lu">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                <form method="POST" action="{{ route('patient.notifications.destroy', $notification->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-700 text-sm font-medium"
                                            title="Supprimer"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Action Button -->
                        @if(isset($notification->data['type']))
                            <div class="mt-3">
                                @if($notification->data['type'] === 'rdv_rappel')
                                    <a href="{{ route('patient.rendezvous.show', $notification->data['rendez_vous_id']) }}" 
                                       class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 font-medium">
                                        Voir le rendez-vous <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                @elseif($notification->data['type'] === 'demande_rdv_status')
                                    <a href="{{ route('patient.mes-rdv') }}" 
                                       class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 font-medium">
                                        Voir mes rendez-vous <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                @elseif($notification->data['type'] === 'facture_impayee')
                                    <a href="{{ route('patient.paiement', $notification->data['facture_id']) }}" 
                                       class="inline-flex items-center text-sm text-yellow-600 hover:text-yellow-700 font-medium">
                                        Payer maintenant <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center text-gray-500">
                <i class="fas fa-bell-slash text-5xl mb-4 text-gray-300"></i>
                <p class="text-lg font-medium">Aucune notification</p>
                <p class="text-sm mt-1">Vous serez notifié ici de tous vos rendez-vous et factures</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
     class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
    {{ session('success') }}
</div>
@endif
@endsection
