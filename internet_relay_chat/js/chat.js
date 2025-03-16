const urlRequestHandler = "handlers\\request_handler.php";

export function createChatElement() {
    const username = "NONE";
    const postingTime = "12:00";
    const content = `Lorem ipsum odor amet, consectetuer adipiscing elit. Enim nullam tellus lectus vehicula sed bibendum integer quisque. Etiam placerat nam; condimentum dapibus fames tellus. Natoque lectus consequat eget sodales purus nibh ligula et. Ante pellentesque maximus velit luctus augue montes pharetra. Feugiat mi dapibus lobortis aliquam malesuada. Quam sed convallis hac turpis euismod tortor. Placerat dictum mus turpis euismod; urna aptent metus. Nostra varius diam ultrices gravida gravida mollis odio consectetur nec. Diam turpis elit pharetra metus etiam parturient nisl accumsan.

Malesuada ex venenatis id tincidunt lectus leo conubia. Gravida conubia adipiscing congue nisi maximus semper lectus vestibulum? Etiam tortor cubilia vitae natoque arcu; taciti aliquet. Lorem nisi senectus sollicitudin facilisi, accumsan aliquet molestie arcu. Vitae blandit amet posuere sagittis vitae. Suspendisse neque suscipit sollicitudin facilisis potenti fermentum finibus laoreet tempor. Efficitur semper finibus consectetur pretium ante himenaeos rhoncus. Nisi vitae nam tincidunt dui mollis sociosqu sociosqu primis.

Praesent elit ligula purus auctor feugiat. Elit amet dapibus libero massa imperdiet id dictumst. Per ligula cubilia ex; volutpat ad fusce auctor. Tristique velit lacus dictum maecenas nisi inceptos suscipit; dapibus nunc. Quisque cras curabitur a aenean netus non. Sit quam a pulvinar sagittis molestie metus habitasse iaculis eleifend.

Mattis dapibus nullam mattis habitasse sodales quam. Tempor pellentesque sagittis at magnis sed hac, quisque nam. Vel metus cursus luctus adipiscing enim nisl eu, molestie sed. Rutrum himenaeos iaculis posuere non class tristique ut. Non convallis ipsum ornare litora malesuada; efficitur phasellus. Consectetur a mollis molestie duis habitasse penatibus turpis bibendum. Ultrices erat aliquet aenean molestie pharetra.

Facilisis facilisis justo urna aliquet mus cras. Vel scelerisque inceptos; hendrerit dui vehicula tempor. Nunc porta malesuada ut eu praesent sed. Vivamus lacinia fermentum aptent ac egestas quam dolor auctor dictum? Eget magna facilisi magna sollicitudin montes. Curae felis nisi auctor cras ligula. Proin lectus ligula ullamcorper penatibus inceptos eros adipiscing mus quis. Sodales laoreet vulputate suspendisse pretium dui lacinia a convallis. Curae sem eros tempus ligula inceptos malesuada.`;

    const chatElementString = `<div class='chat_element'>
        <div class='chat_element_author'><span class='chat_time_style'>@${postingTime} </span>
        <p>${username}</p></div>
        <div class='chat_element_content'>${content}</div>
    </div>`;

    let doc = new DOMParser().parseFromString(chatElementString, "text/html");
    return doc.body.firstChild;
}


/**
 * Sends a fetch request to the server, asking for the current message sign in the channel.
 * @returns A json object containing the data, or false if the request fails.
 */
export async function fetchMessageSign() {
    try {
        const response = await fetch(urlRequestHandler, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                command: "getSign" // This will tell the server which function to run
            })
        });
        if (!response.ok) {
            throw new Error("Failed to fetch message sign - response code wasn't 'OK'");
        }

        const responseText = await response.json();
        console.log(responseText);
    } catch (error) {
        console.log(error);
        return false;
    }
}
