//FOR HIDING AND SHOWING THE OTHER INPUT FIELD
function show() {
  var oth = document.getElementById("other");
  var otxt = document.getElementById("otxt");

  if(oth.checked) {
    otxt.classList.remove('hidden');
  }

  else {
    otxt.classList.add('hidden');
  }

}


 //  const aype = document.getElementById('aype');
  //  const dropdown = document.getElementById('dropdownempStatus');

  // aype.classList.remove('hidden');
  //    dropdown.classList.remove('hidden');


 let click = 0;

 const nxt = document.getElementById('nxt');
 const cdNsub = document.getElementById('cdNsub');
 const btndiv = document.getElementById('btndiv');

  const first = document.getElementById('first');
   const second = document.getElementById('second');
   const third = document.getElementById('third');
   const awb = document.getElementById('awb');
   const fourth = document.getElementById('fourth');

   const c2 = document.getElementById('c2');
   const c3 = document.getElementById('c3');
   const c4 = document.getElementById('c4');

   const l1 = document.getElementById('l1');
   const l2 = document.getElementById('l2');
   const l3 = document.getElementById('l3');

   const pEB = document.getElementById('pEB');
   const pED = document.getElementById('pED');
   const pCon = document.getElementById('pCon');


   nxt.addEventListener('click', function() {
   click++;


   
if (click === 1) {
  first.classList.add('hidden');
  second.classList.remove('hidden');
  cdNsub.textContent = 'Previous';
  c2.style.backgroundColor = '#991B1B';
  l1.style.backgroundColor = '#991B1B';
  pEB.style.color = '#991B1B';
  }
  
  else if (click === 2) {
  second.classList.add('hidden');
  third.classList.remove('hidden');
  awb.classList.remove('hidden');
  nxt.textContent = 'Submit';
  cdNsub.textContent = 'Previous';
  c3.style.backgroundColor = '#991B1B';
  l2.style.backgroundColor = '#991B1B';
  pED.style.color = '#991B1B';
  }
  
  else if (click === 3) {
  third.classList.add('hidden');
  awb.classList.add('hidden');
  fourth.classList.remove('hidden');
  btndiv.classList.add('hidden');
  c4.style.backgroundColor = '#991B1B';
  l3.style.backgroundColor = '#991B1B';
  pCon.style.color = '#991B1B';
  }

});






cdNsub.addEventListener('click', function() {
  const buttonText = cdNsub.textContent;

    if(buttonText == 'Previous') {
      click--;

      if (click === 0) {
        first.classList.remove('hidden');
        second.classList.add('hidden');
        cdNsub.textContent = 'Change Details';
        c2.style.backgroundColor = '#959595';
        l1.style.backgroundColor = '#C4C4C4';
        pEB.style.color = '#959595';
      }

      else if (click === 1) {
        second.classList.remove('hidden');
        third.classList.add('hidden');
        nxt.textContent = 'Next';
        c3.style.backgroundColor = '#959595';
        l2.style.backgroundColor = '#C4C4C4';
        pED.style.color = '#959595';
      }
        
  }

  else if (buttonText == 'Change Details') {
    window.location.href = 'https://www.example.com';
  }

  
});









//FOR DROPDOWN FUNCTION
const anjBtn = document.getElementById('anjBtn');
const dropdownOptoptionsions = document.getElementById('options');

anjBtn.addEventListener('click', () => {
  options.classList.toggle('hidden');
});






var withempButton = document.getElementById('withemp');
var selfempButton = document.getElementById('selfemp');

withempButton.addEventListener('click', function() {
  var wemp = document.getElementById('wemp');

  var clonedDiv = wemp.cloneNode(true);

  clonedDiv.classList.remove('hidden');

  var wcon = document.getElementById('wcon');
  wcon.appendChild(clonedDiv);
});

selfempButton.addEventListener('click', function() {
  var semp = document.getElementById('semp');

  var clonedDiv = semp.cloneNode(true);

  clonedDiv.classList.remove('hidden');

  var wcon = document.getElementById('wcon');
  wcon.appendChild(clonedDiv);
});

const dropdownempStatus = document.getElementById('dropdownempStatus');

dropdownempStatus.addEventListener('change', function() {
  const wemp = document.getElementById('wemp');
  const semp = document.getElementById('semp');
  const nemp = document.getElementById('nemp');
  const awb = document.getElementById('awb');

  const selectedValue = dropdownempStatus.value;

  wemp.style.display = "none";
  semp.style.display = "none";
  nemp.style.display = "none";

  if (selectedValue === 'emp') {
    wemp.style.display = "block";
    awb.classList.remove('hidden');
  } else if (selectedValue === 'self') {
    semp.style.display = "block";
    awb.classList.remove('hidden');
  } else if (selectedValue === 'no') {
    nemp.style.display = "block";
    awb.classList.add('hidden');
  }
});
