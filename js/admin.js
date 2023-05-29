$(document).ready(function(){
  $("#tabs").tabs();


  //change the tab appearance when active and not
  $(".tabs li").click(function(){
    $(".tabs li").removeClass("ui-tabs-active")
    $(this).addClass("ui-tabs-active")
    
  })
});
