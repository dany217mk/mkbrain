const BORDER = 20;
let l_border = 0;
 let loadMoreBlock = document.getElementById('list');

      let search = document.getElementById('search');

      let boolFriend = true;

      setCookie('search', search.value, {expires: -600, path: '/'});

		  window.addEventListener("scroll", lazyScroll);

      let boolSearch = false;

      search.oninput = function(){
        if (search.value.trim() != "") {
          setCookie('search', search.value, {expires: 600, path: '/'});
          boolSearch = true;
        } else{
          setCookie('search', search.value, {expires: -600, path: '/'});
          boolSearch = false;
          l_border = 0;
          if (boolFriend) {
            loadMoreBlock.innerHTML = "";
          }
        }
        output();
        scrollTop();
      }

		  function lazyScroll() {
        var header = document.getElementById('form-friend');
        header.classList.toggle("sticky", window.scrollY > 0);
  			if (!loadMoreBlock.classList.contains('loading')){
  			  loadMore();
  			}
		  }

		  const windowHeight = document.documentElement.clientHeight;

		  function loadMore() {
  			const loadMoreBlockPos = loadMoreBlock.getBoundingClientRect().top + pageYOffset;
  			const loadBlockMoreHeight = loadMoreBlock.offsetHeight;

  			if (pageYOffset > (loadMoreBlockPos + loadBlockMoreHeight) - windowHeight) {
          if (boolSearch && search.value.trim() != ""){
            return false;
          }
  			  output();
  			}
		  }

      output();
		function output(){
      if (!boolFriend) {
        return false;
      }
			if (l_border >= total){
				l_border=total;
			}
			if (l_border < total && !document.querySelector('loading-icon')) {
				  loadMoreBlock.insertAdjacentHTML('beforeend', `<div class="loading-icon"><h4>Загрузка...</h4></div>`);
				}
			loadMoreBlock.classList.add('loading');
				var request = createRequest();
				var data = new FormData();
				data.append('border', l_border);
				request.open('POST','updategroups');
				request.addEventListener('readystatechange', function() {
 if ((request.readyState==4) && (request.status==200)) {
          if (boolSearch){
            loadMoreBlock.innerHTML = request.responseText;
          } else{
            if (request.responseText.trim() == "<div class='no-group'><h3>Вы пока-что не состоите ни в одном классе :( </h3><a href='group-search'>Найти класс</a></div>") {
              if (loadMoreBlock.innerHTML.trim() == "" || loadMoreBlock.innerHTML.trim() == '<div class="loading-icon"><h4>Загрузка...</h4></div>') {
                boolFriend = false;
                loadMoreBlock.insertAdjacentHTML('beforeend', request.responseText);
              }
            } else{
              boolFriend = true;
              loadMoreBlock.insertAdjacentHTML('beforeend', request.responseText);
            }
          }
					  loadMoreBlock.classList.remove('loading');
					   if (document.querySelector('.loading-icon')) {
							document.querySelector('.loading-icon').remove();
						}
					}
				  });
				 request.send(data);
			l_border += BORDER;
		 }


     let timeScroll;
    function scrollTop() {
    	let topBord = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
    	if(topBord > 0) {
    		window.scrollBy(0,-100);
    		timeScroll = setTimeout('scrollTop()',20);
    	} else clearTimeout(timeScroll);
    	return false;
    }
