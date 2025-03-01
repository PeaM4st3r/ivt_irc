export function getSendLanguage(language){
    //location.href = ".\\..\\index.php?lang=" + language;
    const workingDir = window.location.pathname;
    window.location.replace(`.\\..${workingDir}?lang=${language}`);
}