// MARK: Init
import * as comm from "./communication.js";

const addrLogin = document.getElementById("nav_login");
addrLogin.href = "index.php";

// MARK: Function definitions
function logOut() {
    const body = {
        command: "logOut"
    };

    const response = comm.fetchPostJSON(body, comm.urlRequestHandler);
    if(!response) {
        return;
    }
    response.then(() => {
        location.reload();
    });
}


// MARK: Runtime
const in_LogoutButton = document.getElementById("in_logout");
in_LogoutButton.addEventListener("click", () => logOut());