document.getElementById('describe').onclick = function(){
        document.getElementById('block-status').classList.toggle('active');
        document.getElementById('input-status').select();
        document.getElementById('spanLen').innerHTML = document.getElementById('input-status').value.length;
      }
      document.getElementById('close-status').onclick = function(){
        document.getElementById('block-status').classList.remove('active');
      }
      document.getElementById('input-status').oninput = function(){
        document.getElementById('spanLen').innerHTML = this.value.length;
        if (this.value.length > 255) {
          document.getElementById('spanLen').style.color = "red";
        }else {
          document.getElementById('spanLen').style.color = "#939393";
        }
      }
      document.getElementById('btn-status').onclick = function(){
        if (document.getElementById('input-status').value.length <= 255){
          var request = createRequest();
          var data = new FormData();
          data.append('describe', document.getElementById('input-status').value);
          request.open('POST','describe');
          request.addEventListener('readystatechange', function() {
            if ((request.readyState==4) && (request.status==200)) {
              notification(request.responseText);
              if (request.responseText.trim() == "success") {
                if (document.getElementById('input-status').value.trim() == "") {
                  document.getElementById('describe').classList.add('choose');
                  document.getElementById('describe').innerHTML = "Установить статус";
                } else {
                  document.getElementById('describe').classList.remove('choose');
                  document.getElementById('describe').innerText = document.getElementById('input-status').value.trim();
                }
              }
              document.getElementById('block-status').classList.remove('active');
            }
          });
         request.send(data);
       } else {
         notification("Слишком много символов, допустиое число: 255");
       }
      }

      document.getElementById('show-details').onclick = function(){
        document.getElementById('user-info').classList.toggle('active');
        if (document.getElementById('user-info').classList.contains('active')) {
          document.getElementById('show-details').innerHTML = "Скрыть подробную информацию";
        } else {
          document.getElementById('show-details').innerHTML = "Показать подробную информацию";
        }
      }

      function deleteFavorite(id){
        var request = createRequest();
        var data = new FormData();
        data.append('id_test', id);
        request.open('POST','deletefavorite');
        request.addEventListener('readystatechange', function() {
          if ((request.readyState==4) && (request.status==200)) {
            notification(request.responseText);
            if (request.responseText == "success") {
              notification("Успешно", 'success');
              window.location.href = "./my";
            } else{
              notification("Ошибка 464!", 'error');
            }
          }
        });
       request.send(data);
      }
