function redirectWithLanguage(language){
    //location.href = ".\\..\\index.php?lang=" + language;
    const workingDir = window.location.pathname;
    window.location.replace(`.\\..${workingDir}?lang=${language}`);
}

// Registering language button events
const buttonLanguage_CS = document.getElementById("b_lang_cs"); 
const buttonLanguage_EN = document.getElementById("b_lang_en");

buttonLanguage_CS.addEventListener("click", function(){
    redirectWithLanguage("cs");
});
buttonLanguage_EN.addEventListener("click", function(){
    redirectWithLanguage("en");
});


// Clock updating
const o_date = new Date();
const e_clock = document.getElementById("clock");

function updateClockField(){
    arguments[0].textContent = o_date.toLocaleDateString(undefined, {
        hour: "numeric",
        minute: "2-digit",
        hourCycle: "h24"
    });
}

updateClockField(e_clock);
setInterval(updateClockField, 1000, e_clock);