// MARK: Init
const urlRequestHandler = "handlers\\request_handler.php";
const USERNAME_INPUT_STATES = Object.freeze({
    USR_EXISTS: 1,
    USR_NEW: 2,
    EMPTY: 3,
    SERVER_ERROR: 4
});
const LANGUAGE_CS = Object.freeze({
    login: "Přihlásit se",
    create: "Vytvořit účet"
});
const LANGUAGE_EN = Object.freeze({
    login: "Log in",
    create: "Create account"
});

var websiteLanguage = document.documentElement.lang;


/**
 * Used for testing if a specific username exists on the server.
 * @param {string} var_username The username to search for.
 * @returns true when the username string exists on the server, otherwise false.
 */
async function testIfUsernameExists(var_username) {
    const body = {
        command: "findUser",
        username: var_username
    }

    try {
        const request = await fetch(urlRequestHandler, {
            method: "POST",
            headers: {
                "Content-Type": "application/json; charset=utf-8"
            },
            body: JSON.stringify(body)
        });
        if (!request.ok) throw new Error("Failed to test username existence - HTTP wasn't 'OK'");

        return await request.json();
    } catch (error) {
        console.log("Couldn't test if username exists: " + error);
        return false;
    }
}

/**
 * Changes the state of the submit button according to the provided parameters.
 * @param {boolean} disabled Changes the disabled state of the button. If omitted, sets it to enabled.
 * @param {string} label Sets the button's label. If omitted, leaves it as is.
 * @returns when the label is not set.
 */
function updateSubmitButton(disabled = false, label = "") {
    in_SubmitButton.disabled = disabled;

    if(label == "") return;
    in_SubmitButton.innerHTML = label;
}

/**
 * Creates/updates a paragraph with the specified ID.
 * @param {string} id The id of the specific message paragraph element.
 * @param {string} message The message to put into the paragraph.
 * @returns a new ChildNode element if the paragraph doesn't exist yet, or an HTMLElement of
 * the paragraph.
 */
function setInputResolveMessage(id, message) {
    let messageElement = document.getElementById(id);
    if (messageElement) {
        messageElement.innerHTML = message;
        return document.getElementById(id);
    }

    const inputResolveElementString = `<p id='${id}'>${message}</p>`;

    let doc = new DOMParser().parseFromString(inputResolveElementString, "text/html");
    return doc.body.firstChild;
}


/**
 * Updates the resolve element based on the message provided. If the message is empty, removes the resolve element.
 * @param {ChildNode} appendNode The node to append the message to (this should be an <input> element).
 * @param {string} message The message to show in the new paragraph element.
 * @returns 
 */
function updateResolveElement(appendNode, message) {
    if (message == "") {
        const paragraphNode = document.getElementById(appendNode.id + "_info");

        if (!paragraphNode) return;
        paragraphNode.remove();
        return;
    }

    const appendParent = appendNode.parentNode;
    const paragraphNode = setInputResolveMessage(appendNode.id + "_info", message);
    appendParent.insertBefore(paragraphNode, appendNode.nextSibling);
}



// MARK: Runtime
const in_SubmitButton = document.getElementById("in_submit");
const in_Username = document.getElementById("in_username");
const in_Password = document.getElementById("in_password");

updateSubmitButton(true, (websiteLanguage == "en" ? LANGUAGE_EN : LANGUAGE_CS).login);

function updateFormFields(var_inputState) {
    switch (var_inputState) {
        case USERNAME_INPUT_STATES.EMPTY:
            updateSubmitButton(true);
            updateResolveElement(in_Username, "Prosíme, vyplňte toto pole");
            break;
        case USERNAME_INPUT_STATES.SERVER_ERROR:
            updateSubmitButton(true);
            updateResolveElement(in_Username, "Chyba serveru (opakujte akci)");
            break;
        case USERNAME_INPUT_STATES.USR_EXISTS: // User already exists (log in)
            updateSubmitButton(false, (websiteLanguage == "en" ? LANGUAGE_EN : LANGUAGE_CS).login);
            updateResolveElement(in_Username, "");
            break;
        case USERNAME_INPUT_STATES.USR_NEW: // New user (create account)
            updateSubmitButton(false, (websiteLanguage == "en" ? LANGUAGE_EN : LANGUAGE_CS).create);
            updateResolveElement(in_Username, "Vytvoření nového účtu");
            break;
        
        default:
            break;
    }
}

in_Username.addEventListener("change", (event) => {
    // Fetching the username
    let inputState = USERNAME_INPUT_STATES.EMPTY;
    const inputUsername = event.target.value;
    if(!inputUsername){
        updateFormFields(inputState);
        return
    }

    const response = testIfUsernameExists(inputUsername);
    if(!response) inputState = USERNAME_INPUT_STATES.SERVER_ERROR;

    response.then((data) => {
        console.log(data);
        inputState = (data == true ? USERNAME_INPUT_STATES.USR_EXISTS : USERNAME_INPUT_STATES.USR_NEW);

        updateFormFields(inputState);
    });
});
