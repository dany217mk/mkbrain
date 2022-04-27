const BORDER = 21;
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
      data.append('gender', document.querySelector('input[name="gender"]:checked').value);
      data.append('organization', document.getElementById('organization').checked);
      data.append('role', document.querySelector('input[name="role"]:checked').value);
      data.append('sort', document.getElementById('sort').value);
      data.append('vk', document.getElementById('vk').checked);
      data.append('my_organization', my_organization);
      data.append('max_gender', max_gedner);
      data.append('max_role', max_role);
      request.open('POST','updatesearchfriend');
      request.addEventListener('readystatechange', function() {
      if ((request.readyState==4) && (request.status==200)) {
        if (boolSearch){
          loadMoreBlock.innerHTML = request.responseText;
        } else{
          if (request.responseText.trim() == "<div class='no-friend'><h3>Видимо все пользователи, которые есть на сайте у вас в друзьях :) </h3><a href='im'>Сообщения</a></div>") {
            if (!boolFilter && (loadMoreBlock.innerHTML.trim() == "" || loadMoreBlock.innerHTML.trim() == '<div class="loading-icon"><h4>Загрузка...</h4></div>')) {
              boolFriend = false;
              loadMoreBlock.insertAdjacentHTML('beforeend', request.responseText);
            } else {
              if (boolFilter) {
                boolFriend = false;
                loadMoreBlock.insertAdjacentHTML('beforeend', "<div class='empty'><h4>Не найдено ни одной записи</h4><div>");
              }
            }
          } else{
            boolFriend = true;
            if (request.responseText.trim() == "<div class='empty'><h4>Не найдено ни одной записи</h4><div>") {
              if (loadMoreBlock.innerHTML.trim() == "" || loadMoreBlock.innerHTML.trim() == '<div class="loading-icon"><h4>Загрузка...</h4></div>') {
                loadMoreBlock.insertAdjacentHTML('beforeend', request.responseText);
              }
            } else {
                loadMoreBlock.insertAdjacentHTML('beforeend', request.responseText);
            }
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

   function addFriend(id){
     var request = createRequest();
     var data = new FormData();
     data.append('id', id);
     request.open('POST','friendadding');
     request.addEventListener('readystatechange', function() {
       if ((request.readyState==4) && (request.status==200)) {
         notification(request.responseText);
         if (request.responseText.trim() == "success") {
           notification("Заявка отправлена", 'success');
           document.getElementById('user' + id).remove();
           if (loadMoreBlock.innerHTML.trim() == ""){
             loadMoreBlock.innerHTML = "<div class='no-friend'><h3>Видимо все пользователи, которые есть на сайте у вас в друзьях :) </h3><a href='im'>Сообщения</a></div>";
             boolFriend = false;
           }
         } else{
           notification("Ошибка отправки заявки!", 'error');
         }
       }
     });
    request.send(data);
   }


   function openSetting(){
     document.getElementById('search-setting').classList.toggle('active');
     document.getElementById('header-setting').classList.toggle('active');
     document.getElementById('body-setting').classList.toggle('active');
     document.getElementById('clearFilter').classList.toggle('active');
   }

   let roles = document.getElementsByName('role');
   for (var role of roles) {
     role.onchange = function(){
       boolFilter = true;
       loadMoreBlock.innerHTML = "";
       l_border = 0;
       output();
     }
   }

   let genders = document.getElementsByName('gender');
   for (var gender of genders) {
     gender.onchange = function(){
       boolFilter = true;
       loadMoreBlock.innerHTML = "";
         l_border = 0;
       output();
     }
   }

   document.getElementById('organization').onchange = function(){
     if (my_organization == "") {
       notification("Вы не состоите ни в одной организации");
       this.checked = false;
       return false;
     }
     boolFilter = true;
     l_border = 0;
     loadMoreBlock.innerHTML = "";
     output();
   }

   document.getElementById('vk').onchange = function(){
     boolFilter = true;
     l_border = 0;
     loadMoreBlock.innerHTML = "";
     output();
   }

   document.getElementById('sort').onchange = function(){
      boolFilter = true;
       l_border = 0;
       loadMoreBlock.innerHTML = "";
       output();
   }

   function clearFilter() {
     document.getElementById('all').checked = true;
     document.getElementById('any').checked = true;
     document.getElementById('vk').checked = false;
     document.getElementById('organization').checked = false;
     document.getElementById('sort').value = 'sort';
     openSetting();
     l_border = 0;
     loadMoreBlock.innerHTML = "";
     output();
     boolFilter = false;
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
