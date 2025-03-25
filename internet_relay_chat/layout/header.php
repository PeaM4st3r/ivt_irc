<?php
function setLanguageButtonSelected($testLanguage) {
    if ($_SESSION["lang"] == $testLanguage) {
        echo "class='b_sel'";
    }
}
?>

<header>
    <h1><?php echo MAIN_LABEL ?></h1>
    <p id='clock'>N/A</p>

    <div id="language_option_container">
        <button <?php setLanguageButtonSelected("cs")?> id="b_lang_cs">CZ</button>
        <button <?php setLanguageButtonSelected("en")?> id="b_lang_en">EN</button>
    </div>
</header>