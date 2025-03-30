// MARK: Init
import * as comm from "./communication.js";

const addrLogin = document.getElementById("nav_login");
addrLogin.href = "index.php";

const USERNAME_INPUT_STATES = Object.freeze({
    USR_EXISTS: 1,
    USR_NEW: 2,
    EMPTY: 3,
    SERVER_ERROR: 4
});
const LANGUAGE_CS = Object.freeze({
    login: "Přihlásit se",
    create: "Vytvořit účet",

    response_user_exists: "Přihlašování existujícího uživatele",
    response_user_new: "Vytváření nového uživatele",
    response_server_error: "Chyba komunikace se serverem, opakujte akci",
    response_empty_field: "Toto pole nesmí být prázdné",

    create_user_success: "Nový uživatel byl úspěšně vytvořen"
});
const LANGUAGE_EN = Object.freeze({
    login: "Log in",
    create: "Create account",

    response_user_exists: "Logging in as existing user",
    response_user_new: "Creating a new user account",
    response_server_error: "Server communication failed, please try again",
    response_empty_field: "This field mustn't be empty",

    create_user_success: "New user account successfully created"
});

let websiteLanguage = document.documentElement.lang;
let loginState = USERNAME_INPUT_STATES.EMPTY;
const LANG_ENUM = (websiteLanguage == "en" ? LANGUAGE_EN : LANGUAGE_CS);


// MARK: Function definitions
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

    return comm.fetchPostJSON(body, comm.urlRequestHandler);
}

/**
 * Sends a request to the server to log in with the provided credentials.
 * @param {string} var_username The username of the account to log in as.
 * @param {string} var_password The password for the account.
 */
function logIn(var_username, var_password) {
    const body = {
        command: "logIn",
        username: var_username,
        password: var_password
    };

    const response = comm.fetchPostJSON(body, comm.urlRequestHandler);
    response.then((data) => {
        if (!data.ok) {
            updateResolveElement(in_SubmitButton, data.message);
            return;
        }

        location.reload();
    });
}

/**
 * Sends a request to the server to create a new account with the provided credentials.
 * @param {string} var_username The username of the new account.
 * @param {string} var_password The password for the new account.
 */
function createUserAccount(var_username, var_password) {
    const body = {
        command: "createAccount",
        username: var_username,
        password: var_password
    };

    const response = comm.fetchPostJSON(body, comm.urlRequestHandler);
    response.then((data) => {
        resetFormState();

        if (!data.ok) {
            updateResolveElement(in_SubmitButton, data.message);
            return;
        }
        updateResolveElement(in_SubmitButton, data.message);
    });
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

/**
 * Resets all of the form fields and additional messages to their default state.
 */
function resetFormState(resetSubmit = false) {
    if (resetSubmit) {
        updateResolveElement(in_SubmitButton, "")
        return;
    }

    in_Username.value = "";
    in_Password.value = "";

    updateResolveElement(in_Username, "");
    updateResolveElement(in_Password, "");
}



// MARK: Runtime
const in_SubmitButton = document.getElementById("in_submit");
const in_Username = document.getElementById("in_username");
const in_Password = document.getElementById("in_password");

updateSubmitButton(true, LANG_ENUM.login);

function updateFormFields(var_inputState) {
    switch (var_inputState) {
        case USERNAME_INPUT_STATES.EMPTY:
            updateSubmitButton(true);
            updateResolveElement(in_Username, LANG_ENUM.response_empty_field);
            break;
        case USERNAME_INPUT_STATES.SERVER_ERROR:
            updateSubmitButton(true);
            updateResolveElement(in_Username, LANG_ENUM.response_server_error);
            break;
        case USERNAME_INPUT_STATES.USR_EXISTS: // User already exists (log in)
            updateSubmitButton(false, LANG_ENUM.login);
            updateResolveElement(in_Username, LANG_ENUM.response_user_exists);
            break;
        case USERNAME_INPUT_STATES.USR_NEW: // New user (create account)
            updateSubmitButton(false, LANG_ENUM.create);
            updateResolveElement(in_Username, LANG_ENUM.response_user_new);
            break;
        
        default:
            updateSubmitButton(true);
            updateResolveElement(in_Username, "Congratulations, you somehow broke this");
            break;
    }
}


// Events
in_Username.addEventListener("focusout", (event) => {
    loginState = USERNAME_INPUT_STATES.EMPTY;
    const inputUsername = event.target.value;
    if(!inputUsername){
        updateFormFields(loginState);
        return
    }

    const response = testIfUsernameExists(inputUsername);
    if(!response) loginState = USERNAME_INPUT_STATES.SERVER_ERROR;

    response.then((data) => {
        loginState = (data == true ? USERNAME_INPUT_STATES.USR_EXISTS : USERNAME_INPUT_STATES.USR_NEW);

        updateFormFields(loginState);
        resetFormState(true);
    });
});

in_Password.addEventListener("focusout", (event) => {
    const inputPassword = event.target.value;
    if (!inputPassword) {
        updateResolveElement(in_Password, LANG_ENUM.response_empty_field);
        updateSubmitButton(true);
        return;
    }

    updateResolveElement(in_Password, "");
    updateSubmitButton(false);
});

in_SubmitButton.addEventListener("click", () => {
    if (loginState != USERNAME_INPUT_STATES.USR_NEW) return;

    createUserAccount(in_Username.value, in_Password.value);
});
in_SubmitButton.addEventListener("click", () => {
    if (loginState != USERNAME_INPUT_STATES.USR_EXISTS) return;

    logIn(in_Username.value, in_Password.value);
});