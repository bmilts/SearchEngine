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
    $page = isset($_GET["page"]) ? $_GET["page"] : 1;
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Beetle!</title>
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
                            <input class="searchBox" type="text" name="term" value="<?php echo $term; ?>"/>
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
            $pageSize = 20;
            
            $numResults = $resultsProvider->getNumResults($term);
            
            echo "<p class='resultsCount'>$numResults results found</p>";
            
            echo $resultsProvider->getResultsHtml($page, $pageSize, $term);
            
            ?>
            
        </div>
        
        <div class="paginationContainer">
            
            <div class="pageButtons">
            
                <div class="pageNumberContainer">
                    <img src="assets/images/pageStart.png">
                </div>
                
                <?php 
                
                $pagesToShow = 10;
                //  ceil round up
                $numPages = ceil($numResults / $pageSize);
                //  min function takes smaller of two variables
                $pagesLeft = min($pagesToShow, $numPages);
                // Work out current page, floor takes lower value oppositte of ceil
                $currentPage = $page - floor($pagesToShow / 2); 
                
                // 
                if($currentPage < 1) {
                    $currentPage = 1;
                }
                
                while($pagesLeft != 0){
                    
                    // Current page not clickable
                    if($currentPage == $page){
                         echo "<div class='pageNumberContainer'>
                                    <img src='assets/images/pageSelected.png'>
                                    <span class='pageNumber'>$currentPage</span>
                               </div>";
                    } else {
                         echo "<div class='pageNumberContainer'>
                                <a href='search.php?term=$term&type=$type&page=$currentPage'>
                                    <img src='assets/images/page.png'>
                                    <span class='pageNumber'>$currentPage</span>
                                </a>
                               </div>";
                    }
                    
                   
                    $currentPage++;
                    $pagesLeft--;
                }
                    
                
                ?>
                
                <div class="pageNumberContainer end">
                    <img src="assets/images/pageEnd.png">
                </div>
            
            </div>
            
        </div>
        
    </div>
</body>
</html>