const BORDER = 20;
    let l_border = 0;
    let privacyBtn = false;
    let popular = false;
        let newBool = false;
    let privacy = false;
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
      if (!privacy) {
        output();
      }
     scrollTop()
    }

    function lazyScroll() {
      var header = document.getElementById('form-test');
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
        if (!privacy) {
          output();
        }
      }
    }

    let boolFilter = false;

    output();
    function output(){
    if (!boolFriend && !boolFilter && !boolSearch) {
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
      data.append('subject', document.getElementById('subject').value);
      data.append('popular', popular);
      if (privacyBtn) {
        privacyBtn=false;
        data.append('privacy', document.getElementById('code').value);
      }
      request.open('POST','updatetests');
      request.addEventListener('readystatechange', function() {
      if ((request.readyState==4) && (request.status==200)) {
        if (boolSearch){
          loadMoreBlock.innerHTML = request.responseText;
        } else{
          if (request.responseText == "<div class='no-test'><h3>На сайте пока нет открытых тестов :( Стань первым: </h3><a href='constructor'>Создать тест</a></div>") {
            if (!privacy && !boolFilter && (loadMoreBlock.innerHTML.trim() == "" || loadMoreBlock.innerHTML.trim() == '<div class="loading-icon"><h4>Загрузка...</h4></div>')) {
              boolFriend = false;
              loadMoreBlock.insertAdjacentHTML('beforeend', request.responseText);
            } else{
              if (boolFilter && !popular && !newBool) {
                boolFriend = false;
                popular = false;
                loadMoreBlock.insertAdjacentHTML('beforeend', "<div class='empty'><h4>Не найдено ни одной записи</h4><div>");
              }
            }
          } else{
            boolFriend = true;
            if (privacy) {
              loadMoreBlock.innerHTML = '';
            }
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



    function openSetting(){
      if (privacy) {
        clearBtns();
        document.getElementById('btn1').classList.add('active');
        popular = false;
        l_border = 0;
        if (boolFriend) {
          loadMoreBlock.innerHTML = "";
        }
        output();
      }
      document.getElementById('search-setting').classList.toggle('active');
      document.getElementById('header-setting').classList.toggle('active');
      document.getElementById('body-setting').classList.toggle('active');
      document.getElementById('clearFilter').classList.toggle('active');
    }

    function clearFilter() {
      document.getElementById('subject').value = 'all';
      openSetting();
      l_border = 0;
      loadMoreBlock.innerHTML = "";
      output();
      boolFilter = false;
    }

    document.getElementById('subject').onchange = function(){
       boolFilter = true;
        l_border = 0;
        loadMoreBlock.innerHTML = "";
        output();
    }




    document.getElementById('btn1').onclick = function(){
      clearBtns();
      this.classList.add('active');
      popular = false;
      newBool = true;
      l_border = 0;
      if (boolFriend) {
        loadMoreBlock.innerHTML = "";
      }
      output();
    }

    document.getElementById('btn2').onclick = function(){
      clearBtns();
      this.classList.add('active');
      popular = true;
      boolFilter = true;
      newBool = false;
      if (boolFriend) {
        loadMoreBlock.innerHTML = "";
      }
      l_border = 0;
      output();

    }
    document.getElementById('btn3').onclick = function(){
      clearBtns();
      this.classList.add('active');
      popular = false;
      privacy = true;
      newBool = false;
      l_border = 0;
      document.getElementById('code-block').classList.add('active');
      if (boolFriend) {
        loadMoreBlock.innerHTML = "";
      }
      document.getElementById('search-setting').classList.remove('active');
      document.getElementById('header-setting').classList.remove('active');
      document.getElementById('body-setting').classList.remove('active');
      document.getElementById('clearFilter').classList.remove('active');
    }

    function clearBtns() {
      privacy = false;
      document.getElementById('code-block').classList.remove('active');
      document.getElementById('btn1').classList.remove('active');
      document.getElementById('btn2').classList.remove('active');
      document.getElementById('btn3').classList.remove('active');
    }

    document.getElementById('codeBtn').onclick = function(){
      l_border = 0;
      privacyBtn = true;
      if (boolFriend) {
        loadMoreBlock.innerHTML = "<div class='empty'><h4>Не найдено ни одной записи</h4><div>";
      }
      output();
      document.getElementById('code').value = '';
    }

    function createFavorite(id) {
      var request = createRequest();
      var data = new FormData();
      data.append('id', id);
      request.open('POST','updatefavorite');
      request.addEventListener('readystatechange', function() {
        if ((request.readyState==4) && (request.status==200)) {
          if (request.responseText == "success-del" || request.responseText == "success-ins") {
            if (request.responseText == "success-del") {
              document.getElementById('star' + id).classList.remove('fas');
              document.getElementById('star' + id).classList.add('far');
            } else {
              document.getElementById('star' + id).classList.remove('far');
              document.getElementById('star' + id).classList.add('fas');
            }
          } else{
            notification("Ошибка!", 'error');
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
