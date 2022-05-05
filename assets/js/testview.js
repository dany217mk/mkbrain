document.getElementById('btn_test_menu').onclick = function(){
        document.getElementById('block_test').classList.toggle('active');
        document.getElementById('btn_test_menu').classList.toggle('active');
      }
      function putLike(id){
        var request = createRequest();
        var data = new FormData();
        data.append('id_test', id);
        request.open('POST','../testputlike');
        request.addEventListener('readystatechange', function() {
          if ((request.readyState==4) && (request.status==200)) {
            if (request.responseText == "put") {
              document.getElementById('like_icon').classList.add('active');
              document.getElementById('num_likes').innerHTML  = parseInt(document.getElementById('num_likes').innerHTML) + 1;
            } else if (request.responseText == "remove") {
              document.getElementById('like_icon').classList.remove('active');
              document.getElementById('num_likes').innerHTML  = parseInt(document.getElementById('num_likes').innerHTML) - 1;
            } else {
              notification("Ошибка 4632", 'error');
            }
          }
        });
        request.send(data);
      }
