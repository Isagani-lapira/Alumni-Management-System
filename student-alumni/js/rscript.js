//FOR DROPDOWN FUNCTION
const prnt = document.getElementById('prnt');
const dropdownOptions = document.getElementById('dropdownOptions');

prnt.addEventListener('click', () => {
  dropdownOptions.classList.toggle('invisible');
});





//FOR GOING BACK FUNTION
function GoBack() {
  window.location.href = 'https://facebook.com/login';
}





//FOR PRINTING SPECIFIC AREA
function printSpecificArea() {
  var printableArea = document.getElementById("whole1");
  printableArea.classList.remove("body");
  window.print();
  printableArea.classList.add("printable-area");
}





//FOR DONWLOADING THE RESUME AS PDF FILE
function dlPDF() {
  const element = document.getElementById('whole'); 
    element.style.border = 'none'; 
    element.style.margin = '0'; 
    element.querySelector('#btns').style.display = 'none';

    element.style.height = 'auto'; // Set the height to auto to capture the entire content
    const options = {
      filename: 'Resume.pdf',
      image: { type: 'jpeg', quality: 0.98 },
      html2canvas: {
        scale: 2,
        scrollY: 0 // Add this line to start capturing from the top of the element
      },
      jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait', hotfixes: ['px_scaling'] }
    };

    html2pdf().set(options).from(element).save();
}