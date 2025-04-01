import * as comm from "./communication.js";
var localChatSignature = 0;


// MARK: Server communication
/**
 * Sends a fetch request to the server, asking for the current signature in the channel.
 * @returns {Promise<any>|false} A Promise from response.json() or false if the request fails.
 */
export async function fetchMessageSign() {
    const body = {
        command: "getSign",
        channel: "institut"
    }

    return comm.fetchPostJSON(body, comm.urlRequestHandler);
}

/**
 * Sends a fetch request to the server, asking for messages in the current channel.
 * @returns {Promise<any>|false} A promise resolving into a JSON object, or false if the fetch fails.
 */
export async function fetchChatMessages() {
    const body = {
        command: "getChat",
        channel: "institut",
        offset: 0
    }

    return comm.fetchPostJSON(body, comm.urlRequestHandler);
}

/**
 * Sends a new message to the server. Note that the user who's sending the message is handled server-side.
 * @param {string} varMessage The message to be sent.
 * @returns {Promise<any>|false}
 */
export async function sendChatMessage(varMessage) {
    if (!varMessage) return false;

    const body = {
        command: "sendMessage",
        channel: "institut",
        message: varMessage
    }

    return comm.fetchPostJSON(body, comm.urlRequestHandler);
}


// MARK: Chat rendering
/**
 * Creates (and returns) a new div structured as a chat message.
 * @param {string} username Sets the username of the person that sent the message.
 * @param {string} sentTime Sets the time of the user sending the message.
 * @param {string} msgText The content of the message.
 * @returns A div of class `chat_element` to be appended to the chat container element.
 */
export function createChatElement(username, sentTime, msgText) {
    const chatElementString = `<div class='chat_element'>
        <div class='chat_element_author'><p>${username}</p>
        <span class='chat_time_style'>@${sentTime}</span></div>
        <div class='chat_element_content'>${msgText}</div>
    </div>`;

    let doc = new DOMParser().parseFromString(chatElementString, "text/html");
    return doc.body.firstChild;
}


/**
 * Calls fetchChatMessages() and, upon success, loads the result into the chat container.
 * @param {HTMLElement} chatContainer The container of the chat messages.
 * @returns If the chat messages fetch fails.
 */
function fetchChatMessagesWrapper(chatContainer) {
    const chatMessagesPromise = fetchChatMessages();
    if(!chatMessagesPromise) return;
    
    chatMessagesPromise.then((messages) => {
        chatContainer.innerHTML = "";
        const chatMessageFormat = {
            month: "2-digit",
            day: "2-digit",
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit"
        };

        messages.forEach(message => {
            const msgDatetime = new Date(message["time_sent"]);
            const msgDateFormatted = msgDatetime.toLocaleString("en-GB", chatMessageFormat);
            chatContainer.appendChild(createChatElement(message["username"], msgDateFormatted, message["msg_text"]));
        });
    });
}


/**
 * The main chat update function. This should run in an interval.
 */
export function main() {
    const msgSignaturePromise = fetchMessageSign();
    const chatContainer = arguments[0];
    if (!msgSignaturePromise) return;

    msgSignaturePromise.then((signature) => {
        if (localChatSignature == signature) return;
        localChatSignature = signature;
        console.log("Updating chat - new signature: " + signature);

        fetchChatMessagesWrapper(chatContainer);
    });
}
