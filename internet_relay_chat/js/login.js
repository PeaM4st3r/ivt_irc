// Init
const urlRequestHandler = "handlers\\request_handler.php";
const USERNAME_INPUT_STATES = Object.freeze({
    USR_EXISTS: 1,
    USR_NEW: 2,
    EMPTY: 3,
    SERVER_ERROR: 4
});
const LANGUAGE_CS = Object.freeze({

});
const LANGUAGE_EN = Object.freeze({

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


// Runtime
const in_SubmitButton = document.getElementById("in_submit");
const in_Username = document.getElementById("in_username");


function updateFormFields(var_inputState) {
    switch (var_inputState) {
        case USERNAME_INPUT_STATES.EMPTY:
            updateSubmitButton(true);
            break;
        case USERNAME_INPUT_STATES.SERVER_ERROR:
            updateSubmitButton(true);
            break;
        case USERNAME_INPUT_STATES.USR_EXISTS:
            updateSubmitButton(false, (websiteLanguage == "en" ? "Login" : "Přihlásit"));
            break;
        case USERNAME_INPUT_STATES.USR_NEW:
            updateSubmitButton(false, (websiteLanguage == "en" ? "Create account" : "Vytvořit účet"));
            break;
        
        default:
            break;
    }
}

in_Username.addEventListener("change", (event) => {
    // Fetching the username
    var inputState = USERNAME_INPUT_STATES.EMPTY;
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
