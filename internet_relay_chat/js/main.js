import * as chat from "./chat.js";

const addrLogin = document.getElementById("nav_login");
addrLogin.href = "login.php";

const chatMessageInput = document.getElementById("message_input");
const chatButtonSendMessage = document.getElementById("b_chat_send");

chatButtonSendMessage.addEventListener("click", () => {
    const message = chatMessageInput.value;
    if (!message) return;
    const response = chat.sendChatMessage(message);
    if (!response) return;

    response.then((data) => {
        if (data.ok) {
            chatMessageInput.value = "";
            chat.main(chatContainer);
            return;
        }
    });
});


//Updating the chat
const chatContainer = document.getElementById("chat");
chat.main(chatContainer);

setInterval(chat.main, 2500, chatContainer);
