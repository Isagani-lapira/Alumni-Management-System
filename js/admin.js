const classStyle = "bg-accent text-white font-bold";
const defaultStyle = "text-black";

$(document).ready(()=>{
  $(".dashboard").addClass(classStyle);
  $(".dashboard svg").css("fill","#f6f6f6");
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


    //change icon color active/not
    $(".sidebar div svg").css("fill","#474645"); //set as default color

    //make the selected color as active button
    let icon = $(this).find('svg').attr("id");
    let iconVar = document.getElementById(icon);
    iconVar.style.fill = "#f6f6f6"

    let sideBar = $(this).find('span').html();
    visibleContent(sideBar);
  })
})

const visibleContent = (item)=>{
  
  const mainDiv = document.querySelectorAll(".content");

  mainDiv.forEach(child =>{
    child.style.display = "none";
  })

  let sideBarID;
  switch(item){
    case 'DASHBOARD':
      console.log("somethings wrong");
      break;
    case 'ANNOUNCEMENT':
      sideBarID = 'announcement-content';
      break;
  }

  //display the tab content
  document.getElementById(sideBarID).classList.toggle("hidden");
  document.getElementById(sideBarID).style.display = "block";
}