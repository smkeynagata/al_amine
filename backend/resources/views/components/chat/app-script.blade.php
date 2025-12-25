<script>
    document.addEventListener('alpine:init', () => {
        if (window.__chatAppRegistered) {
            return;
        }

        window.__chatAppRegistered = true;

        Alpine.data('chatApp', (config) => ({
            authUserId: config.authUserId,
            conversations: config.initialConversations || [],
            contacts: config.contacts || [],
            conversationSearch: '',
            contactSearch: '',
            selectedContactId: '',
            selectedConversation: null,
            messages: [],
            loadingMessages: false,
            messageContent: '',
            sendingMessage: false,
            channelName: null,
            endpoints: config.endpoints,
            pollIntervalId: null,
            pollingIntervalMs: config.pollingIntervalMs ?? 10000,
            refreshingMessages: false,

            init() {
                this.sortConversations();
                this.emitUnreadCount();
                this.startPolling();
                window.addEventListener('beforeunload', () => this.stopPolling());
            },

            buildEndpoint(name, conversationId = null) {
                const conversationsEndpoints = this.endpoints?.conversations || {};
                const endpoint = conversationsEndpoints[name];

                if (!endpoint) {
                    console.error(`[chatApp] Endpoint manquant: conversations.${name}`);
                    return null;
                }

                if (conversationId === null) {
                    return endpoint;
                }

                return endpoint.replace('__ID__', conversationId);
            },

            get filteredConversations() {
                if (!this.conversationSearch) {
                    return this.conversations;
                }

                const term = this.conversationSearch.toLowerCase();
                return this.conversations.filter((conversation) => {
                    return (conversation.other_user?.name || '').toLowerCase().includes(term);
                });
            },

            get filteredContacts() {
                const term = (this.contactSearch || '').toLowerCase();
                return this.contacts.filter((contact) => {
                    const match = !term || contact.name.toLowerCase().includes(term) || (contact.specialty || '').toLowerCase().includes(term);
                    return match;
                });
            },

            get selectedContact() {
                if (!this.selectedContactId) {
                    return null;
                }
                return this.contacts.find((contact) => String(contact.user_id) === String(this.selectedContactId)) || null;
            },

            openConversationWithSelected() {
                if (!this.selectedContact) {
                    return;
                }
                this.openConversationWithContact(this.selectedContact);
            },

            refreshConversations() {
                const url = this.buildEndpoint('index');
                if (!url) {
                    return;
                }

                axios.get(url)
                    .then(({ data }) => {
                        this.conversations = data.conversations || [];
                        this.sortConversations();
                        this.emitUnreadCount();
                        if (this.selectedConversation) {
                            const refreshed = this.findConversationById(this.selectedConversation.id);
                            if (refreshed) {
                                this.selectedConversation = refreshed;
                            }
                        }
                    })
                    .catch((error) => console.error('[chatApp] Erreur rafraîchissement conversations', error));
            },

            startPolling() {
                this.stopPolling();

                if (!this.pollingIntervalMs || this.pollingIntervalMs <= 0) {
                    return;
                }

                this.pollIntervalId = setInterval(() => {
                    this.refreshConversations();

                    if (this.selectedConversation && !this.loadingMessages) {
                        this.refreshSelectedConversation();
                    }
                }, this.pollingIntervalMs);
            },

            stopPolling() {
                if (this.pollIntervalId) {
                    clearInterval(this.pollIntervalId);
                    this.pollIntervalId = null;
                }
            },

            refreshSelectedConversation() {
                if (!this.selectedConversation || this.refreshingMessages) {
                    return;
                }

                const conversationId = this.selectedConversation.id;
                const url = this.buildEndpoint('show', conversationId);
                if (!url) {
                    return;
                }

                this.refreshingMessages = true;

                axios.get(url)
                    .then(({ data }) => {
                        if (data.conversation) {
                            this.insertOrUpdateConversation(data.conversation);
                            const updated = this.findConversationById(data.conversation.id);
                            if (updated) {
                                this.selectedConversation = updated;
                            }
                        }

                        const rawMessages = data.messages || [];
                        const nextMessages = rawMessages
                            .map(this.normalizeMessage.bind(this))
                            .sort((a, b) => new Date(a.created_at || 0) - new Date(b.created_at || 0));

                        const previousLastId = this.messages.length ? this.messages[this.messages.length - 1]?.id : null;
                        const nextLastId = nextMessages.length ? nextMessages[nextMessages.length - 1]?.id : null;

                        this.messages = nextMessages;

                        if (nextLastId && nextLastId !== previousLastId) {
                            this.$nextTick(() => this.scrollToBottom());
                        }

                        const conversation = this.findConversationById(conversationId);
                        if (conversation) {
                            conversation.unread_count = 0;
                        }

                        this.emitUnreadCount();
                    })
                    .catch((error) => console.error('[chatApp] Erreur rafraîchissement messages', error))
                    .finally(() => {
                        this.refreshingMessages = false;
                    });
            },

            sortConversations() {
                this.conversations.sort((a, b) => new Date(b.updated_at || b.last_message?.created_at || 0) - new Date(a.updated_at || a.last_message?.created_at || 0));
            },

            findConversationById(id) {
                return this.conversations.find((conversation) => conversation.id === id) || null;
            },

            findConversationByUser(userId) {
                return this.conversations.find((conversation) => conversation.other_user?.id === userId) || null;
            },

            openConversationWithContact(contact) {
                const existing = this.findConversationByUser(contact.user_id);
                if (existing) {
                    this.selectConversation(existing);
                    return;
                }

                const url = this.buildEndpoint('store');
                if (!url) {
                    return;
                }

                axios.post(url, { user_id: contact.user_id })
                    .then(({ data }) => {
                        const conversation = data.conversation;
                        if (!conversation) {
                            return;
                        }
                        this.insertOrUpdateConversation(conversation);
                        this.selectConversation(conversation);
                        this.emitUnreadCount();
                    })
                    .catch((error) => console.error('[chatApp] Erreur création conversation', error));
            },

            selectConversation(conversation) {
                if (!conversation) {
                    return;
                }

                this.selectedConversation = conversation;
                this.messages = [];
                this.loadConversation(conversation.id);
            },

            loadConversation(conversationId) {
                const url = this.buildEndpoint('show', conversationId);
                if (!url) {
                    return;
                }

                this.loadingMessages = true;
                axios.get(url)
                    .then(({ data }) => {
                        if (data.conversation) {
                            this.insertOrUpdateConversation(data.conversation);
                            this.selectedConversation = this.findConversationById(data.conversation.id);
                        }

                        const rawMessages = data.messages || [];
                        this.messages = rawMessages.map(this.normalizeMessage.bind(this)).sort((a, b) => new Date(a.created_at || 0) - new Date(b.created_at || 0));
                        this.$nextTick(() => this.scrollToBottom());
                        this.markConversationAsRead(conversationId);
                        this.subscribeToConversation(conversationId);
                        this.emitUnreadCount();
                    })
                    .catch((error) => console.error('[chatApp] Erreur chargement conversation', error))
                    .finally(() => {
                        this.loadingMessages = false;
                    });
            },

            normalizeMessage(message) {
                return {
                    ...message,
                    isMine: message.is_mine ?? (message.sender_id === this.authUserId),
                    read: message.read ?? false,
                    time_for_humans: message.time_for_humans || new Date(message.created_at).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }),
                };
            },

            subscribeToConversation(conversationId) {
                if (!window.Echo) {
                    return;
                }

                if (this.channelName) {
                    window.Echo.leave(this.channelName);
                }

                this.channelName = `conversations.${conversationId}`;

                window.Echo.private(this.channelName)
                    .listen('.conversation.message.sent', (event) => {
                        if (!event || !event.message) {
                            return;
                        }
                        this.handleIncomingMessage(event.message);
                    });
            },

            handleIncomingMessage(rawMessage) {
                const message = this.normalizeMessage(rawMessage);
                const conversationId = message.conversation_id;

                const conversation = this.findConversationById(conversationId);
                if (conversation) {
                    this.updateConversationPreview(conversation, message);
                }

                if (this.selectedConversation && this.selectedConversation.id === conversationId) {
                    this.messages = [...this.messages, message].sort((a, b) => new Date(a.created_at || 0) - new Date(b.created_at || 0));
                    this.$nextTick(() => this.scrollToBottom());
                    this.markConversationAsRead(conversationId);
                }

                this.emitUnreadCount();
            },

            insertOrUpdateConversation(conversation) {
                const existing = this.findConversationById(conversation.id);
                if (!existing) {
                    this.conversations.unshift(conversation);
                } else {
                    Object.assign(existing, conversation);
                }
                this.sortConversations();
            },

            updateConversationPreview(conversation, message) {
                conversation.last_message = message;
                conversation.updated_at = message.created_at;

                if (message.sender_id !== this.authUserId) {
                    conversation.unread_count = (conversation.unread_count || 0) + 1;
                } else {
                    conversation.unread_count = 0;
                }

                this.sortConversations();
            },

            markConversationAsRead(conversationId) {
                const url = this.buildEndpoint('read', conversationId);
                if (!url) {
                    return;
                }

                axios.post(url)
                    .then(() => {
                        const conversation = this.findConversationById(conversationId);
                        if (conversation) {
                            conversation.unread_count = 0;
                        }
                        this.messages = this.messages.map((message) => {
                            if (message.recipient_id === this.authUserId) {
                                return { ...message, read: true };
                            }
                            return message;
                        });
                        this.emitUnreadCount();
                    })
                    .catch((error) => console.error('[chatApp] Erreur marquage lecture', error));
            },

            async sendMessage() {
                const content = this.messageContent.trim();
                if (!content || !this.selectedConversation) {
                    return;
                }

                this.sendingMessage = true;

                try {
                    const url = this.buildEndpoint('messages', this.selectedConversation.id);
                    if (!url) {
                        return;
                    }

                    const { data } = await axios.post(url, {
                        content,
                    });

                    if (data.message) {
                        const message = this.normalizeMessage(data.message);
                        this.messages = [...this.messages, message].sort((a, b) => new Date(a.created_at || 0) - new Date(b.created_at || 0));
                        this.updateConversationPreview(this.selectedConversation, message);
                        this.$nextTick(() => this.scrollToBottom());
                        this.messageContent = '';
                        this.emitUnreadCount();
                    }
                } catch (error) {
                    console.error('[chatApp] Erreur envoi message', error);
                } finally {
                    this.sendingMessage = false;
                }
            },

            scrollToBottom() {
                if (this.$refs.messagesContainer) {
                    this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
                }
            },

            emitUnreadCount() {
                if (!Array.isArray(this.conversations)) {
                    return;
                }

                const totalUnread = this.conversations.reduce((sum, conversation) => {
                    return sum + (conversation.unread_count || 0);
                }, 0);

                window.__chatUnreadCount = totalUnread;
                window.dispatchEvent(new CustomEvent('chat:unread-count-changed', { detail: totalUnread }));
            },
        }));
    });
</script>
