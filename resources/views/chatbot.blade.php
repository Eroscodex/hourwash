<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <title>HourWash</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .chat-scroll::-webkit-scrollbar { width: 4px; }
        .chat-scroll::-webkit-scrollbar-track { background: transparent; }
        .chat-scroll::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.15); border-radius: 10px; }
        .dark .chat-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
    </style>
</head>

<body class="bg-[#F5F5F7] dark:bg-[#000000] font-['Inter'] antialiased min-h-screen flex flex-col transition-colors duration-300">

    
    <header class="sticky top-0 z-50 bg-white/95 dark:bg-[#1C1C1E]/95 border-b border-black/10 dark:border-white/10 px-4 md:px-8 py-3 backdrop-blur-xl">
        <div class="max-w-3xl mx-auto flex items-center justify-between">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-10 h-10 rounded-full object-cover shadow-md group-hover:scale-105 transition-transform bg-white p-0.5 border border-black/10 dark:border-white/10">
                <div>
                    <span class="text-lg font-bold font-['Outfit'] text-slate-900 dark:text-white block leading-tight">HourWash</span>
                    <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold uppercase tracking-wider">Online</span>
                </div>
            </a>

            <div class="flex items-center gap-2">
                <button id="chatbot-theme-toggle" class="p-2 px-3 rounded-xl bg-white dark:bg-white/10 text-slate-900 dark:text-[#F5F5F7] border border-black/10 dark:border-white/10 hover:scale-105 transition-all text-xs font-semibold flex items-center gap-1.5 shadow-sm cursor-pointer">
                    <span class="dark:hidden">Dark</span>
                    <span class="hidden dark:inline">Light</span>
                </button>
                <a href="{{ route('welcome') }}" class="p-2 px-3 rounded-xl bg-white dark:bg-white/10 text-slate-900 dark:text-[#F5F5F7] border border-black/10 dark:border-white/10 hover:scale-105 transition-all text-xs font-semibold shadow-sm">
                    Back
                </a>
            </div>
        </div>
    </header>

    
    <main class="flex-1 flex flex-col max-w-3xl mx-auto w-full px-4 py-4">

        <div id="chat-box" class="flex-1 overflow-y-auto space-y-4 chat-scroll pb-4" style="max-height: calc(100vh - 180px);">
            
            <div class="flex items-start gap-3">
                <img src="{{ asset('favicon.svg') }}" alt="HourWash" class="w-8 h-8 rounded-full object-cover bg-white p-0.5 border border-black/10 dark:border-white/10 shadow-sm flex-shrink-0 mt-0.5">
                <div class="bg-white dark:bg-[#2C2C2E] text-slate-900 dark:text-[#F5F5F7] px-4 py-3 rounded-2xl rounded-tl-none max-w-[80%] border border-black/10 dark:border-white/10 shadow-sm text-sm leading-relaxed">
                    <strong class="text-[#007AFF] dark:text-[#0A84FF] block mb-1 text-xs font-bold">HourWash AI</strong>
                    Hello! Welcome to Hour Wash Laundry Shop! I can help you with order tracking, machine availability, services & rates, promotions, and store hours. How can I assist you today?
                </div>
            </div>
        </div>

        
        <div class="sticky bottom-0 bg-[#F5F5F7] dark:bg-[#000000] pt-2 pb-4">
            <div class="flex gap-2">
                <input
                    id="message"
                    type="text"
                    placeholder="Ask about your order, services, hours..."
                    class="flex-1 bg-white dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-[#007AFF] dark:focus:border-[#0A84FF] text-slate-900 dark:text-[#F5F5F7] shadow-sm transition-colors"
                    onkeydown="if(event.key==='Enter')sendMessage()"
                >
                <button
                    onclick="sendMessage()"
                    class="bg-[#007AFF] dark:bg-[#0A84FF] hover:bg-[#0062CC] text-white font-bold px-5 py-3 rounded-2xl text-sm transition shadow-sm cursor-pointer active:scale-95">
                    Send
                </button>
            </div>
        </div>

    </main>

<script>
    // Theme Toggle
    document.getElementById('chatbot-theme-toggle').addEventListener('click', function() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    });

    function sendMessage() {
        const input = document.getElementById('message');
        const message = input.value.trim();
        if (!message) return;

        const chatBox = document.getElementById('chat-box');

        // User message with avatar
        chatBox.innerHTML += `
            <div class="flex items-start gap-3 justify-end">
                <div class="bg-[#007AFF] dark:bg-[#0A84FF] text-white px-4 py-3 rounded-2xl rounded-tr-none max-w-[80%] shadow-sm text-sm leading-relaxed">
                    ${message}
                </div>
                <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center flex-shrink-0 mt-0.5 text-xs font-bold text-slate-600 dark:text-slate-300 border border-black/10 dark:border-white/10">
                    You
                </div>
            </div>
        `;
        input.value = "";
        chatBox.scrollTop = chatBox.scrollHeight;

        // Typing indicator with avatar
        const typingId = 'typing-' + Date.now();
        chatBox.innerHTML += `
            <div class="flex items-start gap-3" id="${typingId}">
                <img src="{{ asset('favicon.svg') }}" alt="HourWash" class="w-8 h-8 rounded-full object-cover bg-white p-0.5 border border-black/10 dark:border-white/10 shadow-sm flex-shrink-0 mt-0.5">
                <div class="bg-white dark:bg-[#2C2C2E] text-slate-500 px-4 py-3 rounded-2xl rounded-tl-none border border-black/10 dark:border-white/10 shadow-sm text-sm">
                    <span class="animate-pulse">Typing...</span>
                </div>
            </div>
        `;
        chatBox.scrollTop = chatBox.scrollHeight;

        fetch('/chatbot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Server error: " + response.status);
            }
            return response.json();
        })
        .then(data => {
            const typingEl = document.getElementById(typingId);
            if (typingEl) typingEl.remove();

            const formattedReply = data.reply.replace(/\n/g, '<br>');

            chatBox.innerHTML += `
                <div class="flex items-start gap-3">
                    <img src="{{ asset('favicon.svg') }}" alt="HourWash" class="w-8 h-8 rounded-full object-cover bg-white p-0.5 border border-black/10 dark:border-white/10 shadow-sm flex-shrink-0 mt-0.5">
                    <div class="bg-white dark:bg-[#2C2C2E] text-slate-900 dark:text-[#F5F5F7] px-4 py-3 rounded-2xl rounded-tl-none max-w-[80%] border border-black/10 dark:border-white/10 shadow-sm text-sm leading-relaxed">
                        <strong class="text-[#007AFF] dark:text-[#0A84FF] block mb-1 text-xs font-bold">HourWash AI</strong>
                        ${formattedReply}
                    </div>
                </div>
            `;
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(error => {
            const typingEl = document.getElementById(typingId);
            if (typingEl) typingEl.remove();

            console.error(error);
            chatBox.innerHTML += `
                <div class="flex items-start gap-3">
                    <img src="{{ asset('favicon.svg') }}" alt="HourWash" class="w-8 h-8 rounded-full object-cover bg-white p-0.5 border border-black/10 dark:border-white/10 shadow-sm flex-shrink-0 mt-0.5">
                    <div class="bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 px-4 py-3 rounded-2xl rounded-tl-none max-w-[80%] shadow-sm text-sm">
                        Could not reach assistant. Please try again.
                    </div>
                </div>
            `;
        });
    }
</script>

</body>
</html>