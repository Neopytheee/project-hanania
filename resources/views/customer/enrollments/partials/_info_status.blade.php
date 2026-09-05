<div class="bg-gradient-to-br from-gray-900 to-purple-900 rounded-[24px] p-6 shadow-lg text-center relative overflow-hidden">
    <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
    <div class="relative z-10">
        <span class="inline-block bg-white/20 backdrop-blur-sm border border-white/20 text-yellow-400 text-[10px] uppercase tracking-[0.2em] font-extrabold px-3 py-1 rounded-full mb-3">
            Status Perjalanan
        </span>
        <h3 class="text-[22px] font-black text-white tracking-tight leading-none">
            {{ strtoupper($enrollment->statusText()) }}
        </h3>
    </div>
</div>