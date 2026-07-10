<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hour Wash Laundry Shop</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('hourwash.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

<nav class="bg-white shadow p-5 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-blue-600">
        Hour Wash Laundry
    </h1>


    <div>
        @auth
            <a href="{{ route('dashboard') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded">
                Dashboard
            </a>
        @else
            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                <a href="{{ route('login') }}"
                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-violet-700 transition w-full sm:w-auto text-center">
                    LOG IN
                </a>

                <a href="{{ route('register') }}"
                class="bg-gray-300 text-gray-800 px-6 py-3 rounded-lg hover:bg-blue-400 transition w-full sm:w-auto text-center">
                    CREATE NEW ACOUNT
                </a>
            </div>
        @endauth
    </div>
</nav>


<section class="text-center py-16">

    <!-- Logo -->
    <div class="w-24 h-24 mx-auto bg-white rounded-full shadow-lg flex items-center justify-center overflow-hidden">
        <img
            src="{{ asset('hourwash.ico') }}"
            alt="Hour Wash Logo"
            class="w-30 h-30 object-contain">
    </div>

    <!-- Title -->
    <h2 class="mt-6 text-5xl font-bold text-gray-800">
        Hour Wash Laundry Monitoring System
    </h2>

    <!-- Subtitle -->
    <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
        Check washing machine availability and monitor your laundry status in real time.
    </p>

</section>


<section class="py-10">

    <div class="max-w-6xl mx-auto">

        <h2 class="text-3xl font-bold text-center mb-8">
            Our Laundry Machines
        </h2>

        <div class="grid md:grid-cols-3 gap-6">

            @forelse($machines as $machine)

                <div class="bg-white rounded-xl shadow-lg p-6">

                    <h3 class="text-xl font-bold">
                        {{ $machine->machine_name }}
                    </h3>

                    <p class="text-gray-500">
                        Code: {{ $machine->machine_code }}
                    </p>

                    <div class="mt-4">
                        @if($machine->status == 'Available')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                Available
                            </span>
                        @elseif($machine->status == 'In Use')
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full">
                                In Use
                            </span>
                        @else
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">
                                Maintenance
                            </span>
                        @endif
                    </div>

                </div>

            @empty

                <p class="text-center col-span-3 text-gray-500">
                    No machines available.
                </p>

            @endforelse

        </div>

    </div>

</section>

<section class="max-w-6xl mx-auto mt-12 px-6">

    <div class="bg-white p-8 rounded shadow">

        <h2 class="text-2xl font-bold">
            Our Services
        </h2>

        <ul class="mt-4 space-y-2">
            <li>✓ Self-service washing</li>
            <li>✓ Drying service</li>
            <li>✓ Fold and pickup service</li>
            <li>✓ Machine availability monitoring</li>
        </ul>

    </div>

</section>


<footer class="text-center mt-12 p-5 text-gray-500">
    © {{ date('Y') }} Hour Wash Laundry
</footer>

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
</body>
</html>