{{-- Include the static bilingual FAQ dataset --}}
<x-chatbot.public-faq-data />

<div x-data="publicFaqChatbot" class="relative">
    <!-- ═══════════════════════════════════════════ -->
    <!-- FLOATING TRIGGER BUTTON                     -->
    <!-- ═══════════════════════════════════════════ -->
    <button @click="toggleChat()"
            x-show="!isOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-90 translate-y-4"
            class="fixed bottom-6 right-6 z-50 flex items-center justify-center w-14 h-14 text-white rounded-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 hover:scale-110 active:scale-95 transition-all duration-300 focus:outline-none"
            aria-label="Tanya SILA">
        <!-- Chat Bubble Icon -->
        <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
    </button>

    <!-- ═══════════════════════════════════════════ -->
    <!-- CHAT WINDOW PANEL                           -->
    <!-- ═══════════════════════════════════════════ -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-8"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-8"
         x-cloak
         class="fixed bottom-6 right-6 z-50 flex flex-col w-[320px] sm:w-[380px] h-[480px] sm:h-[520px] bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl border border-gray-200/50 dark:border-gray-800/50 shadow-2xl rounded-2xl overflow-hidden focus:outline-none select-none">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-md">
            <div class="flex items-center gap-2.5">
                <!-- Avatar Mascot with Pulsing Status Indicator -->
                <div class="relative w-9 h-9 bg-white/15 rounded-full flex items-center justify-center border border-white/20">
                    <span class="text-white font-extrabold text-sm tracking-tighter">SILA</span>
                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-emerald-400 ring-2 ring-emerald-500 animate-ping"></span>
                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-emerald-400 ring-2 ring-emerald-500"></span>
                </div>
                <div>
                    <h3 class="font-bold text-sm leading-tight">SILA</h3>
                    <p class="text-[10px] text-emerald-100/90 font-medium">Asisten Informasi SILAKU</p>
                </div>
            </div>
            
            <!-- Header Controls -->
            <div class="flex items-center gap-1.5">
                <!-- Clear Chat Button -->
                <button @click="clearChat()"
                        class="p-1.5 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-colors focus:outline-none"
                        title="Hapus Percakapan"
                        aria-label="Hapus Percakapan">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
                <!-- Close Window Button -->
                <button @click="toggleChat()"
                        class="p-1.5 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-colors focus:outline-none"
                        title="Tutup Chat"
                        aria-label="Tutup Chat">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Chat Container -->
        <div x-ref="chatContainer"
             class="flex-1 overflow-y-auto px-4 py-4 space-y-4 bg-gray-50/50 dark:bg-gray-950/20 select-text scrollbar-thin scrollbar-thumb-gray-200 scrollbar-track-transparent">
            
            <template x-for="(msg, index) in messages" :key="index">
                <div class="flex flex-col"
                     :class="msg.sender === 'user' ? 'items-end' : 'items-start'">
                    
                    <div class="flex gap-2 max-w-[85%] items-end"
                         :class="msg.sender === 'user' ? 'flex-row-reverse' : 'flex-row'">
                        
                        <!-- Bot Mascot Thumbnail (only for bot) -->
                        <div x-show="msg.sender !== 'user'"
                             class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-[10px] font-black border border-emerald-200/30">
                            S
                        </div>
                        
                        <div class="flex flex-col">
                            <!-- Text Bubble -->
                            <div class="px-3.5 py-2.5 rounded-2xl shadow-sm text-sm leading-relaxed"
                                 :class="msg.sender === 'user'
                                    ? 'bg-emerald-500 text-white rounded-br-none'
                                    : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 border border-gray-100 dark:border-gray-700/50 rounded-bl-none'">
                                
                                <p class="whitespace-pre-line" x-text="msg.text"></p>

                                <!-- Links inside response -->
                                <template x-if="msg.links && msg.links.length > 0">
                                    <div class="mt-3.5 space-y-2">
                                        <template x-for="link in msg.links">
                                            <div>
                                                <!-- If it is a trigger link (FAQ ID mapping) -->
                                                <template x-if="link.isTrigger">
                                                    <button @click="triggerQuickReply(link.faqId)"
                                                            :disabled="isTyping"
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/30 rounded-xl hover:bg-emerald-100 dark:hover:bg-emerald-900/50 hover:scale-102 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-emerald-50 disabled:hover:scale-100">
                                                        <span x-text="link.label"></span>
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                                    </button>
                                                </template>
                                                <!-- If it is a real URL navigation link -->
                                                <template x-if="!link.isTrigger">
                                                    <a :href="link.url"
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl hover:shadow-md hover:scale-102 transition-all">
                                                        <span x-text="link.label"></span>
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                    </a>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                            
                            <!-- Timestamp -->
                            <span class="text-[9px] text-gray-400 mt-1"
                                  :class="msg.sender === 'user' ? 'text-right' : 'text-left'"
                                  x-text="msg.time"></span>
                        </div>
                    </div>

                    <!-- Suggestions / Quick Replies from Bot -->
                    <template x-if="msg.sender !== 'user' && msg.suggestions && msg.suggestions.length > 0">
                        <div class="mt-2.5 pl-8 pr-4 flex flex-wrap gap-1.5">
                            <template x-for="sug in msg.suggestions">
                                <button @click="triggerQuickReply(sug.id)"
                                        :disabled="isTyping"
                                        class="px-2.5 py-1.5 text-xs font-semibold bg-white dark:bg-gray-800/80 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 border border-gray-200/60 dark:border-gray-700/60 hover:border-emerald-300 dark:hover:border-emerald-800/40 rounded-xl transition-all shadow-sm active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-white disabled:dark:hover:bg-gray-800/80 disabled:hover:border-gray-200/60 disabled:dark:hover:border-gray-700/60 disabled:active:scale-100"
                                        x-text="sug.label">
                                </button>
                            </template>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Typing Indicator -->
            <div x-show="isTyping" class="flex flex-col items-start transition-opacity duration-300">
                <div class="flex gap-2 max-w-[85%] items-end flex-row">
                    <!-- Bot Mascot Thumbnail -->
                    <div class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-[10px] font-black border border-emerald-200/30">
                        S
                    </div>
                    <div class="flex flex-col">
                        <div class="px-3.5 py-2.5 rounded-2xl shadow-sm text-sm bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 border border-gray-100 dark:border-gray-700/50 rounded-bl-none flex items-center gap-2">
                            <span class="animate-pulse" x-text="locale === 'en' ? 'SILA is typing...' : 'SILA sedang mengetik...'"></span>
                            <div class="flex gap-1 items-center">
                                <span class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                                <span class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                                <span class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Footer Form -->
        <div class="p-3 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800/60">
            <form @submit.prevent="sendMessage()" class="flex items-end gap-2 bg-gray-50 dark:bg-gray-950 px-3 py-2 rounded-xl border border-gray-200/50 dark:border-gray-800/50 focus-within:border-emerald-500/50 focus-within:ring-2 focus-within:ring-emerald-500/10 transition-all duration-300">
                <!-- Multiline text input support -->
                <textarea x-model="userInput"
                          @keydown.enter.prevent="if(!event.shiftKey && !isTyping) { sendMessage() }"
                          rows="1"
                          class="flex-1 max-h-18 bg-transparent text-sm text-gray-800 dark:text-gray-200 border-0 p-0 focus:ring-0 focus:outline-none placeholder-gray-400 resize-none font-sans overflow-y-auto leading-relaxed"
                          :placeholder="locale === 'en' ? 'Type your question...' : 'Tanyakan sesuatu...'"
                          aria-label="Input pesan chatbot"></textarea>
                
                <!-- Send Button -->
                <button type="submit"
                        :disabled="isTyping"
                        class="flex-shrink-0 flex items-center justify-center w-7 h-7 text-emerald-500 hover:text-emerald-600 hover:scale-105 active:scale-95 transition-all focus:outline-none disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:scale-100 disabled:active:scale-100"
                        aria-label="Kirim Pesan">
                    <svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('publicFaqChatbot', () => ({
        isOpen: false,
        messages: [],
        userInput: '',
        locale: 'id',
        isTyping: false,

        // List of sensitive words that should trigger account security warning
        sensitiveKeywords: [
            'password admin', 'password administrator', 'kredensial bawaan', 
            'kredensial default', 'akun default', 'akun bawaan', 'password default', 
            'seeder', 'db_password', 'db_username', 'database', 'tabel', 
            'passwords', 'credentials', 'env file', '.env', 'config', 'token', 
            'api key', 'csrf token', 'aminudin', 'hery', 'dekan', 'amin123', 'heri123'
        ],

        init() {
            // Read document language from Laravel locale
            this.locale = '{{ app()->getLocale() }}';

            // Check if widget should open from past session
            this.isOpen = localStorage.getItem('silaku_public_faq_open') === 'true';

            // Load message history from localStorage
            const savedHistory = localStorage.getItem('silaku_public_faq_history');
            if (savedHistory) {
                try {
                    this.messages = JSON.parse(savedHistory);
                } catch (e) {
                    this.messages = [];
                }
            }

            // If empty, populate with greeting message
            if (this.messages.length === 0) {
                this.addGreetingMessage();
            }

            this.scrollToBottom();
        },

        addGreetingMessage() {
            const welcomeText = this.locale === 'en'
                ? "Hello! I am SILA, the SILAKU Information Assistant. I can help explain SILAKU, account access, and login issues. What would you like to know?"
                : "Halo! Saya SILA, Asisten Informasi SILAKU. Saya dapat membantu menjelaskan SILAKU, akses akun, dan kendala login. Apa yang ingin Anda ketahui?";

            const initialSuggestions = this.locale === 'en'
                ? [
                    { label: "What is SILAKU?", id: "about_silaku" },
                    { label: "How to log in", id: "login_howto" },
                    { label: "I do not have an account", id: "no_account" },
                    { label: "I cannot log in", id: "login_failed" },
                    { label: "Contact administrator", id: "contact_admin" }
                  ]
                : [
                    { label: "Apa itu SILAKU?", id: "about_silaku" },
                    { label: "Cara login", id: "login_howto" },
                    { label: "Saya belum punya akun", id: "no_account" },
                    { label: "Saya tidak bisa login", id: "login_failed" },
                    { label: "Hubungi admin", id: "contact_admin" }
                  ];

            this.messages.push({
                id: 'welcome',
                sender: 'bot',
                text: welcomeText,
                suggestions: initialSuggestions,
                time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
            });
            this.saveHistory();
        },

        sendMessage() {
            const text = this.userInput.trim();
            if (!text || this.isTyping) return;

            // 1. Add user message
            this.messages.push({
                sender: 'user',
                text: text,
                time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
            });

            this.userInput = '';
            this.saveHistory();
            this.scrollToBottom();

            // Set typing state
            this.isTyping = true;
            this.scrollToBottom();

            // 2. Simulate bot typing after a 2-second delay
            setTimeout(() => {
                if (!this.isTyping) return;

                const response = this.findBestMatch(text);
                
                // Construct suggestions
                let suggestions = [];
                if (response.suggestions && window.SILAKU_FAQ_DATA) {
                    const dataset = window.SILAKU_FAQ_DATA[this.locale] || window.SILAKU_FAQ_DATA['id'];
                    response.suggestions.forEach(suggestionId => {
                        const found = dataset.find(faq => faq.id === suggestionId);
                        if (found) {
                            suggestions.push({
                                label: found.questions[0] || found.id,
                                id: found.id
                            });
                        }
                    });
                }

                this.messages.push({
                    sender: 'bot',
                    text: response.answer,
                    links: response.links || [],
                    suggestions: suggestions,
                    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                });

                this.isTyping = false;
                this.limitHistory();
                this.saveHistory();
                this.scrollToBottom();
            }, 2000);
        },

        triggerQuickReply(faqId) {
            if (!window.SILAKU_FAQ_DATA || this.isTyping) return;
            const dataset = window.SILAKU_FAQ_DATA[this.locale] || window.SILAKU_FAQ_DATA['id'];
            const faq = dataset.find(f => f.id === faqId);
            
            if (faq) {
                // Add user query
                this.messages.push({
                    sender: 'user',
                    text: faq.questions[0] || faq.id,
                    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                });
                this.saveHistory();
                this.scrollToBottom();

                this.isTyping = true;
                this.scrollToBottom();

                // Respond
                setTimeout(() => {
                    if (!this.isTyping) return;

                    let suggestions = [];
                    if (faq.suggestions) {
                        faq.suggestions.forEach(suggestionId => {
                            const found = dataset.find(f => f.id === suggestionId);
                            if (found) {
                                suggestions.push({
                                    label: found.questions[0] || found.id,
                                    id: found.id
                                });
                            }
                        });
                    }

                    this.messages.push({
                        sender: 'bot',
                        text: faq.answer,
                        links: faq.links || [],
                        suggestions: suggestions,
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    });

                    this.isTyping = false;
                    this.limitHistory();
                    this.saveHistory();
                    this.scrollToBottom();
                }, 2000);
            }
        },

        cleanInput(text) {
            return text.toLowerCase()
                .replace(/[.,\/#!$%\^&\*;:{}=\-_`~()?\u2014]/g, "")
                .replace(/\s+/g, " ")
                .trim();
        },

        findBestMatch(text) {
            const cleaned = this.cleanInput(text);
            if (!cleaned) return null;

            // 1. Intercept sensitive credentials inquiry
            const isSensitive = this.sensitiveKeywords.some(key => cleaned.includes(key));
            if (isSensitive) {
                return {
                    answer: this.locale === 'en'
                        ? 'Sorry, account information, credentials, and internal configurations cannot be provided. To obtain official access, please contact the SILAKU administrator.'
                        : 'Maaf, informasi akun, kredensial, dan konfigurasi internal tidak dapat diberikan. Untuk mendapatkan akses resmi, silakan hubungi pengelola SILAKU.',
                    links: [],
                    suggestions: ['contact_admin', 'login_howto']
                };
            }

            const inputTokens = cleaned.split(' ');
            const dataset = window.SILAKU_FAQ_DATA ? (window.SILAKU_FAQ_DATA[this.locale] || window.SILAKU_FAQ_DATA['id']) : [];

            let bestMatch = null;
            let highestScore = 0;

            // 2. Exact phrase matching
            for (const faq of dataset) {
                if (faq.exactPhrases && faq.exactPhrases.some(phrase => cleaned === phrase || cleaned.includes(phrase))) {
                    return faq;
                }
            }

            // 3. Weighted keyword matching
            for (const faq of dataset) {
                let score = 0;
                let hasNegativeKeyword = false;

                // Check negative keywords (abort matching if found)
                if (faq.negativeKeywords) {
                    for (const neg of faq.negativeKeywords) {
                        if (cleaned.includes(neg)) {
                            hasNegativeKeyword = true;
                            break;
                        }
                    }
                }

                if (hasNegativeKeyword) continue;

                // Priority keywords (weight 2)
                if (faq.priorityKeywords) {
                    for (const prio of faq.priorityKeywords) {
                        if (cleaned.includes(prio)) {
                            score += 2;
                        }
                    }
                }

                // General keywords (weight 1)
                if (faq.keywords) {
                    for (const key of faq.keywords) {
                        if (cleaned.includes(key)) {
                            score += 1;
                            if (inputTokens.includes(key)) {
                                score += 1; // bonus for full token match
                            }
                        }
                    }
                }

                if (score > highestScore) {
                    highestScore = score;
                    bestMatch = faq;
                }
            }

            // Threshold: score >= 2 (ensures composite keywords match to avoid false positive)
            if (highestScore >= 2 && bestMatch) {
                return bestMatch;
            }

            // 4. Fallback if no match
            const fallbackFaq = dataset.find(faq => faq.id === 'unknown');
            return fallbackFaq || {
                answer: this.locale === 'en'
                    ? 'Sorry, I could not understand that question. I can help with information about SILAKU, login access, account creation, password issues, and contacting the administrator.'
                    : 'Maaf, saya belum memahami pertanyaan tersebut. Saya dapat membantu menjelaskan SILAKU, cara login, pembuatan akun, kendala password, dan cara menghubungi pengelola.',
                links: [],
                suggestions: ['about_silaku', 'login_howto', 'forgot_password', 'contact_admin']
            };
        },

        toggleChat() {
            this.isOpen = !this.isOpen;
            localStorage.setItem('silaku_public_faq_open', this.isOpen);
            if (!this.isOpen) {
                this.isTyping = false;
            } else {
                this.scrollToBottom();
            }
        },

        clearChat() {
            this.isTyping = false;
            this.messages = [];
            localStorage.removeItem('silaku_public_faq_history');
            this.addGreetingMessage();
            this.scrollToBottom();
        },

        limitHistory() {
            // Keep maximum of 30 messages in history
            if (this.messages.length > 30) {
                const greeting = this.messages.find(m => m.id === 'welcome') || this.messages[0];
                const sliced = this.messages.slice(-29);
                if (greeting && !sliced.some(m => m.id === 'welcome')) {
                    this.messages = [greeting, ...sliced];
                } else {
                    this.messages = sliced;
                }
            }
        },

        saveHistory() {
            localStorage.setItem('silaku_public_faq_history', JSON.stringify(this.messages));
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.chatContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        }
    }));
});
</script>

