<?php
include("index.php");
include("config.php");
include("classes/DomDocumentParser.php");

// Global Arrays
$alreadyCrawled = array();
$crawling = array();
$alreadyFoundImages = array();

//  Check for duplicate link
function linkExists($url){
    
    // Connect to database
    global $con;
    
    $query = $con->prepare("SELECT * FROM sites WHERE url = :url"); // Placeholders
                            
    // Bind place holders to value
    $query->bindParam(":url", $url);
    $query->execute();
    
    return $query->rowCount() != 0;
}


// Insert link into database
function insertLink($url, $title, $description, $keywords){
    
    // Connect to database
    global $con;
    
    $query = $con->prepare("INSERT INTO sites(url, title, description, keywords)
                            VALUES(:url, :title, :description, :keywords)"); // Placeholders
                            
    // Bind place holders to value
    $query->bindParam(":url", $url);
    $query->bindParam(":title", $title);
    $query->bindParam(":description", $description);
    $query->bindParam(":keywords", $keywords);
    
    //  Check if it worked or not
    return $query->execute();
    // print_r($query->errorInfo());
    // exit();
}

// Insert image into database
function insertImage($url, $src, $alt, $title){
    
    // Connect to database
    global $con;
    
    $query = $con->prepare("INSERT INTO images(siteUrl, imageUrl, alt, title)
                            VALUES(:siteUrl, :imageUrl, :alt, :title)"); // Placeholders
                            
    // Bind place holders to value
    $query->bindParam(":siteUrl", $url);
    $query->bindParam(":imageUrl", $src);
    $query->bindParam(":alt", $alt);
    $query->bindParam(":title", $title);
    
    // 
    return $query->execute();
    // print_r($query->errorInfo());
    // exit();
}

//  Converte relative link to absolute link
function createLink($src, $url){
    
    // Vars
    
    // if = http
    $scheme = parse_url($url)["scheme"];
    // if for example www.google.com + /about.php
    $host = parse_url($url)["host"];
    
    // Check state of link based on first few chars, append website if needed
    // USE STATE CHECK FOR CRYPTO
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

// Get details of crawled sites for main display
function getDetails($url) {
    
    global $alreadyFoundImages;
    
    $parser = new DomDocumentParser($url);
    
    $titleArray = $parser->getTitleTags();
    
    // if(sizeof($titleArray == 0 || $titleArray->item(0) == NULL)){
    //     return;
    // }
    
    $title = $titleArray->item(0)->nodeValue;
    
    //  Delete new lines
    $title = str_replace("\n", "", $title);
    
    //  Ignore links with no titles
    if($title == ""){
        return;
    }
    
    // Crawl description and keywords
    $description = "";
    $keywords = "";
    
    $metasArray = $parser->getMetatags();
    
    foreach($metasArray as $meta){
        
        // 
        if($meta->getAttribute("name") == "description"){
            $description = $meta->getAttribute("content");
        }
        
         // 
        if($meta->getAttribute("name") == "keywords"){
            $keywords = $meta->getAttribute("content");
        }
    }
    
    //  Delete new lines
    $description = str_replace("\n", "", $title);
    $keywords = str_replace("\n", "", $title);
    
    if(linkExists($url)){
        echo "$url already exists<br>";
    }
    else if(insertLink($url, $title, $description, $keywords)){
        echo "SUCCESS: $url<br>";
    }
    else {
        echo "ERROR: Falied to insert $url<br>";
    }
    
    $imageArray = $parser->getImages();
    foreach($imageArray as $image){
        $src = $image->getAttribute("src");
        $alt = $image->getAttribute("alt");
        $title = $image->getAttribute("title");
        
        if(!$title && !$alt){
            continue;
        }
        
        // Convert link information to absolute
        $src = createLink($src, $url);
        
        //  Check for image duplicates
        if(!in_array($src, $alreadyFoundImages)){
            $alreadyFoundImages[] = $src;
            
            // Insert image into database
            insertImage($url, $src, $alt, $title);
        }
    }
    
    // echo "URL: $url, Description: $description, KEYWORD: $keywords<br>";
}

//  Access links and ignore accordingly
function followLinks($url) {
    
    // reference arrays
    global $alreadyCrawled;
    global $crawling;
    
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
        
        if(!in_array($href, $alreadyCrawled)){
            // [] next item in array = $href
            $alreadyCrawled[] = $href;
            $crawling[] =$href;
            
            //  Insert array
            getDetails($href);
        } 
        // COMMENT IN PRODUCTION WILL ALLOW ALL LINKS ETC
        // else return;
    }
    
    // Remove from array
    array_shift($crawling);
    
    foreach($crawling as $site){
        followLinks($site);
    }
    
}

$startUrl = $_GET['crawl'];
followLinks($startUrl);

?>