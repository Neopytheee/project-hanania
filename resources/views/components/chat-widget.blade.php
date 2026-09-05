<!-- AREA WIDGET CHATBOT -->
<div id="hanania-chatbot-container" class="fixed bottom-6 right-6 z-[9999] font-['Plus_Jakarta_Sans']">
    
    <!-- Kotak Chat (Awalnya Disembunyikan) -->
    <div id="chat-window" class="hidden flex-col w-[350px] sm:w-[400px] h-[500px] max-h-[80vh] bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden mb-4 transition-all duration-300 origin-bottom-right transform scale-95 opacity-0">
        
        <!-- Header Chat -->
        <div class="bg-gradient-to-r from-hanania-purple to-hanania-purple-dark p-4 flex items-center justify-between shadow-md shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/30">
                    <span class="material-symbols-outlined text-white text-[22px]">smart_toy</span>
                </div>
                <div>
                    <h3 class="text-white font-extrabold text-[15px] leading-tight">Hanania AI</h3>
                    <p class="text-white/80 text-[11px] font-medium flex items-center gap-1 mt-0.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Online
                    </p>
                </div>
            </div>
            <button onclick="toggleChat()" class="text-white/70 hover:text-white hover:bg-white/10 w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Area Pesan (Scrollable) KITA KUNCI MAX-H -->
        <div id="chat-messages" class="flex-1 p-4 overflow-y-auto bg-slate-50 flex flex-col gap-4 max-h-[350px] [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:bg-slate-200 [&::-webkit-scrollbar-thumb]:rounded-full">
            
            <!-- Pesan Pembuka Default -->
            <div class="flex items-start gap-2 max-w-[85%]">
                <div class="w-8 h-8 bg-hanania-purple/10 rounded-full flex items-center justify-center shrink-0 mt-1">
                    <span class="material-symbols-outlined text-hanania-purple text-[16px]">smart_toy</span>
                </div>
                <div class="bg-white p-3.5 rounded-2xl rounded-tl-sm shadow-sm border border-slate-100">
                    <p class="text-[13px] text-slate-700 leading-relaxed">
                        Assalamu'alaikum, {{ auth()->user()->name ?? 'Bapak/Ibu' }}! 👋<br>
                        Saya adalah Asisten AI Hanania Travel. Ada yang bisa saya bantu terkait layanan tabungan umroh atau sistem hari ini?
                    </p>
                </div>
            </div>

            <!-- 🔥 LOOPING RIWAYAT CHAT DARI SESSION 🔥 -->
            @php
                $chatHistory = session('chat_history', []);
            @endphp

            @foreach($chatHistory as $msg)
                @php
                    $text = $msg['parts'][0]['text'] ?? '';
                    // Mengubah bintang ganda **teks** menjadi <b>teks</b> ala HTML
                    $formattedText = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', e($text));
                @endphp

                @if($msg['role'] === 'user')
                    <!-- Balon Chat User -->
                    <div class="flex items-end gap-2 max-w-[85%] self-end">
                        <div class="bg-hanania-purple text-white p-3.5 rounded-2xl rounded-tr-sm shadow-sm">
                            <p class="text-[13px] leading-relaxed whitespace-pre-wrap">{!! $formattedText !!}</p>
                        </div>
                    </div>
                @else
                    <!-- Balon Chat AI (Model) -->
                    <div class="flex items-start gap-2 max-w-[85%]">
                        <div class="w-8 h-8 bg-hanania-purple/10 rounded-full flex items-center justify-center shrink-0 mt-1">
                            <span class="material-symbols-outlined text-hanania-purple text-[16px]">smart_toy</span>
                        </div>
                        <div class="bg-white p-3.5 rounded-2xl rounded-tl-sm shadow-sm border border-slate-100">
                            <p class="text-[13px] text-slate-700 leading-relaxed whitespace-pre-wrap">{!! $formattedText !!}</p>
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Loading Indicator (Disembunyikan) -->
            <div id="chat-loading" class="hidden items-center gap-2 max-w-[85%]">
                 <div class="w-8 h-8 bg-hanania-purple/10 rounded-full flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-hanania-purple text-[16px]">smart_toy</span>
                </div>
                <div class="bg-white px-4 py-3 rounded-2xl rounded-tl-sm shadow-sm border border-slate-100 flex items-center gap-1.5">
                    <span class="w-2 h-2 bg-slate-300 rounded-full animate-bounce" style="animation-delay: 0s"></span>
                    <span class="w-2 h-2 bg-slate-300 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                    <span class="w-2 h-2 bg-slate-300 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                </div>
            </div>
            
            <!-- Penahan Scroll ke Bawah -->
            <div id="chat-anchor"></div>
        </div>

        <!-- Form Input Chat -->
        <div class="p-4 bg-white border-t border-slate-100 shrink-0">
            <form id="chat-form" class="flex items-end gap-2" onsubmit="event.preventDefault();">
                <div class="flex-1 relative">
                    <textarea id="chat-input" rows="1" placeholder="Ketik pesan di sini..." class="w-full bg-slate-50 border border-slate-200 text-[13px] text-slate-800 rounded-2xl pl-4 pr-10 py-3 focus:outline-none focus:border-hanania-purple focus:ring-1 focus:ring-hanania-purple resize-none overflow-hidden max-h-[100px]" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                </div>
                <button type="submit" class="w-11 h-11 bg-hanania-purple hover:bg-hanania-purple-dark text-white rounded-xl flex items-center justify-center shadow-md active:scale-95 transition-all shrink-0">
                    <span class="material-symbols-outlined text-[20px] ml-1">send</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Tombol Mengambang (Floating Action Button) -->
    <button id="chat-fab" onclick="toggleChat()" class="w-16 h-16 bg-hanania-purple hover:bg-hanania-purple-dark text-white rounded-full flex items-center justify-center shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:shadow-[0_8px_30px_rgba(107,33,168,0.4)] hover:-translate-y-1 transition-all duration-300 group">
        <span class="material-symbols-outlined text-[32px] group-hover:scale-110 transition-transform">chat_bubble</span>
    </button>
</div>

<!-- Script Simple untuk Buka/Tutup Chat -->
<script>
    const chatWindow = document.getElementById('chat-window');
    const fab = document.getElementById('chat-fab');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatMessages = document.getElementById('chat-messages');
    const chatLoading = document.getElementById('chat-loading');
    const chatAnchor = document.getElementById('chat-anchor');

    // Paksa scroll ke bawah setiap kali halaman dimuat (agar history terbaru terlihat)
    window.addEventListener('DOMContentLoaded', () => {
        scrollToBottom();
    });

    // Logika Buka Tutup Chat
    function toggleChat() {
        if (chatWindow.classList.contains('hidden')) {
            chatWindow.classList.remove('hidden');
            setTimeout(() => {
                chatWindow.classList.remove('scale-95', 'opacity-0');
                chatWindow.classList.add('scale-100', 'opacity-100');
                scrollToBottom();
            }, 10);
            fab.classList.add('scale-0');
        } else {
            chatWindow.classList.remove('scale-100', 'opacity-100');
            chatWindow.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                chatWindow.classList.add('hidden');
                fab.classList.remove('scale-0');
            }, 300);
        }
    }

    // Mencegah Enter bikin garis baru, malah jadi Submit
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

        // 1. Munculkan Chat User di layar
        appendMessage('user', message);
        chatInput.value = '';
        chatInput.style.height = ''; // Reset tinggi textarea
        scrollToBottom();

        // 2. Munculkan Animasi Loading AI
        chatLoading.classList.remove('hidden');
        chatLoading.classList.add('flex');
        scrollToBottom();

        // 3. Tembak ke Backend (ChatbotController)
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
            
            // Sembunyikan Loading
            chatLoading.classList.add('hidden');
            chatLoading.classList.remove('flex');

            // 4. Munculkan Balasan AI
            appendMessage('ai', data.reply);
            
        } catch (error) {
            chatLoading.classList.add('hidden');
            chatLoading.classList.remove('flex');
            appendMessage('ai', 'Maaf, terjadi kesalahan pada sistem koneksi.');
        }
        
        scrollToBottom();
    });

    // Fungsi Render Bubble Chat
    function appendMessage(sender, text) {
        const messageDiv = document.createElement('div');
        messageDiv.className = sender === 'user' 
            ? 'flex items-end gap-2 max-w-[85%] self-end' 
            : 'flex items-start gap-2 max-w-[85%]';
            
        // Render Markdown/Bintang (*bold*) sederhana dari Gemini
        let formattedText = text.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');

        if (sender === 'user') {
            messageDiv.innerHTML = `
                <div class="bg-hanania-purple text-white p-3.5 rounded-2xl rounded-tr-sm shadow-sm">
                    <p class="text-[13px] leading-relaxed whitespace-pre-wrap">${formattedText}</p>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="w-8 h-8 bg-hanania-purple/10 rounded-full flex items-center justify-center shrink-0 mt-1">
                    <span class="material-symbols-outlined text-hanania-purple text-[16px]">smart_toy</span>
                </div>
                <div class="bg-white p-3.5 rounded-2xl rounded-tl-sm shadow-sm border border-slate-100">
                    <p class="text-[13px] text-slate-700 leading-relaxed whitespace-pre-wrap">${formattedText}</p>
                </div>
            `;
        }

        // Sisipkan chat tepat di atas indikator loading
        chatMessages.insertBefore(messageDiv, chatLoading);
    }

    function scrollToBottom() {
        chatAnchor.scrollIntoView({ behavior: 'smooth' });
    }
</script>