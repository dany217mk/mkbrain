document.getElementById('show-details').onclick = function(){
       document.getElementById('group-info').classList.toggle('active');
       if (document.getElementById('group-info').classList.contains('active')) {
         document.getElementById('show-details').innerHTML = "Скрыть подробную информацию";
       } else {
         document.getElementById('show-details').innerHTML = "Показать подробную информацию";
       }
     }





   function deleteRequest(id){
     if (!confirm("Вы точно хотите выйти из этой группы?")){
       return false;
     }
     var request = createRequest();
     var data = new FormData();
     data.append('id_group', id);
     data.append('type', 'deleteRequest');
     request.open('POST','../groupaction');
     request.addEventListener('readystatechange', function() {
       if ((request.readyState==4) && (request.status==200)) {
         if (request.responseText == "success") {
           notification("Пользователь удалён", 'success');
           window.location.href = "../group/" + id_group;
         } else if (request.responseText == "admin") {
           notification("Вы не можете выйти из этой группы, так как вы являетесь администратором", 'warning');
           window.location.href = "../report/admin_group";
         } else{
           notification("Ошибка удаления!", 'error');
         }
       }
     });
    request.send(data);
   }


   function addRequest(id){
     var request = createRequest();
     var data = new FormData();
     data.append('id_group', id);
     data.append('type', 'addRequest');
     request.open('POST','../groupaction');
     request.addEventListener('readystatechange', function() {
       if ((request.readyState==4) && (request.status==200)) {
         if (request.responseText == "success") {
           notification("Заявка отправлена", 'success');
           window.location.href = "../group/" + id_group;
         } else{
           notification("Ошибка отправки заявки!", 'error');
         }
       }
     });
    request.send(data);
   }

   function removeRequest(id){
     var request = createRequest();
     var data = new FormData();
     data.append('id_group', id);
     data.append('type', 'removeRequest');
     request.open('POST','../groupaction');
     request.addEventListener('readystatechange', function() {
       if ((request.readyState==4) && (request.status==200)) {
         notification(request.responseText);
         if (request.responseText == "success") {
           notification("Заявка отменена", 'success');
           window.location.href = "../group/" + id_group;
         } else{
           notification("Ошибка отмены заявки!", 'error');
         }
       }
     });
    request.send(data);
   }


   const BORDER = 10;
 let l_border = 0;
 let loadMoreBlock = document.getElementById('list');

 window.addEventListener("scroll", lazyScroll);

 let boolGroup = true;



 function lazyScroll() {
   if (!loadMoreBlock.classList.contains('loading')){
     loadMore();
   }
 }

 const windowHeight = document.documentElement.clientHeight;

 function loadMore() {
   const loadMoreBlockPos = loadMoreBlock.getBoundingClientRect().top + pageYOffset;
   const loadBlockMoreHeight = loadMoreBlock.offsetHeight;
   if (pageYOffset > (loadMoreBlockPos + loadBlockMoreHeight) - windowHeight) {
     output();
   }
 }

 output();
 function output(){
   if (!boolGroup) {
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
   data.append('id_group', id_group);
   request.open('POST','../updaterecords');
   request.addEventListener('readystatechange', function() {
   if ((request.readyState==4) && (request.status==200)) {
     if (request.responseText.trim() == "<h1 class='no-records'>Здесь пока-что нет записей</h1>") {
       if (loadMoreBlock.innerHTML.trim() == "" || loadMoreBlock.innerHTML.trim() == '<div class="loading-icon"><h4>Загрузка...</h4></div>') {
         boolGroup = false;
         loadMoreBlock.insertAdjacentHTML('beforeend', request.responseText);
       }
     } else{
       boolGroup = true;
       loadMoreBlock.insertAdjacentHTML('beforeend', request.responseText);
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

 function putLike(id){
   var request = createRequest();
   var data = new FormData();
   data.append('id_record', id);
   request.open('POST','../putlike');
   request.addEventListener('readystatechange', function() {
     if ((request.readyState==4) && (request.status==200)) {
       if (request.responseText == "put") {
         document.getElementById('like_icon' + id).classList.add('active');
         document.getElementById('numLikes' + id).innerHTML  = parseInt(document.getElementById('numLikes' + id).innerHTML) + 1;
       } else if (request.responseText == "remove") {
         document.getElementById('like_icon' + id).classList.remove('active');
         document.getElementById('numLikes' + id).innerHTML  = parseInt(document.getElementById('numLikes' + id).innerHTML) - 1;
       } else {
         notification("Ошибка 4632", 'error');
       }
     }
   });
   request.send(data);
 }

let cntComment = 0;
function addComment(id, user_id, user_img, user_name, user_surname){
  let inp = document.getElementById('input_comment' + id);
  if (inp.value.trim() == '') {
    notification('Пустое поле', 'warning');
    return false;
  }
  var request = createRequest();
  var data = new FormData();
  data.append('id_record', id);
  data.append('text', inp.value);
  request.open('POST','../addcomment');
  request.addEventListener('readystatechange', function() {
    if ((request.readyState==4) && (request.status==200)) {
      if (request.responseText == "success") {
        cntComment++;
        let date = new Date();
        let str_date = date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
        let text = inp.value;
        let temp = '<div><a href="../view/' + user_id + '"><img src="' + user_img + '"></a><div><a href="../view/' + user_id + '">' + user_name + ' ' + user_surname + '</a><p id="text' + cntComment + '"></p><span>' + str_date + '</span></div></div>';
         document.getElementById('comment' + id).insertAdjacentHTML('beforeend', temp);
         document.getElementById('text' + cntComment).innerText = text;
        inp.value = '';
      } else {
        notification("Ошибка 202", 'error');
      }
    }
  });
  request.send(data);
}

function deleteRecord(id){
  if (!confirm("Вы точно хотите удалить эту запись?")){
    return false;
  }
  var request = createRequest();
  var data = new FormData();
  data.append('id', id);
  request.open('POST','../recorddeleted');
  request.addEventListener('readystatechange', function() {
    if ((request.readyState==4) && (request.status==200)) {
      notification(request.responseText);
      if (request.responseText.trim() == "success") {
        notification("Запись удалена", 'success');
        window.location.reload();
      } else{
        notification("Ошибка удаления!", 'error');
      }
    }
  });
 request.send(data);
}
