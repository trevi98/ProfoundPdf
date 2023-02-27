// alert("dd")

const{jsPdf} = import ("jspdf");
// async function myFunction() {
//     // const { someFunction } = await import('./some-module.js');
//     // Use someFunction here
//     someFunction();
//   }
// Default export is a4 paper, portrait, using millimeters for units
const doc = new jsPDF();

doc.text("Hello world!", 10, 10);
doc.save("a4.pdf");
