const messagesDiv = document.getElementById("messages");
const inputMsg = document.getElementById("msgInput");
const sendBtn = document.getElementById("sendBtn");
const logoutBtn = document.getElementById('logout');

const evtSource = new EventSource("chat_sse.php");

evtSource.addEventListener("message", (e) => {
    const data = JSON.parse(e.data);
    const div = document.createElement("div");
    div.textContent = data.text;
    messagesDiv.appendChild(div);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
});

evtSource.addEventListener("users", (e) => {
    const data = JSON.parse(e.data);
    let users = data.users;
    let usersList = document.getElementById('users_list');
    for(let i = 0; i< usersList.children.length; i++ ){
        let style = 'user_list_active';
        let item = usersList.children[i]
        item.classList.remove(style);
        for (let mail in users) {
            if(item.dataset.user === mail) {
                item.classList.add(style);
            }
        }
    }
});

function sendMessage() {
    const text = inputMsg.value.trim();
    if (text === "") return;

    const formData = new FormData();
    formData.append("msg", text);

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

logoutBtn.addEventListener('click', e => {
    if (evtSource !== null) {
        evtSource.close();
    }
})