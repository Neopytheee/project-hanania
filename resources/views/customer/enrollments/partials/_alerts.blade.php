@if(session('error'))
    <div class="bg-red-50 text-red-700 p-4 rounded-[16px] text-[13px] font-bold border border-red-100 flex gap-3 shadow-sm items-start">
        <span class="material-symbols-outlined text-red-500 [font-variation-settings:'FILL'_1]">error</span>
        <div class="flex-1 mt-0.5">{{ session('error') }}</div>
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-50 text-red-700 p-4 rounded-[16px] text-[13px] border border-red-100 flex gap-3 shadow-sm items-start">
        <span class="material-symbols-outlined text-red-500 [font-variation-settings:'FILL'_1]">warning</span>
        <ul class="list-disc pl-4 space-y-1 flex-1 font-medium mt-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="bg-emerald-50 text-emerald-700 p-4 rounded-[16px] text-[13px] font-bold border border-emerald-100 flex gap-3 shadow-sm items-center">
        <span class="material-symbols-outlined text-emerald-500 [font-variation-settings:'FILL'_1]">check_circle</span>
        <div class="flex-1">{{ session('success') }}</div>
    </div>
@endif