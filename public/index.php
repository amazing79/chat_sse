<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Redis + SSE</title>
    <style>
        body { font-family: Arial, sans-serif; }
        #messages { border: 1px solid #ccc; padding: 10px; height: 250px; overflow-y: auto; margin-bottom: 10px; }
        #userInput, #msgInput { padding: 5px; margin-right: 5px; }
        #sendBtn { padding: 5px 10px; }
    </style>
</head>
<body>
<h1>Chat Redis + SSE</h1>

<div>
    <label>Nombre: </label>
    <input id="userInput" type="text" placeholder="Tu nombre">
</div>

<div id="messages"></div>

<div>
    <input id="msgInput" type="text" placeholder="Escribe un mensaje">
    <button id="sendBtn">Enviar</button>
</div>

<script>
    const messagesDiv = document.getElementById("messages");
    const inputMsg = document.getElementById("msgInput");
    const inputUser = document.getElementById("userInput");
    const sendBtn = document.getElementById("sendBtn");

    // SSE para recibir mensajes en tiempo real
    const evtSource = new EventSource("chat_sse.php");
    evtSource.addEventListener("message", (e) => {
        const data = JSON.parse(e.data);
        const div = document.createElement("div");
        div.textContent = data.text;
        messagesDiv.appendChild(div);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    });

    // Enviar mensaje vía AJAX
    function sendMessage() {
        const text = inputMsg.value.trim();
        const user = inputUser.value.trim() || "Anónimo";
        if (text === "") return;

        const formData = new FormData();
        formData.append("msg", text);
        formData.append("user", user);

        fetch("send.php", { method: "POST", body: formData })
            .then(res => res.text())
            .then(res => console.log("Enviado:", res))
            .catch(err => console.error(err));

        inputMsg.value = "";
    }

    sendBtn.onclick = sendMessage;
    inputMsg.addEventListener("keyup", e => {
        if (e.key === "Enter") sendMessage();
    });
</script>
</body>
</html>