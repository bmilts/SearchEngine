<?php 

// Class group code together to use over again
class DomDocumentParser {
    
    // Remember and store doc
    private $doc;
    
    // Public constructor can be called outside class
    public function __construct($url) {
        
        $options = array(
            // Options for website retrieval method key points to value GET
            'http'=>array('method'=>"GET", 'header'=>"User-Agent: engineBot/0.1\n")
            );
            
        $context = stream_context_create($options);
        //  Perform actions on web pages
        $this->doc = new DomDocument();
        //  @ don't show errors or warnings
        @$this->doc->loadHTML(file_get_contents($url, false, $context));
    }
    
    public function getlinks() {
        //  Get a tag or links
        return $this->doc->getElementsByTagName("a");
    } 
    
}

?>