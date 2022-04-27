let counter = 1;

let prev = document.getElementById('prev');
let next = document.getElementById('next');

let blocks = document.querySelectorAll('.slider > .slider-item');
let len = blocks.length;

let slider = document.getElementById('slider');

let boolArrow = false;

let boolSlider = true;

let count = slider.offsetWidth;



let timeScroll;
function scroll() {
	if(count > 0) {
		if (boolArrow) {
			 slider.scrollBy(-20, 0);
		} else {
			 slider.scrollBy(20, 0);
		}
		count -= 20;
		timeScroll = setTimeout('scroll()',5);
	} else{
		boolArrow  = false;
		boolSlider = true;
		count = slider.offsetWidth;
		 clearTimeout(timeScroll);
	 }
	return false;
}

next.addEventListener("click", nextImg);
prev.addEventListener("click", backImg);

function nextImg() {
	if (counter == len) {
		return ;
	}
	if (!boolSlider) {
		return ;
	}
	counter++;
	updatedCheck();
	boolSlider = false;
	for (let j = 0; j < btns.length; j++) {
		btns[j].classList.remove('active')
	}
	btns[counter - 1].classList.add("active");
	scroll();
}

function backImg() {
	if (counter == 1) {
		return ;
	}
	if (!boolSlider) {
		return ;
	}
	counter--;
	updatedCheck();
	boolArrow = true;
	boolSlider = false;
	for (let j = 0; j < btns.length; j++) {
		btns[j].classList.remove('active')
	}
	btns[counter - 1].classList.add("active");
	scroll();
}

document.body.onkeydown = function  (event) {
  if(event.keyCode == 39){
      nextImg();
  }
  if(event.keyCode == 37){
      backImg();
  }
}

function updatedCheck() {
	if (counter == len) {
		next.disabled = true;
	} else {
		next.disabled = false;
	}
	if (counter == 1) {
		prev.disabled = true;
	} else {
		prev.disabled = false;
	}
}
let btns = [];
let btnsItem = document.getElementById('btns-item');
for (var i = 0; i < blocks.length; i++) {
	let btn = document.createElement("button");
	btn.innerHTML = (i+1);
	btn.id = "btn" + (i+1);
	if (i == 0) {
		btn.classList.add('active');
	}
	btnsItem.append(btn);
		btns[i] = document.getElementById("btn" + (i+1));
}

for (let i = 0; i < btns.length; i++) {
	btns[i].onclick = function(){
		if (!boolSlider) {
			return ;
		}
		for (let j = 0; j < btns.length; j++) {
			btns[j].classList.remove('active')
		}
		btns[i].classList.add("active");
		let num = (i+1) - counter;
		counter = (i+1);
		updatedCheck();
		let scrollNum
		if ((num * 1000) < 0) {
			boolArrow = true;
			scrollNum = -(num * 1000)
		} else {
			scrollNum = num * 1000;
		}
		count = scrollNum;
		boolSlider = false;
		scroll();
	}
}
