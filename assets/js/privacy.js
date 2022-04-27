let masBtn = [];
let masBlock = [];
for (let i = 0; i < 2; i++) {
 masBtn[i] = 'btn' + (i + 1) ;
 masBlock[i] = 'block' + (i + 1);
}
for (let i = 0; i < 2; i++) {
 document.getElementById(masBtn[i]).onclick = function(){
   for (let j = 0; j < 2; j++) {
     document.getElementById(masBtn[j]).classList.remove('active');
     document.getElementById(masBlock[j]).classList.remove('active');
   }
   document.getElementById(masBtn[i]).classList.add('active');
   document.getElementById(masBlock[i]).classList.add('active');
 }
}
