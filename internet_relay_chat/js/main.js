import * as chat from "./chat.js";

const addrLogin = document.getElementById("nav_login");
addrLogin.href = "login.php";

const chatMessageInput = document.getElementById("message_input");
const chatButtonSendMessage = document.getElementById("b_chat_send");
const chatInputPopup = document.getElementById("input_popup_container");

chatButtonSendMessage.addEventListener("click", () => {
    const message = chatMessageInput.value;
    if (!message) return;
    const response = chat.sendChatMessage(message);
    if (!response) return;

    response.then((data) => {
        if (data.ok) {
            chatMessageInput.value = "";
            chat.main(chatContainer);
            chat.updateChatInputText(chatInputPopup, false);
            return;
        }

        chat.updateChatInputText(chatInputPopup, true, data.message);       
    });
});


//Updating the chat
const chatContainer = document.getElementById("chat");
chat.main(chatContainer, chatInputPopup);

setInterval(chat.main, 2500, chatContainer);
