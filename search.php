<?php
include("config.php");
include("classes/SiteResultsProvider.php");

    // Only take term value if it exists
    if(isset($_GET['term'])) {
        $term = $_GET['term'];
    } 
    else {
        exit("Please enter a search term");
    }
    
    // if(isset($_GET['type'])) {
    //     $type = $_GET['type'];
    // } 
    // else {
    //     $type = "sites";
    // } 
    
    $type = isset($_GET["type"]) ? $_GET["type"] : "sites";
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Beagle!</title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css" />
</head>    
<body>
    <div class="wrapper">
        <div class="header">
            <div class="headerContent">
                <div class="logoContainer">
                    <a href="index.php">
                        <img src="assets/images/beagle.png">
                    </a>
                </div>
                
                <div class="searchContainer">
                    <form action="search.php" method="GET">
                        <div class="searchBarContainer">
                            <input class="searchBox" type="text" name="term"/>
                            <button class="searchButton">
                                <img src="assets/images/icons/searchIcon.png"></img>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="tabsContainer">
                <ul class="tabList">
                    <li class="<?php echo $type == 'sites' ? 'active' : '' ?>">
                        <a href='<?php echo "search.php?term=$term&type=sites"; ?>'>
                            Sites
                        </a>
                   </li> 
                    <li class="<?php echo $type == 'images' ? 'active' : '' ?>">
                        <a href='<?php echo "search.php?term=$term&type=images"; ?>'>
                            Images
                        </a>
                    </li>
                </ul>
            </div>
            
        </div>
        
        <div class="mainResultsSection">
            
            <?php 
            
            $resultsProvider = new SiteResultsProvider($con);
            
            $numResults = $resultsProvider->getNumResults($term);
            
            echo "<p class='resultsCount'>$numResults results found</p>";
            
            echo $resultsProvider->getResultsHtml(1, 20, $term);
            
            ?>
            
        </div>
        
    </div>
</body>
</html>