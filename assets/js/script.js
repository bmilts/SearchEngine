$(document).ready(function(){
   
    // Click handler to log clicks
   $(".result").on("click", function(){
      
        // Access id and url to increase clicks
        var id = $(this).attr("data-linkId");
        var url = $(this).attr("href");
        
        if(!id){
            alert("data-linkId attribute not found");
        }
        
        increaseLinkClicks(id, url);
        
        return false;
   });
   
});

function increaseLinkClicks(linkId, url){
    
    $.post("ajax/updateLinkCount.php", {linkId: linkId})
    .done(function(result){
            
        if(result != ""){
            alert(result);
            return;
        }
        
        window.location.href = url;
    });
    
}