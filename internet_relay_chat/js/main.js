import * as chat from "./chat.js";

//Updating the chat
const chatContainer = document.getElementById("chat");
chat.main(chatContainer);

setInterval(chat.main, 3000, chatContainer);
