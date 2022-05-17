let blocks = document.getElementsByClassName('block');
      let btns = document.getElementsByClassName('btn');

      let index = 0;

      for (var i = 0; i < blocks.length; i++) {
        btns[i].onclick = function(){
          index = this.id[3];
          clearBlocks();
          blocks[index-1].classList.add('active');
          this.classList.add('active');
        }
      }

      function clearBlocks() {
        for (var i = 0; i < blocks.length; i++) {
          blocks[i].classList.remove('active');
        }
        for (var i = 0; i < btns.length; i++) {
          btns[i].classList.remove('active');
        }
      }

      document.getElementById('private').onchange = function(){
        var request = createRequest();
         request.open('POST','update_privacy',true);
         var data = new FormData();
         data.append('private', document.getElementById('private').checked);
         request.addEventListener('readystatechange', function() {
         if ((request.readyState==4) && (request.status==200)) {
           if (request.responseText.trim() == "Данные успешно отправлены") {
             notification(request.responseText, 'success');
           } else {
             notification(request.responseText, 'error');
           }
         }
         });
         request.send(data);
      }

      let boolCheckImg = false;

      let inputs = document.querySelectorAll('.input_file');
      Array.prototype.forEach.call(inputs, function (input) {
        let label = input.nextElementSibling,
          labelVal = label.querySelector('.input_file-button-text').innerText;

        input.addEventListener('change', function (e) {
          let countFiles = '';
          if (this.files && this.files.length >= 1)
            countFiles = this.files.length;

          if (countFiles){
            label.querySelector('.input_file-button-text').innerText = 'Выбрано файлов: ' + countFiles;
            document.getElementById('inp_file_block').classList.add('choosen');
          }
          else{
            label.querySelector('.input_file-button-text').innerText = labelVal;
            document.getElementById('inp_file_block').classList.remove('choosen');
          }

          let counterCheckImg = 0;
          files = this.files;
          if (files.length != 1){
            document.getElementById("mistakeImg").innerHTML = "Выберете изображение";
  					return;
          }else {
          	document.getElementById("mistakeImg").innerHTML = "";
            counterCheckImg++;
          }
  				for (let i = 0; i < files.length; i++){
            filesSizeTotal = files[i].size;
  					if (!checkTypeFile(files[i].type)) {
  						document.getElementById("mistakeImg").innerHTML = "Расширение изображения не соответствует формату";
  						return;
  					} else {
  						document.getElementById("mistakeImg").innerHTML = "";
              counterCheckImg++;
  					}
  				if (filesSizeTotal > 5000000) {
  					document.getElementById("mistakeImg").innerHTML = "Размер изобоажения не соответствует формату";
  					return;
  				} else {
  					document.getElementById("mistakeImg").innerHTML = "";
            counterCheckImg++;
  				}
        }
        if (counterCheckImg == 3) {
          boolCheckImg = true;
        } else {
          boolCheckImg = false;
        }
        });
      });

      function checkTypeFile(ext) {
				let allowed = ['image/gif', 'image/png', 'image/jpeg', 'image/jpg'];
        if (allowed.indexOf(ext) == -1) {
          return false;
        } else {
        	return true;
        }
      }

      document.getElementById('upload_file').onsubmit = function(){
        if (boolCheckImg == false) {
          notification("Изображение не соответствует формату", 'warning')
          return false;
        }
      }


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
      }

      function lazyScroll() {
        var header = document.getElementById('form-organizations');
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
        request.open('POST','update_org');
        request.addEventListener('readystatechange', function() {
        if ((request.readyState==4) && (request.status==200)) {
          if (boolSearch){
            loadMoreBlock.innerHTML = request.responseText;
          } else{
            if (request.responseText.trim() == "<div class='empty'>Здесь пока-что ничего нет</div>") {
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

     function addOrganization(id, name) {
       var request = createRequest();
       var data = new FormData();
       data.append('id', id);
       request.open('POST','add_org');
       request.addEventListener('readystatechange', function() {
         if ((request.readyState==4) && (request.status==200)) {
           if (request.responseText.trim() == "success") {
            document.getElementById('my-org').innerHTML = name;
            l_border = 0;
            loadMoreBlock.innerHTML = '';
            boolFriend = true;
            output();
           } else if (request.responseText.trim() == "admin")
           notification("Вы являетесь администратором организации", 'warning');
           else{
             notification("Ошибка!", 'error');
           }
         }
       });
      request.send(data);
     }
