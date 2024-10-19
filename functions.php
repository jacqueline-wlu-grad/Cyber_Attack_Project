<?php

function xss($data){
    switch ($_SESSION["security"]) {
        case "1":
            $data = no_check($data);
            break;
        case "2":
            $data = xss_check($data);
            break;
        default:
            $data = no_check($data);
            break;
    }
    return $data;
}

function no_check($data){
    return $data;
}

function xss_check($data, $encoding = "UTF-8"){
    // htmlspecialchars - converts special characters to HTML entities
    // '&' (ampersand) becomes '&amp;'
    // '"' (double quote) becomes '&quot;' when ENT_NOQUOTES is not set
    // "'" (single quote) becomes '&#039;' (or &apos;) only when ENT_QUOTES is set
    // '<' (less than) becomes '&lt;'
    // '>' (greater than) becomes '&gt;'

    return htmlspecialchars($data, ENT_QUOTES, $encoding);
}
