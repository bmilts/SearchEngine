<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Beetle!</title>
    
    <meta charset="UTF-8">
    <meta name="description" content="Search the web for sites images, gifs, memes etc">
    <meta name="keywords" content="HTML,CSS,PHP,JavaScript">
    <meta name="author" content="Brendan Milton">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="assets/css/style.css" type="text/css" />
</head>    
<body>
    <div class="wrapper indexPage">
      <div class="mainSection">
            <div class="logoContainer">
                <img src="assets/images/beagle.png" title="Site Logo"></img>
            </div>
            
            <div class="searchContainer">
                
                <form action="search.php" method="GET">
                    
                    <input class="searchBox" type="text" name="term"/>
                    <input class="searchButton" type="submit" value="Search"/>
                
                </form>
                
                <form action="crawl.php" method="GET">
                    
                    <input class="crawlBox" type="text" name="crawl"/>
                    <input class="crawlButton" type="submit" value="Start Crawling"/>
                    <!-- Change below when uploading to heroku -->
                    <button class="cancelButton" type="cancel" onclick="window.location='https://searchengine-bmilts.c9users.io/crawl.php';return false;">Stop Crawling</button>
                    <input class="deleteDB" type="submit" value="Clear Database"/>
                    <!-- https://stackoverflow.com/questions/28925812/button-to-clear-mysql-table-of-data -->

                </form>
            </div>
        </div>
    </div>
</body>
</html>