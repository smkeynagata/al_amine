@extends('layouts.app')

@section('title', 'Messagerie')
@section('page-title', 'Messagerie patient')

@section('sidebar')
    @include('patient.partials.sidebar')
@endsection

@section('content')
@php
    $messagingConfig = [
        'currentUserId' => auth()->id(),
        'endpoints' => [
            'list' => route('patient.messagerie.conversations'),
            'create' => route('patient.messagerie.conversations.store'),
            'messages' => route('patient.messagerie.conversations.show', ['conversation' => '__ID__']),
            'send' => route('patient.messagerie.conversations.messages.store', ['conversation' => '__ID__']),
            'markRead' => route('patient.messagerie.conversations.read', ['conversation' => '__ID__']),
            'archive' => route('patient.messagerie.conversations.archive', ['conversation' => '__ID__']),
        ],
        'initialPractitioners' => $practitioners,
    ];
@endphp
<div
    x-data="patientMessagingStore({{ Illuminate\Support\Js::from($messagingConfig) }})"
    x-init="init()"
    class="space-y-6"
>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar conversations -->
        <div class="bg-white rounded-2xl shadow-lg border border-blue-100 overflow-hidden flex flex-col">
            <div class="p-4 border-b border-blue-100 space-y-4">
                <div>
                    <label class="block text-xs uppercase tracking-wide text-blue-500 font-semibold mb-2">Rechercher</label>
                    <input
                        type="search"
                        class="w-full px-4 py-2.5 rounded-lg border border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-sm"
                        placeholder="Nom du praticien..."
                        x-model.debounce.500ms="filters.search"
                        @input="onSearchChanged()"
                    >
                </div>
                <div class="flex flex-wrap gap-2">
                    <button
                        type="button"
                        @click="setArchiveFilter(null)"
                        :class="filters.archived === null ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-600 hover:bg-blue-100'"
                        class="px-3 py-1.5 rounded-full text-xs font-semibold transition-colors"
                    >
                        Toutes
                    </button>
                    <button
                        type="button"
                        @click="setArchiveFilter(false)"
                        :class="filters.archived === false ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-600 hover:bg-blue-100'"
                        class="px-3 py-1.5 rounded-full text-xs font-semibold transition-colors"
                    >
                        Actives
                    </button>
                    <button
                        type="button"
                        @click="setArchiveFilter(true)"
                        :class="filters.archived === true ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-600 hover:bg-blue-100'"
                        class="px-3 py-1.5 rounded-full text-xs font-semibold transition-colors"
                    >
                        Archivées
                    </button>
                </div>
            </div>

            <div class="p-4 border-b border-blue-100">
                <h3 class="text-xs uppercase tracking-wide text-blue-500 font-semibold mb-2">Nouvelle conversation</h3>
                <div class="space-y-2">
                    <select
                        class="w-full px-3 py-2 rounded-lg border border-blue-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        x-model="selectedPractitionerId"
                    >
                        <option value="">Sélectionner un praticien</option>
                        <template x-for="praticien in practitioners" :key="praticien.id">
                            <option :value="praticien.id" x-text="praticien.name"></option>
                        </template>
                    </select>
                    <button
                        type="button"
                        class="w-full inline-flex justify-center items-center px-3 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition"
                        @click="startConversation()"
                        :disabled="!selectedPractitionerId || creatingConversation"
                    >
                        <svg x-show="creatingConversation" class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span x-text="creatingConversation ? 'Création...' : 'Démarrer'">Démarrer</span>
                    </button>
                    <p x-show="practitioners.length === 0" class="text-xs text-blue-500/70">Aucun praticien disponible pour le moment.</p>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto" x-ref="conversationList">
                <template x-if="isLoadingConversations">
                    <div class="p-6 text-center text-blue-400 text-sm">Chargement des conversations...</div>
                </template>

                <template x-if="!isLoadingConversations && conversations.length === 0">
                    <div class="p-6 text-center text-blue-400 text-sm">
                        Aucune conversation pour l'instant. Démarrez un échange avec un praticien.
                    </div>
                </template>

                <template x-for="conversation in conversations" :key="conversation.id">
                    <button
                        type="button"
                        class="w-full text-left border-b border-blue-50 hover:bg-blue-50 transition"
                        :class="activeConversationId === conversation.id ? 'bg-blue-100/80' : ''"
                        @click="selectConversation(conversation.id)"
                    >
                        <div class="p-4 space-y-2">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-blue-900" x-text="conversation.other_user?.name ?? 'Conversation'">Conversation</p>
                                    <p class="text-xs text-blue-500" x-text="conversation.other_user?.role === 'PRATICIEN' ? 'Praticien' : (conversation.other_user?.role ?? '')"></p>
                                </div>
                                <div class="flex flex-col items-end space-y-1">
                                    <span class="text-[11px] text-blue-400" x-text="conversation.last_message?.time_for_humans ?? ''"></span>
                                    <span
                                        x-show="conversation.unread_count > 0"
                                        class="inline-flex items-center justify-center h-5 min-w-[1.5rem] px-2 rounded-full bg-blue-600 text-white text-[11px] font-semibold"
                                        x-text="conversation.unread_count"
                                    ></span>
                                </div>
                            </div>
                            <p class="text-sm text-blue-700 line-clamp-2" x-text="conversation.last_message?.content ?? 'Nouvelle conversation'">Dernier message</p>
                            <template x-if="conversation.is_archived">
                                <span class="inline-flex items-center text-[11px] font-semibold text-amber-600 bg-amber-100 rounded-full px-2 py-0.5">Archivée</span>
                            </template>
                        </div>
                    </button>
                </template>
            </div>
        </div>

        <!-- Conversation panel -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-blue-100 flex flex-col min-h-[600px]">
            <template x-if="!activeConversation">
                <div class="flex-1 flex flex-col items-center justify-center text-center space-y-4 p-10 text-blue-500">
                    <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2v10z" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold mb-1">Sélectionnez une conversation</h3>
                        <p class="text-sm text-blue-400">Ou démarrez un nouvel échange avec un praticien.</p>
                    </div>
                </div>
            </template>

            <template x-if="activeConversation">
                <div class="flex flex-1 flex-col">
                    <div class="p-4 border-b border-blue-100 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-blue-900" x-text="activeConversation.other_user?.name ?? 'Conversation'"></h3>
                            <p class="text-xs text-blue-500" x-text="activeConversation.other_user?.role === 'PRATICIEN' ? 'Praticien' : ''"></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="text-xs font-semibold px-3 py-1.5 rounded-full border transition"
                                :class="activeConversation.is_archived ? 'border-amber-400 text-amber-600 hover:bg-amber-50' : 'border-blue-300 text-blue-600 hover:bg-blue-50'"
                                @click="toggleArchive(activeConversation)"
                            >
                                <span x-text="activeConversation.is_archived ? 'Désarchiver' : 'Archiver'"></span>
                            </button>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-blue-50/50" x-ref="messagesContainer">
                        <template x-if="isLoadingMessages">
                            <div class="text-center text-blue-400 text-sm py-4">Chargement des messages...</div>
                        </template>

                        <template x-if="!isLoadingMessages && messages.length === 0">
                            <div class="text-center text-blue-400 text-sm py-4">
                                Aucun message encore. Envoyez le premier !
                            </div>
                        </template>

                        <template x-if="pagination.next">
                            <div class="text-center">
                                <button
                                    type="button"
                                    class="inline-flex items-center px-4 py-2 text-xs font-semibold text-blue-600 border border-blue-300 rounded-full hover:bg-blue-100"
                                    @click="loadOlderMessages()"
                                >
                                    Charger les messages précédents
                                </button>
                            </div>
                        </template>

                        <template x-for="message in messages" :key="message.id">
                            <div class="flex" :class="message.is_mine ? 'justify-end' : 'justify-start'">
                                <div class="max-w-xl">
                                    <div :class="message.is_mine ? 'bg-blue-600 text-white rounded-2xl rounded-tr-sm' : 'bg-white text-blue-900 border border-blue-100 rounded-2xl rounded-tl-sm'" class="px-4 py-3 shadow-sm space-y-2">
                                        <p class="text-sm whitespace-pre-line" x-text="message.content"></p>
                                        <template x-if="message.attachments && message.attachments.length">
                                            <div class="space-y-1">
                                                <template x-for="attachment in message.attachments" :key="attachment.id">
                                                    <a :href="attachment.url" target="_blank" class="flex items-center text-xs font-semibold text-blue-100 hover:text-white" :class="message.is_mine ? '' : 'text-blue-600 hover:text-blue-800'">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7v10a4 4 0 01-8 0V7a4 4 0 018 0z"></path>
                                                        </svg>
                                                        <span x-text="attachment.name"></span>
                                                    </a>
                                                </template>
                                            </div>
                                        </template>
                                        <div class="flex items-center justify-between text-[11px] opacity-70">
                                            <span x-text="message.time_for_humans"></span>
                                            <span x-show="message.is_mine && message.read_at">Vu</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="border-t border-blue-100 p-4 space-y-4 bg-white">
                        <template x-if="error">
                            <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-3 py-2 rounded-lg" x-text="error"></div>
                        </template>

                        <template x-if="successMessage">
                            <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-3 py-2 rounded-lg" x-text="successMessage"></div>
                        </template>

                        <div class="space-y-3">
                            <textarea
                                x-model="messageForm.content"
                                class="w-full border border-blue-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Écrivez votre message..."
                                rows="3"
                                @keydown.ctrl.enter.prevent="sendMessage()"
                            ></textarea>
                            <div class="flex items-center justify-between flex-wrap gap-3">
                                <div class="flex items-center gap-3">
                                    <label class="inline-flex items-center px-3 py-2 border border-blue-300 rounded-lg text-sm font-semibold text-blue-600 hover:bg-blue-50 cursor-pointer">
                                        <input type="file" class="hidden" multiple x-ref="fileInput" @change="handleFiles($event)">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L20 7.828 18.172 6l-6.586 6.586a2 2 0 102.828 2.828"></path>
                                        </svg>
                                        Joindre un fichier
                                    </label>
                                    <div class="flex flex-wrap gap-2" x-show="filePreviews.length">
                                        <template x-for="(file, index) in filePreviews" :key="file.key">
                                            <div class="px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-full text-xs text-blue-700 inline-flex items-center gap-2">
                                                <span x-text="file.name"></span>
                                                <button type="button" class="text-blue-500 hover:text-blue-700" @click="removeFile(index)">×</button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition disabled:opacity-60"
                                    @click="sendMessage()"
                                    :disabled="isSending || (!messageForm.content.trim() && messageForm.files.length === 0)"
                                >
                                    <svg x-show="isSending" class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>
                                    <span>Envoyer</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function patientMessagingStore(config) {
    return {
        config,
        conversations: [],
        filters: {
            archived: null,
            search: ''
        },
        isLoadingConversations: false,
        isLoadingMessages: false,
        isSending: false,
        creatingConversation: false,
        messages: [],
        pagination: {
            next: null
        },
        activeConversationId: null,
        messageForm: {
            content: '',
            files: []
        },
        filePreviews: [],
        error: null,
        successMessage: null,
        practitioners: config.initialPractitioners ?? [],
        selectedPractitionerId: '',
        currentChannel: null,
        currentUserId: config.currentUserId,
        get activeConversation() {
            return this.conversations.find(conversation => conversation.id === this.activeConversationId) ?? null;
        },
        async init() {
            await this.fetchConversations();
        },
        endpoint(name, id = null) {
            let url = this.config.endpoints[name];
            if (id !== null) {
                url = url.replace('__ID__', id);
            }
            return url;
        },
        async fetchConversations() {
            this.isLoadingConversations = true;
            try {
                const params = {};
                if (this.filters.archived !== null) {
                    params.archived = this.filters.archived ? 1 : 0;
                }
                if (this.filters.search) {
                    params.search = this.filters.search;
                }
                const { data } = await axios.get(this.endpoint('list'), { params });
                this.conversations = data;
                if (this.activeConversationId) {
                    const stillExists = this.conversations.some(c => c.id === this.activeConversationId);
                    if (!stillExists && this.conversations.length > 0) {
                        this.selectConversation(this.conversations[0].id);
                    }
                } else if (this.conversations.length > 0) {
                    this.selectConversation(this.conversations[0].id);
                }
            } catch (error) {
                console.error(error);
                this.error = "Impossible de charger les conversations.";
            } finally {
                this.isLoadingConversations = false;
            }
        },
        onSearchChanged() {
            this.fetchConversations();
        },
        setArchiveFilter(value) {
            if (this.filters.archived === value) return;
            this.filters.archived = value;
            this.fetchConversations();
        },
        async startConversation() {
            if (!this.selectedPractitionerId) return;
            this.creatingConversation = true;
            this.error = null;
            try {
                const { data } = await axios.post(this.endpoint('create'), {
                    practitioner_id: this.selectedPractitionerId
                });
                const existingIndex = this.conversations.findIndex(c => c.id === data.id);
                if (existingIndex >= 0) {
                    this.conversations.splice(existingIndex, 1, data);
                } else {
                    this.conversations.unshift(data);
                }
                this.selectedPractitionerId = '';
                this.selectConversation(data.id);
                this.successMessage = 'Conversation démarrée.';
                this.scheduleSuccessClear();
            } catch (error) {
                console.error(error);
                this.error = error.response?.data?.message ?? "Impossible de créer la conversation.";
            } finally {
                this.creatingConversation = false;
            }
        },
        async selectConversation(conversationId) {
            if (this.activeConversationId === conversationId && this.messages.length > 0) {
                return;
            }
            this.activeConversationId = conversationId;
            await this.fetchMessages(true);
            await this.markAsRead();
            this.subscribeToConversation();
        },
        async fetchMessages(reset = false) {
            if (!this.activeConversationId) return;
            this.isLoadingMessages = reset;
            try {
                const params = {};
                if (!reset && this.pagination.next) {
                    const nextUrl = new URL(this.pagination.next, window.location.origin);
                    params.page = nextUrl.searchParams.get('page');
                }
                const { data } = await axios.get(this.endpoint('messages', this.activeConversationId), { params });
                const messagesBatch = data.data;
                if (reset) {
                    this.messages = messagesBatch;
                } else {
                    this.messages = [...this.messages, ...messagesBatch];
                }
                this.pagination.next = data.next_page_url;
                this.updateConversationMetaFromMessages(messagesBatch);
                if (reset) {
                    this.$nextTick(() => this.scrollToBottom());
                }
            } catch (error) {
                console.error(error);
                this.error = "Impossible de charger les messages.";
            } finally {
                this.isLoadingMessages = false;
            }
        },
        async loadOlderMessages() {
            if (!this.pagination.next || this.isLoadingMessages) return;
            await this.fetchMessages(false);
        },
        async sendMessage() {
            if (!this.activeConversationId || (this.messageForm.content.trim() === '' && this.messageForm.files.length === 0)) {
                return;
            }
            this.isSending = true;
            this.error = null;
            try {
                const formData = new FormData();
                formData.append('content', this.messageForm.content ?? '');
                this.messageForm.files.forEach(file => {
                    formData.append('files[]', file);
                });
                const { data } = await axios.post(this.endpoint('send', this.activeConversationId), formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
                this.messages.push(data);
                this.updateConversationAfterMessage(data, true);
                this.clearMessageForm();
                this.$nextTick(() => this.scrollToBottom());
                this.successMessage = 'Message envoyé';
                this.scheduleSuccessClear();
            } catch (error) {
                console.error(error);
                this.error = error.response?.data?.message ?? "Impossible d'envoyer le message.";
            } finally {
                this.isSending = false;
            }
        },
        async markAsRead() {
            if (!this.activeConversationId) return;
            try {
                await axios.post(this.endpoint('markRead', this.activeConversationId));
                const conversation = this.conversations.find(c => c.id === this.activeConversationId);
                if (conversation) {
                    conversation.unread_count = 0;
                }
            } catch (error) {
                console.error(error);
            }
        },
        async toggleArchive(conversation) {
            try {
                await axios.post(this.endpoint('archive', conversation.id), {
                    archived: !conversation.is_archived
                });
                conversation.is_archived = !conversation.is_archived;
                this.successMessage = conversation.is_archived ? 'Conversation archivée' : 'Conversation réactivée';
                this.scheduleSuccessClear();
                if (this.filters.archived !== null) {
                    this.fetchConversations();
                }
            } catch (error) {
                console.error(error);
                this.error = "Impossible de modifier l'état d'archivage.";
            }
        },
        handleFiles(event) {
            const files = Array.from(event.target.files || []);
            this.messageForm.files = files;
            this.filePreviews = files.map((file, index) => ({
                key: `${file.name}-${file.lastModified}-${index}`,
                name: file.name,
                size: file.size
            }));
        },
        removeFile(index) {
            this.messageForm.files.splice(index, 1);
            this.filePreviews.splice(index, 1);
            if (this.messageForm.files.length === 0 && this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        },
        clearMessageForm() {
            this.messageForm.content = '';
            this.messageForm.files = [];
            this.filePreviews = [];
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        },
        scrollToBottom() {
            const container = this.$refs.messagesContainer;
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        },
        scheduleSuccessClear() {
            setTimeout(() => {
                this.successMessage = null;
            }, 3000);
        },
        subscribeToConversation() {
            if (!window.Echo || !this.activeConversationId) {
                return;
            }
            if (this.currentChannel) {
                window.Echo.leave(this.currentChannel.name);
                this.currentChannel = null;
            }
            this.currentChannel = window.Echo.private(`conversations.${this.activeConversationId}`);
            this.currentChannel.listen('.conversation.message.sent', (event) => {
                this.handleIncomingMessage(event.message);
            });
        },
        handleIncomingMessage(message) {
            const conversation = this.conversations.find(c => c.id === message.conversation_id);
            const isMine = message.sender_id === this.currentUserId;
            if (conversation) {
                conversation.last_message = message;
                conversation.updated_at = message.created_at;
                if (!isMine && conversation.id !== this.activeConversationId) {
                    conversation.unread_count = (conversation.unread_count ?? 0) + 1;
                }
                if (conversation.id === this.activeConversationId) {
                    this.messages.push(message);
                    this.$nextTick(() => this.scrollToBottom());
                    if (!isMine) {
                        this.markAsRead();
                    }
                }
            } else {
                this.fetchConversations();
            }
        },
        updateConversationMetaFromMessages(batch) {
            if (!this.activeConversationId) return;
            const conversation = this.conversations.find(c => c.id === this.activeConversationId);
            if (!conversation || batch.length === 0) return;
            const latest = batch[batch.length - 1];
            conversation.last_message = latest;
            conversation.updated_at = latest.created_at;
        },
        updateConversationAfterMessage(message, isMine) {
            const conversation = this.conversations.find(c => c.id === message.conversation_id);
            if (conversation) {
                conversation.last_message = message;
                conversation.updated_at = message.created_at;
                conversation.unread_count = 0;
            }
        }
    }
}
</script>
@endpush
