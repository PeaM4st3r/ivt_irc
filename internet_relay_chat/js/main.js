import * as chat from "./chat.js";
import * as lang from "./lang.js";

// Registering DOM events
const buttonLanguage_CS = document.getElementById("b_lang_cs"); 
const buttonLanguage_EN = document.getElementById("b_lang_en");

buttonLanguage_CS.addEventListener("click", function(){
    lang.getSendLanguage("cs");
});
buttonLanguage_EN.addEventListener("click", function(){
    lang.getSendLanguage("en");
});


// Clock updating
const o_date = new Date();

function updateClockField(){
    const e_clock = document.getElementById("clock");
    e_clock.textContent = o_date.toLocaleDateString(undefined, {hour: "numeric", minute: "2-digit", hourCycle: "h24"});
}

updateClockField();
setInterval(updateClockField, 1000);

//Updating the chat
const buttonChat_SendMsg = document.getElementById("b_chat_send");
buttonChat_SendMsg.addEventListener("click", ()=>{ // debug
    console.log(chat.fetchMessageSign());
});

const chatContainer = document.getElementById("chat");
chatContainer.appendChild(chat.createChatElement());