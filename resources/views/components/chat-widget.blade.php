<!-- AREA WIDGET CHATBOT -->
<div id="hanania-chatbot-container" class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-[9999] font-sans flex flex-col items-end">
    
    <!-- Kotak Chat -->
    <div id="chat-window" class="hidden flex-col w-[calc(100vw-2rem)] sm:w-[380px] h-[500px] sm:h-[550px] max-h-[80vh] sm:max-h-[85vh] bg-[#FBF8FF] rounded-[1.5rem] sm:rounded-[2rem] shadow-2xl border border-hanania-purple/15 overflow-hidden mb-3 sm:mb-4 transition-all duration-300 origin-bottom-right transform scale-95 opacity-0 shrink-0">
        
        <!-- Header Chat (30% Warna Brand) -->
        <div class="relative bg-hanania-purple-dark p-3.5 sm:p-5 flex items-center justify-between shadow-md shrink-0 overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/5 rounded-full blur-xl pointer-events-none"></div>
            <div class="absolute right-12 -bottom-6 w-16 h-16 bg-hanania-gold/10 rounded-full blur-lg pointer-events-none"></div>

            <div class="flex items-center gap-3 relative z-10">
                <div class="relative w-10 h-10 sm:w-11 sm:h-11 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-md border border-white/20 shadow-sm shrink-0">
                    <span class="material-symbols-outlined text-white text-[20px] sm:text-[24px]">smart_toy</span>
                    <span class="absolute bottom-0 right-0 w-3 h-3 sm:w-3.5 sm:h-3.5 bg-hanania-gold border-2 border-hanania-purple-dark rounded-full"></span>
                </div>
                <div>
                    <h3 class="font-heading text-white font-extrabold text-[15px] sm:text-[16px] leading-tight">Hanania AI</h3>
                    <p class="text-white/80 text-[10px] sm:text-[11px] font-medium mt-0.5">Asisten Virtual Anda</p>
                </div>
            </div>
            
            <button onclick="toggleChat()" class="relative z-10 text-white/60 hover:text-white hover:bg-white/10 w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center transition-all active:scale-90">
                <span class="material-symbols-outlined text-[20px] sm:text-[22px]">close</span>
            </button>
        </div>

        <!-- Area Pesan (Scrollable) -->
        <!-- 🔥 INI DIA KUNCI SAKTI BOSKU KEMBALI: max-h-[350px] sm:max-h-[380px] 🔥 -->
        <div id="chat-messages" class="flex-1 p-3.5 sm:p-5 overflow-y-auto bg-white flex flex-col gap-4 max-h-[350px] sm:max-h-[380px] custom-scrollbar scroll-smooth">
            
            <!-- Pesan Pembuka Default -->
            <div class="flex items-start gap-2 sm:gap-2.5 max-w-[90%] sm:max-w-[88%]">
                <div class="w-7 h-7 sm:w-8 sm:h-8 bg-hanania-purple-light rounded-full flex items-center justify-center shrink-0 border border-hanania-purple/10">
                    <span class="material-symbols-outlined text-hanania-purple text-[14px] sm:text-[16px]">smart_toy</span>
                </div>
                <div class="bg-hanania-purple-light/40 p-3 sm:p-4 rounded-2xl sm:rounded-[1.25rem] rounded-tl-sm border border-hanania-purple/10 shadow-sm">
                    <p class="text-[13px] sm:text-[14px] text-hanania-purple-dark font-medium leading-relaxed">
                        Assalamu'alaikum, {{ auth()->user()->name ?? 'Bapak/Ibu' }}! 👋<br><br>
                        Saya adalah Asisten AI Hanania. Ada yang bisa saya bantu terkait informasi paket atau tabungan hari ini?
                    </p>
                </div>
            </div>

            <!-- LOOPING RIWAYAT CHAT DARI SESSION -->
            @php
                $chatHistory = session('chat_history', []);
            @endphp

            @foreach($chatHistory as $msg)
                @php
                    $text = $msg['parts'][0]['text'] ?? '';
                    $formattedText = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', e($text));
                @endphp

                @if($msg['role'] === 'user')
                    <div class="flex items-end gap-2 max-w-[90%] sm:max-w-[88%] self-end">
                        <div class="bg-hanania-purple text-white p-3 sm:p-4 rounded-2xl sm:rounded-[1.25rem] rounded-tr-sm shadow-md">
                            <p class="text-[13px] sm:text-[14px] leading-relaxed whitespace-pre-wrap font-medium">{!! $formattedText !!}</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-start gap-2 sm:gap-2.5 max-w-[90%] sm:max-w-[88%]">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-hanania-purple-light rounded-full flex items-center justify-center shrink-0 border border-hanania-purple/10">
                            <span class="material-symbols-outlined text-hanania-purple text-[14px] sm:text-[16px]">smart_toy</span>
                        </div>
                        <div class="bg-hanania-purple-light/40 p-3 sm:p-4 rounded-2xl sm:rounded-[1.25rem] rounded-tl-sm border border-hanania-purple/10 shadow-sm">
                            <p class="text-[13px] sm:text-[14px] text-hanania-purple-dark font-medium leading-relaxed whitespace-pre-wrap">{!! $formattedText !!}</p>
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Loading Indicator -->
            <div id="chat-loading" class="hidden items-center gap-2 sm:gap-2.5 max-w-[90%] sm:max-w-[88%]">
                 <div class="w-7 h-7 sm:w-8 sm:h-8 bg-hanania-purple-light rounded-full flex items-center justify-center shrink-0 border border-hanania-purple/10">
                    <span class="material-symbols-outlined text-hanania-purple text-[14px] sm:text-[16px]">smart_toy</span>
                </div>
                <div class="bg-hanania-purple-light/40 px-3.5 py-3 sm:px-4 sm:py-3.5 rounded-2xl sm:rounded-[1.25rem] rounded-tl-sm border border-hanania-purple/10 shadow-sm flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-hanania-purple/40 rounded-full animate-bounce" style="animation-delay: 0s"></span>
                    <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-hanania-purple/40 rounded-full animate-bounce" style="animation-delay: 0.15s"></span>
                    <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-hanania-purple/40 rounded-full animate-bounce" style="animation-delay: 0.3s"></span>
                </div>
            </div>
            
            <div id="chat-anchor"></div>
        </div>

        <!-- Form Input Chat -->
        <div class="p-3 sm:p-4 bg-white border-t border-hanania-purple/10 shrink-0">
            <form id="chat-form" class="flex items-end gap-2" onsubmit="event.preventDefault();">
                <div class="flex-1 relative">
                    <textarea 
                        id="chat-input" 
                        rows="1" 
                        placeholder="Ketik pesan..." 
                        class="w-full bg-slate-50 border border-slate-200 text-[13px] sm:text-[14px] text-hanania-purple-dark font-medium rounded-xl sm:rounded-2xl pl-3 pr-3 py-2.5 sm:py-3.5 focus:outline-none focus:border-hanania-purple focus:ring-1 focus:ring-hanania-purple resize-none overflow-hidden max-h-[100px] sm:max-h-[120px] shadow-inner transition-colors custom-scrollbar" 
                        oninput="this.style.height = 'auto'; this.style.height = this.scrollHeight + 'px'"
                    ></textarea>
                </div>
                <button type="submit" class="w-10 h-10 sm:w-14 sm:h-14 btn-hanania-gold rounded-xl sm:rounded-2xl flex items-center justify-center active:scale-90 transition-all shrink-0" aria-label="Kirim Pesan">
                    <span class="material-symbols-outlined text-[18px] sm:text-[26px] ml-1">send</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Tombol Mengambang (FAB) -->
    <button id="chat-fab" onclick="toggleChat()" class="w-14 h-14 sm:w-[72px] sm:h-[72px] bg-hanania-purple hover:bg-hanania-purple-dark text-white rounded-full flex items-center justify-center shadow-[0_8px_30px_rgba(97,57,143,0.3)] hover:shadow-[0_12px_40px_rgba(97,57,143,0.5)] hover:-translate-y-1 transition-all duration-300 group border-2 border-white/20 relative z-50">
        <span class="material-symbols-outlined text-[28px] sm:text-[36px] group-hover:scale-110 transition-transform">forum</span>
        <span class="absolute top-0 right-0 sm:top-1 sm:right-1 w-3 h-3 sm:w-4 sm:h-4 bg-hanania-gold border-2 border-white rounded-full animate-pulse"></span>
    </button>
</div>

<!-- Script -->
<script>
    const chatWindow = document.getElementById('chat-window');
    const fab = document.getElementById('chat-fab');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatMessages = document.getElementById('chat-messages');
    const chatLoading = document.getElementById('chat-loading');
    const chatAnchor = document.getElementById('chat-anchor');

    window.addEventListener('DOMContentLoaded', () => {
        scrollToBottom();
    });

    function toggleChat() {
        if (chatWindow.classList.contains('hidden')) {
            chatWindow.classList.remove('hidden');
            setTimeout(() => {
                chatWindow.classList.remove('scale-95', 'opacity-0');
                chatWindow.classList.add('scale-100', 'opacity-100');
                scrollToBottom();
            }, 10);
            fab.classList.add('scale-0', 'opacity-0');
        } else {
            chatWindow.classList.remove('scale-100', 'opacity-100');
            chatWindow.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                chatWindow.classList.add('hidden');
                fab.classList.remove('scale-0', 'opacity-0');
            }, 300);
        }
    }

    chatInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });

    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = chatInput.value.trim();
        if (!message) return;

        appendMessage('user', message);
        chatInput.value = '';
        chatInput.style.height = 'auto';
        scrollToBottom();

        chatLoading.classList.remove('hidden');
        chatLoading.classList.add('flex');
        scrollToBottom();

        try {
            const response = await fetch("{{ route('chatbot.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            
            chatLoading.classList.add('hidden');
            chatLoading.classList.remove('flex');

            appendMessage('ai', data.reply);
            
        } catch (error) {
            chatLoading.classList.add('hidden');
            chatLoading.classList.remove('flex');
            appendMessage('ai', 'Maaf, sistem sedang sibuk atau terjadi gangguan koneksi. Mohon coba beberapa saat lagi.');
        }
        
        scrollToBottom();
    });

    function appendMessage(sender, text) {
        const messageDiv = document.createElement('div');
        messageDiv.className = sender === 'user' 
            ? 'flex items-end gap-2 max-w-[90%] sm:max-w-[88%] self-end' 
            : 'flex items-start gap-2 sm:gap-2.5 max-w-[90%] sm:max-w-[88%]';
            
        // 🔒 DOM XSS FIX: Buat kontainer teks aman menggunakan textContent (Anti HTML Injection)
        const bubbleDiv = document.createElement('div');
        bubbleDiv.className = sender === 'user'
            ? 'bg-hanania-purple text-white p-3 sm:p-4 rounded-2xl sm:rounded-[1.25rem] rounded-tr-sm shadow-md'
            : 'bg-hanania-purple-light/40 p-3 sm:p-4 rounded-2xl sm:rounded-[1.25rem] rounded-tl-sm border border-hanania-purple/10 shadow-sm';

        const pTag = document.createElement('p');
        pTag.className = sender === 'user'
            ? 'text-[13px] sm:text-[14px] font-medium leading-relaxed whitespace-pre-wrap'
            : 'text-[13px] sm:text-[14px] text-hanania-purple-dark font-medium leading-relaxed whitespace-pre-wrap';
        
        // Menggunakan textContent untuk membersihkan script berbahaya dari LLM/User
        pTag.textContent = text; 

        bubbleDiv.appendChild(pTag);
        messageDiv.appendChild(bubbleDiv);

        chatMessages.insertBefore(messageDiv, chatLoading);
    }

    function scrollToBottom() {
        setTimeout(() => {
            chatAnchor.scrollIntoView({ behavior: 'smooth', block: 'end' });
        }, 50);
    }
</script>