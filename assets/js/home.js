// Activation hedaer when scrolling
window.addEventListener("scroll", function(){
  var header = document.querySelector("header");
  header.classList.toggle("sticky", window.scrollY > 0);
}
);

// cursor
document.body.onmousemove = function(event){
  let x, y;
  if (document.all)  {
      x = event.x + document.body.scrollLeft;
      y = event.y + document.body.scrollTop;
  } else {
      x = event.pageX;
      y = event.pageY;
  }
  document.getElementById("cursor").style.left = (x+2) + "px";
  document.getElementById("cursor").style.top = (y+2) + "px";
}

let counterLoad = 0;

if (loading == 1) {
  document.body.classList.add('screensaver');
  setInterval(() =>{
    if (counterLoad % 2 == 1) {
      clear();
      setTimeout(output, 2200, "BRAIN",);
    } else {
      clear();
      setTimeout(output, 2000, "STUDIO",);
    }
    counterLoad++;
  }, 5000);
  setTimeout( function(){
    document.getElementById('body-screensaver').remove();
    document.body.classList.remove('screensaver');
    setCookie('vizited', '', 100);
    loading = 0;
  }, 10000);

  setInterval(() =>{
    if (loading == 0) {
      return false;
    }
    document.getElementById('text_screensaver').classList.toggle('active');
  }, 300);
}


function clear() {
  if (loading == 0) {
    return false;
  }
  let str = document.getElementById('text_screensaver').innerHTML;
  let num;
  for (let i = str.length; i >= 0; i--) {
    str = document.getElementById('text_screensaver').innerHTML;
    setTimeout( function(){
      if (loading == 0) {
        return false;
      }
      num = str.length-i;
      document.getElementById('text_screensaver').innerHTML = str.substring(0, num);
    }, (i + 1) * 300);
  }
}

function output(text) {
  if (loading == 0) {
    return false;
  }
  let str = text;
  let num;
  for (let i = (str.length-1); i >= 0; i--) {
    setTimeout( function(){
      if (loading == 0) {
        return false;
      }
      document.getElementById('text_screensaver').innerHTML += str[i];
    }, (i + 1) * 300);
  }
}
