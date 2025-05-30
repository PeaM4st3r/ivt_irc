import * as comm from "./communication.js";
let localChatSignature = "none";
let externalError = false;
let lang = document.documentElement.lang;


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

        if (!messages.length) {
            const sysDate = new Date();
            const dateString = sysDate.toLocaleString("en-GB", chatMessageFormat);
            chatContainer.appendChild(createChatElement("system", dateString, (lang == "cs" ?
                "Zatím žádné zprávy... napiště nějakou!" : "No messages so far... write one!")));
            return;
        }

        messages.forEach(message => {
            const msgDatetime = new Date(message["time_sent"]);
            const msgDateFormatted = msgDatetime.toLocaleString("en-GB", chatMessageFormat);
            chatContainer.appendChild(createChatElement(message["username"], msgDateFormatted, message["msg_text"]));
        });
    });
}


/**
 * Updates the chat input status text for notifying the user of errors during chatting.
 * @param {HTMLElement} container The input status container.
 * @param {boolean} show Shows or hides the status element.
 * @param {string} message The message to show when the element is shown.
 * @returns void
 */
export function updateChatInputText(container, show = false, message = "N/A") {
    if (!container) return;
    const childSpan = container.children[0];

    if (!show) {
        container.setAttribute("class", "login_popup hidden");
        return;
    }

    container.setAttribute("class", "login_popup");
    childSpan.innerHTML = message;
}

/**
 * Used for setting external error flags from different modules.
 * @param {boolean} bool
 */
export function setExternalError(bool = false) {
    externalError = bool;
}


/**
 * The main chat update function. This should run in an interval.
 */
export function main() {
    const msgSignaturePromise = fetchMessageSign();
    const chatContainer = arguments[0];
    const chatInputStatus = arguments[1];
    if (!msgSignaturePromise) return;

    msgSignaturePromise.then((response) => {
        // Testing response
        if (!response.ok & response.data == null) {
            updateChatInputText(chatInputStatus, true, response.message);
            return;
        }
        let signature = response.data;

        if (!externalError) {
            updateChatInputText(chatInputStatus, false);
        }

        // Updating chat signature & content
        if (localChatSignature == signature) return;
        localChatSignature = signature;
        console.log("Updating chat - new signature: " + signature);

        fetchChatMessagesWrapper(chatContainer);
    });
}
