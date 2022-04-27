/*
  __MKStuido__
*/
let header = document.getElementById('header');
let main = document.getElementById('main');
let closeSearch = document.getElementById('closeSearch');

let sidebar = document.querySelector(".sidebar");
let closeBtn = document.querySelector("#btn");
let searchBtn = document.querySelector(".fa-search");

if (getCookie('menu') == 'active'){
    sidebar.classList.add("open");
    sidebar.style.transition="none";
    header.style.transition="none";
    main.style.transition="none";
    menuBtnChange();
    setTimeout(function(){
         sidebar.style.transition="all 0.5s ease";
         header.style.transition="all 0.5s ease";
         main.style.transition="all 0.5s ease";
    }, 100);
}

 if (typeof boolHomePage == 'undefined' || !boolHomePage) {
     let closeBtn = document.querySelector("#btn");
    closeBtn.addEventListener("click", ()=>{
      sidebar.classList.toggle("open");
      menuBtnChange();
    });
 }



function menuBtnChange() {
  document.getElementById('header').classList.toggle('menu');
 if(sidebar.classList.contains("open")){
   closeBtn.classList.replace("fa-bars", "fa-align-right");
   setCookie('menu', 'active');
 }else {
   closeBtn.classList.replace("fa-align-right","fa-bars");
   setCookie('menu', '');
 }
}

function createRequest() {
    var request = false;
    if (window.XMLHttpRequest){
      request = new XMLHttpRequest();
    } else if(window.ActiveXObject){
      try{
        request = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (CatchException){
        request = new ActiveXObject("Msxml2.XMLHTTP");
      }
    }
    if (!request){
      notification("Ошибка сервера!", 'error');
    }
      return request;
}

function openAccountMenu() {
  document.getElementById('account-menu').classList.toggle('active');
}
let html = document.querySelector('html');
html.addEventListener('click', function(event) {
 let target = event.target;
 let boolAccount = false;
 try {
   while (target.tagName != 'HTML'){
     if (target.id == 'account-menu' || target.id == 'account'){
       boolAccount = true;
       break;
     }
     target = target.parentNode;
   }
 } catch (e) {}
 if (!boolAccount && typeof boolHomePage == 'undefined'){
   document.getElementById('account-menu').classList.remove('active');
 }
});
