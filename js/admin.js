const classStyle = "bg-accent text-white font-bold";
const defaultStyle = "text-black";

$(document).ready(()=>{
  $(".dashboard").addClass(classStyle);
})

// hove effect in side bar
$(document).ready(function() {
    const additionalClass = "font-extrabold text-accent text-10xs";
    $('.sidebar span').hover(function() {
      $(this).addClass(additionalClass);
    }, function() {
      $('.sidebar span').removeClass(additionalClass);
    });
});

// change view
$(document).ready(()=>{
  $('.sidebar div').click(function(){
    
    //change the menu to active
    $(".sidebar div").removeClass(classStyle).addClass(defaultStyle);
    $(this).addClass(classStyle)

    //change icon color
    let icon = $(this).find('svg').attr("id");
    let iconVar = document.getElementById(icon);
    iconVar.style.fill = "#f6f6f6"
  })
})