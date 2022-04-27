if (privacy == 1 && !boolFriend) {
  console.log("Приватный аккаунт");
} else {
  document.getElementById('show-details').onclick = function(){
       document.getElementById('user-info').classList.toggle('active');
       if (document.getElementById('user-info').classList.contains('active')) {
         document.getElementById('show-details').innerHTML = "Скрыть подробную информацию";
       } else {
         document.getElementById('show-details').innerHTML = "Показать подробную информацию";
       }
     }
}




   function deleteFriend(id){
     if (!confirm("Вы точно хотите удалить этого пользователя?")){
       return false;
     }
     var request = createRequest();
     var data = new FormData();
     data.append('id_friend', id);
     data.append('type', 'deleteFriend');
     request.open('POST','../friendaction');
     request.addEventListener('readystatechange', function() {
       if ((request.readyState==4) && (request.status==200)) {
         if (request.responseText == "success") {
           notification("Пользователь удалён", 'success');
           window.location.href = "../view/" + id_view;
         } else{
           notification("Ошибка удаления!", 'error');
         }
       }
     });
    request.send(data);
   }


   function addFriend(id){
     var request = createRequest();
     var data = new FormData();
     data.append('id_friend', id);
     data.append('type', 'addFriend');
     request.open('POST','../friendaction');
     request.addEventListener('readystatechange', function() {
       if ((request.readyState==4) && (request.status==200)) {
         if (request.responseText == "success") {
           notification("Заявка отправлена", 'success');
           window.location.href = "../view/" + id_view;
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
     data.append('id_friend', id);
     data.append('type', 'removeRequest');
     request.open('POST','../friendaction');
     request.addEventListener('readystatechange', function() {
       if ((request.readyState==4) && (request.status==200)) {
         notification(request.responseText);
         if (request.responseText == "success") {
           notification("Заявка отменена", 'success');
           window.location.href = "../view/" + id_view;
         } else{
           notification("Ошибка отмены заявки!", 'error');
         }
       }
     });
    request.send(data);
   }

   function accessRequest(id){
     var request = createRequest();
     var data = new FormData();
     data.append('id_friend', id_view);
     data.append('type', 'accessRequest');
     request.open('POST','../friendaction');
     request.addEventListener('readystatechange', function() {
       if ((request.readyState==4) && (request.status==200)) {
         notification(request.responseText);
         if (request.responseText == "success") {
           notification("Заявка принята", 'success');
           window.location.href = "../view/" + id_view;
         } else{
           notification("Ошибка принятия заявки!", 'error');
         }
       }
     });
    request.send(data);
   }
