<?php

include("classes/DomDocumentParser.php");

//  Converte relative link to absolute link
function createLink($src, $url){
    
    // Vars
    
    // if = http
    $scheme = parse_url($url)["scheme"];
    // if for example www.google.com + /about.php
    $host = parse_url($url)["host"];
    
    // Check state of link based on first few chars, append website if needed
    // //about
    if(substr($src, 0, 2) == "//"){
        $src = $scheme . ":" . $src;
    } 
    // /about/
    else if(substr($src, 0, 1) == "/") {
        $src = $scheme . "://" . $host . $src;
    } 
    //  ./about/ append directory name and ignore .
    else if(substr($src, 0, 2) == "./"){
        $src = $scheme . "://" . $host . dirname(parse_url($url)["path"]) . substr($src, 1);
    }
    //  ../about/ cancel out with / before
    else if(substr($src, 0, 3) == "../"){
        $src = $scheme . "://" . $host . "/" . $src;
    }
    // Check start of string is not http and https
    else if(substr($src, 0, 5) != "https" && substr($src, 0, 4) != "http"){
        $src = $scheme . "://" . $host . "/" . $src;
    }
    
    // Return updated url
    return $src;
}

function followLinks($url) {
    
    $parser = new DomDocumentParser($url);
    
    $linkList = $parser->getLinks();
    
    // function for displaying links
    foreach($linkList as $link){
        $href = $link->getAttribute("href");
        
        // Ignore pound sign
        if(strpos($href, "#") !== false){
            continue;
        }
        else if(substr($href, 0, 11) == "javascript:"){
            continue;
        }
        
        $href = createLink($href, $url);
        
        echo $href . "<br>";
    }
    
}

$startUrl = "https://www.blockchain.com/btc/address/1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2";
followLinks($startUrl);

?>