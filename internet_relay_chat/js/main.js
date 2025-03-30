import * as chat from "./chat.js";

const addrLogin = document.getElementById("nav_login");
addrLogin.href = "login.php";

//Updating the chat
const chatContainer = document.getElementById("chat");
chat.main(chatContainer);

setInterval(chat.main, 3000, chatContainer);
