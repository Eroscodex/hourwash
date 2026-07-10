<!DOCTYPE html>
<html>
<head>
    <title>AI Chatbot</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            AI Chatbot
        </div>

        <div class="card-body">

            <div id="chat-box" style="height:400px; overflow-y:auto;"></div>

            <div class="input-group mt-3">

                <input 
                    id="message"
                    class="form-control"
                    placeholder="Type your message..."
                >

                <button 
                    id="sendBtn"
                    class="btn btn-primary">
                    Send
                </button>

            </div>

        </div>

    </div>

</div>

<script>

document.getElementById('sendBtn')
    .addEventListener('click', sendMessage);


function sendMessage(){

    let input = document.getElementById('message');
    let message = input.value.trim();

    if(message === ''){
        return;
    }


    let chatBox = document.getElementById('chat-box');


    // Show user message
    chatBox.innerHTML += `
        <div class="mb-2">
            <b>You:</b> ${message}
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


    .then(response => {

        if(!response.ok){
            throw new Error("Server error: " + response.status);
        }

        return response.json();

    })


    .then(data => {

        chatBox.innerHTML += `
            <div class="text-primary mb-2">
                <b>AI:</b> ${data.reply}
            </div>
        `;


        chatBox.scrollTop = chatBox.scrollHeight;

    })


    .catch(error => {

        console.error(error);

        chatBox.innerHTML += `
            <div class="text-danger mb-2">
                <b>Error:</b> ${error.message}
            </div>
        `;

    });

}

</script>

</body>
</html>