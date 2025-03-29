export const urlRequestHandler = "handlers\\request_handler.php";

/**
 * A generic fetch API wrapper for sending JSON requests to the server.
 * @param {Object} body A body object to send to the server. Prior to sending, this will be stringified into JSON.
 * @param {string} url The url to fetch data from.
 * @returns {Promise<Any>|false} a promise resolving into a JSON object on success, or false if the fetch fails.
 */
export async function fetchPostJSON(body, url) {
    try {
        const request = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json; charset=utf-8"
            },
            body: JSON.stringify(body)
        });
        if (!request.ok) throw new Error("Fetch failed - HTTP code wasn't 'OK'");

        return await request.json();
    } catch (error) {
        console.log("Fetch failed: " + error);
        return false;
    }
}