const BORDER = 50;
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
				request.open('POST','updaterequests');
				request.addEventListener('readystatechange', function() {
				if ((request.readyState==4) && (request.status==200)) {
          if (boolSearch){
            loadMoreBlock.innerHTML = request.responseText;
          } else{
            if (request.responseText.trim() == "<div class='no-friend'><h3>У вас пока-что нет заявок :( </h3><a href='friend-search'>Найти друзей</a></div>") {
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
				  })
				 request.send(data);
			l_border += BORDER;
		 }

     function acceptRequest(id){
       var request = createRequest();
       var data = new FormData();
       data.append('id', id);
       request.open('POST','friendaccepting');
       request.addEventListener('readystatechange', function() {
         if ((request.readyState==4) && (request.status==200)) {
           if (request.responseText.trim() == "success") {
             notification("Заявка принята", 'success');
             document.getElementById('friend' + id).remove();
             if (document.getElementById('num') != '0') {
               document.getElementById('num').innerHTML = (parseInt(document.getElementById('num').innerHTML) - 1);
             }
             if (loadMoreBlock.innerHTML.trim() == ""){
               loadMoreBlock.innerHTML = "<div class='no-friend'><h3>У вас пока-что нет заявок :( </h3><a href='friend-search'>Найти друзей</a></div>";
               boolFriend = false;
             }
           } else{
             notification("Ошибка принятия заявки!", 'error');
           }
         }
       });
      request.send(data);
     }

     function removeRequest(id){
       var request = createRequest();
       var data = new FormData();
       data.append('id', id);
       request.open('POST','friendremove');
       request.addEventListener('readystatechange', function() {
         if ((request.readyState==4) && (request.status==200)) {
           notification(request.responseText);
           if (request.responseText.trim() == "success") {
             notification("Заявка удалена", 'success');
             document.getElementById('friend' + id).remove();
             if (document.getElementById('num') != '0') {
               document.getElementById('num').innerHTML = (parseInt(document.getElementById('num').innerHTML) - 1);
             }
             if (loadMoreBlock.innerHTML.trim() == ""){
               loadMoreBlock.innerHTML = "<div class='no-friend'><h3>У вас пока-что нет заявок :( </h3><a href='friend-search'>Найти друзей</a></div>";
               boolFriend = false;
             }
           } else{
             notification("Ошибка удаления заявки!", 'error');
           }
         }
       });
      request.send(data);
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
