@extends('praticien.layouts.app')

@section('title', 'Messages')

@section('content')
@php
    $chatConfig = [
        'authUserId' => $authUser->id,
        'initialConversations' => $initialConversations,
        'contacts' => $contacts,
        'endpoints' => [
            'conversations' => [
                'index' => route('praticien.messages.conversations'),
                'store' => route('praticien.messages.conversations.store'),
                'show' => url('/praticien/messages/conversations/__ID__'),
                'messages' => url('/praticien/messages/conversations/__ID__/messages'),
                'read' => url('/praticien/messages/conversations/__ID__/read'),
            ],
        ],
    ];
@endphp

<div class="mb-6 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-700 to-blue-800 p-6 text-white shadow-xl">
    <h1 class="text-2xl font-bold">Messagerie du praticien</h1>
    <p class="mt-1 text-sm text-blue-100">Restez en contact avec le secrétariat en temps réel.</p>
</div>

<div
    x-data="chatApp(@js($chatConfig))"
    x-init="init()"
    class="grid gap-6 lg:grid-cols-[380px_1fr]"
>
    <div class="flex flex-col gap-6">
        <div class="rounded-2xl border border-indigo-100 bg-white p-5 shadow-lg">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Conversations</h2>
                <button @click="refreshConversations" type="button" class="rounded-full p-2 text-gray-400 transition hover:bg-indigo-50 hover:text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9M20 20v-5h-.581m-15.357-2a8.003 8.003 0 0015.357 2"></path>
                    </svg>
                </button>
            </div>

            <div class="relative">
                <input
                    type="search"
                    placeholder="Rechercher une conversation..."
                    x-model="conversationSearch"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200"
                >
                <svg class="absolute right-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 105.65 5.65a7.5 7.5 0 0010.6 10.6z"></path>
                </svg>
            </div>

            <div class="mt-4 space-y-2 overflow-y-auto pr-1" style="max-height: 26rem" x-ref="conversationList">
                <template x-if="filteredConversations.length === 0">
                    <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 p-4 text-center text-xs text-gray-500">
                        Aucune conversation à afficher.
                    </div>
                </template>

                <template x-for="conversation in filteredConversations" :key="conversation.id">
                    <button
                        type="button"
                        @click="selectConversation(conversation)"
                        class="w-full rounded-2xl border border-transparent p-3 text-left transition hover:border-indigo-100 hover:bg-indigo-50"
                        :class="selectedConversation && selectedConversation.id === conversation.id ? 'border-indigo-200 bg-indigo-50' : ''"
                    >
                        <div class="flex items-start gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-600">
                                <span x-text="conversation.other_user?.initials ?? '?' "></span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-gray-900" x-text="conversation.other_user?.name ?? 'Conversation'"></p>
                                        <p class="text-[11px] text-indigo-500" x-text="conversation.other_user?.role_label ?? ''"></p>
                                    </div>
                                    <span class="text-[11px] self-start text-gray-400" x-text="conversation.last_message?.time_for_humans ?? ''"></span>
                                </div>
                                <p class="mt-1 truncate text-xs text-gray-500" x-text="conversation.last_message?.content ?? 'Aucun message pour le moment.'"></p>
                                <div class="mt-2 flex items-center gap-2">
                                    <span x-show="conversation.unread_count > 0" class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-2 text-[11px] font-semibold text-white" x-text="conversation.unread_count"></span>
                                    <span
                                        x-show="conversation.last_message && !conversation.unread_count && conversation.last_message.read_at"
                                        class="text-[11px] text-gray-400"
                                    >Lu</span>
                                </div>
                            </div>
                        </div>
                    </button>
                </template>
            </div>
        </div>

        <div class="rounded-2xl border border-indigo-100 bg-white p-5 shadow-lg">
            <h2 class="text-lg font-semibold text-gray-900">Secrétaires</h2>
            <p class="mt-1 text-xs text-gray-500">Démarrez une conversation privée avec l’équipe d’accueil.</p>

            <div class="relative mt-4">
                <input
                    type="search"
                    placeholder="Rechercher une secrétaire"
                    x-model="contactSearch"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200"
                >
                <svg class="absolute right-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 105.65 5.65a7.5 7.5 0 0010.6 10.6z"></path>
                </svg>
            </div>

            <div class="mt-4 space-y-2 overflow-y-auto pr-1" style="max-height: 18rem;">
                <template x-if="filteredContacts.length === 0">
                    <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 p-4 text-center text-xs text-gray-500">
                        Aucun résultat trouvé.
                    </div>
                </template>

                <template x-for="contact in filteredContacts" :key="contact.user_id">
                    <div class="flex items-center gap-3 rounded-2xl border border-gray-100 p-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-50 text-sm font-semibold text-blue-600" x-text="contact.initials"></div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-gray-900" x-text="contact.name"></p>
                            <p class="text-xs text-gray-500" x-text="contact.role_label ?? 'Utilisateur'"></p>
                        </div>
                        <button
                            type="button"
                            class="rounded-xl bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white shadow hover:bg-blue-700"
                            @click="openConversationWithContact(contact)"
                        >
                            Discuter
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-indigo-100 bg-white shadow-lg">
        <template x-if="!selectedConversation">
            <div class="flex h-full flex-col items-center justify-center gap-4 p-10 text-center text-gray-500">
                <div class="rounded-full bg-indigo-50 p-4">
                    <svg class="h-8 w-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H7a4 4 0 01-4-4V7a4 4 0 014-4h10a4 4 0 014 4v5a4 4 0 01-4 4h-1l-4 4z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-600">Choisissez une conversation</p>
                    <p class="mt-1 text-sm">Sélectionnez un contact pour consulter l’historique et échanger en direct.</p>
                </div>
            </div>
        </template>

        <template x-if="selectedConversation">
            <div class="flex h-full flex-col">
                <div class="flex items-center justify-between border-b border-gray-100 p-6">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-600">
                            <span x-text="selectedConversation.other_user?.initials ?? '?' "></span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900" x-text="selectedConversation.other_user?.name ?? 'Conversation'"></p>
                            <p class="text-xs text-gray-400" x-text="selectedConversation.other_user?.role_label ?? ''"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400" x-text="selectedConversation.updated_at_for_humans ?? ''"></span>
                    </div>
                </div>

                <div class="flex-1 space-y-4 overflow-y-auto bg-gray-50/80 p-6" x-ref="messagesContainer">
                    <template x-if="loadingMessages">
                        <div class="flex h-full items-center justify-center text-sm text-gray-400">
                            Chargement de la conversation...
                        </div>
                    </template>

                    <template x-if="!loadingMessages && messages.length === 0">
                        <div class="flex h-full items-center justify-center text-sm text-gray-400">
                            Aucun message pour ce fil. Envoyez le premier !
                        </div>
                    </template>

                    <template x-for="message in messages" :key="message.id">
                        <div class="flex w-full" :class="message.isMine ? 'justify-end' : 'justify-start'">
                            <div class="max-w-[75%] rounded-2xl px-4 py-3" :class="message.isMine ? 'rounded-br-sm bg-indigo-600 text-white shadow-lg' : 'rounded-bl-sm bg-white text-gray-800 shadow'">
                                <p class="text-sm" x-text="message.content"></p>
                                <div class="mt-1 flex items-center justify-between gap-2 text-[11px]" :class="message.isMine ? 'text-indigo-100/80' : 'text-gray-400'">
                                    <span x-text="message.time_for_humans ?? ''"></span>
                                    <span x-show="message.isMine && message.read" class="ml-2 text-[10px] uppercase tracking-wide">Lu</span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="border-t border-gray-100 p-4">
                    <form @submit.prevent="sendMessage" class="flex items-end gap-3">
                        <textarea
                            x-model="messageContent"
                            rows="2"
                            placeholder="Votre message au secrétariat..."
                            class="max-h-32 w-full resize-none rounded-2xl border border-gray-200 bg-gray-50 px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                        ></textarea>
                        <button
                            type="submit"
                            :disabled="sendingMessage || !messageContent.trim()"
                            class="flex h-11 w-11 items-center justify-center rounded-full bg-indigo-600 text-white shadow-lg transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:bg-indigo-300"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </template>
    </div>
</div>
@endsection

@push('styles')
<style>[x-cloak]{display:none!important;}</style>
@endpush

@push('scripts')
    @include('components.chat.app-script')
@endpush

