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
        chatMessageInput.value = "";

        if (data.ok) {
            chat.main(chatContainer);
            chat.updateChatInputText(chatInputPopup, false);
            chat.setExternalError(false);
            return;
        }

        chat.updateChatInputText(chatInputPopup, true, data.message);
        chat.setExternalError(true);
    });
});


//Updating the chat
const chatContainer = document.getElementById("chat");
chat.main(chatContainer, chatInputPopup);
chat.setExternalError(false);

setInterval(chat.main, 3000, chatContainer);
