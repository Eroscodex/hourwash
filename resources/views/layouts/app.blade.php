<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hour Wash Laundry') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('hourwash.ico') }}">


    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">


    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="font-sans antialiased bg-gray-100">


<div class="min-h-screen flex">


    <!-- Sidebar -->

    <aside id="sidebar"class="fixed md:static inset-y-0 left-0 w-64 bg-gradient-to-b from-blue-700 to-cyan-500 text-white transform -translate-x-full md:translate-x-0 transition duration-300 z-50 flex flex-col">
    <button id="close-btn"class="md:hidden absolute top-4 right-4 text-white text-3xl">✕</button>

    <div class="p-6 flex flex-col items-center text-center">

        <div class="w-20 h-20 bg-white rounded-full shadow-lg flex items-center justify-center overflow-hidden">
            <img
                src="{{ asset('hourwash.ico') }}"
                alt="Hour Wash Logo"
                class="w-16 h-16 object-contain"
            >
        </div>

        <h1 class="mt-4 text-2xl font-bold">
            Hour Wash
        </h1>

        <p class="text-blue-100 text-sm">
            Laundry Management
        </p>

    </div>



        <nav class="px-4 space-y-2">


            @if(auth()->user()->role === 'admin')


                <!-- ADMIN MENU -->

                <a href="{{ route('admin.dashboard') }}"
                   class="block px-4 py-3 rounded-lg hover:bg-white/20">
                    🏠 Admin Dashboard
                </a>


                <a href="{{ route('admin.machines.index') }}"
                class="block px-4 py-3 rounded-lg hover:bg-white/20">

                    ⚙ Manage Machines

                </a>

                <a href="{{ route('admin.users.index') }}"
                class="block px-4 py-3 rounded-lg hover:bg-white/20">
                    👥 Manage Users
                </a>


                <a href="#"
                   class="block px-4 py-3 rounded-lg hover:bg-white/20">
                    📊 Reports
                </a>

                <a href="{{ route('welcome') }}"
                class="block px-4 py-3 rounded-lg hover:bg-white/20">
                    🌐 View Website
                </a>



            @else


                <!-- USER MENU -->

                <a href="{{ route('dashboard') }}"
                   class="block px-4 py-3 rounded-lg hover:bg-white/20">
                    🏠 Dashboard
                </a>


                <a href="#"
                   class="block px-4 py-3 rounded-lg hover:bg-white/20">
                    🧺 Machines
                </a>


                <a href="#"
                   class="block px-4 py-3 rounded-lg hover:bg-white/20">
                    📋 My Laundry
                </a>

                <a href="{{ route('welcome') }}"
                class="block px-4 py-3 rounded-lg hover:bg-white/20">
                   🌐 Back to Home
                </a>


            @endif



            <a href="{{ route('profile.edit') }}"
               class="block px-4 py-3 rounded-lg hover:bg-white/20">
                👤 Profile
            </a>


        </nav>
    </aside>


    <!-- CONTENT -->


    <div class="flex-1">


        <!-- TOP BAR -->

<header class="bg-white shadow px-6 py-4 flex items-center gap-4"><button id="menu-btn"class="md:hidden text-3xl text-blue-700">☰</button>

    @isset($header)

        {{ $header }}

    @else

        <div class="flex justify-between items-center">

            <h2 class="text-xl font-bold text-gray-700">

                @if(auth()->user()->role === 'admin')

                    Admin Panel

                @else

                    Customer Panel

                @endif

            </h2>


            <div class="flex items-center gap-4">

                <span class="text-gray-600">
                    {{ auth()->user()->name }}
                </span>


                <form method="POST" action="{{ route('logout') }}">

                    @csrf

                    <button
                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">

                        Logout

                    </button>

                </form>

            </div>

        </div>

    @endisset

</header>



        <!-- PAGE CONTENT -->


        <main class="p-6">

            {{ $slot }}

        </main>


    </div>


</div>

     <!-- Chat Button -->
    <button id="chat-toggle"
            class="bg-blue-600 text-white rounded-full shadow-lg"
            style="
            position:fixed;
            bottom:30px;
            right:30px;
            width:60px;
            height:60px;
            font-size:25px;
            z-index:9999;">
        💬
    </button>


    <!-- Chat Window -->
    <div id="chat-window"
        class="bg-white shadow-lg rounded-lg"
        style="
        display:none;
        position:fixed;
        bottom:100px;
        right:30px;
        width:350px;
        height:450px;
        z-index:9999;">


        <div class="bg-blue-600 text-white p-3 rounded-t-lg">
            Hour Wash AI
        </div>


        <div id="chat-box"
            class="p-3"
            style="
            height:330px;
            overflow-y:auto;">
        </div>


        <div class="p-3 border-top">

            <div class="flex">

                <input id="message"
                    class="border rounded-l px-3 py-2 flex-1"
                    placeholder="Type message">

                <button onclick="sendMessage()"
                        class="bg-blue-600 text-white px-4 rounded-r">
                    Send
                </button>

            </div>

        </div>


    </div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const toggle = document.getElementById('chat-toggle');
    const chat = document.getElementById('chat-window');

    toggle.addEventListener('click', function () {
        chat.style.display =
            chat.style.display === 'none' ? 'block' : 'none';
    });

});

function sendMessage() {

    let input = document.getElementById('message');
    let message = input.value.trim();

    if (!message) return;

    let chatBox = document.getElementById('chat-box');


    // User message bubble
    chatBox.innerHTML += `
        <div class="flex justify-end mb-3">
            <div class="bg-blue-600 text-white px-4 py-2 rounded-2xl rounded-br-none max-w-xs shadow">
                ${message}
            </div>
        </div>
    `;


    input.value = "";


    fetch('/chatbot', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .content
        },
        body: JSON.stringify({
            message: message
        })
    })

    .then(res => res.json())

    .then(data => {


        // AI message bubble
        chatBox.innerHTML += `
            <div class="flex justify-start mb-3">
                <div class="bg-gray-200 text-gray-800 px-4 py-2 rounded-2xl rounded-bl-none max-w-xs shadow">
                    <b class="text-blue-600">HourWash AI</b><br>
                    ${data.reply}
                </div>
            </div>
        `;


        chatBox.scrollTop = chatBox.scrollHeight;

    })


    .catch(err => {

        chatBox.innerHTML += `
            <div class="flex justify-start mb-3">
                <div class="bg-red-100 text-red-600 px-4 py-2 rounded-2xl">
                    Error: ${err.message}
                </div>
            </div>
        `;

    });

}
</script>

<script>

const sidebar = document.getElementById('sidebar');
const menuBtn = document.getElementById('menu-btn');
const closeBtn = document.getElementById('close-btn');


menuBtn.addEventListener('click', function(){

    sidebar.classList.remove('-translate-x-full');

});


closeBtn.addEventListener('click', function(){

    sidebar.classList.add('-translate-x-full');

});

</script>

</body>

</html>