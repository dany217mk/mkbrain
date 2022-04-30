 const BORDER = 5;
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
   request.open('POST','updatenews');
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
   request.open('POST','putlike');
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
  request.open('POST','addcomment');
  request.addEventListener('readystatechange', function() {
    if ((request.readyState==4) && (request.status==200)) {
      if (request.responseText == "success") {
        let date = new Date();
        let str_date = date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
        let temp = '<div><a href="../view/' + user_id + '"><img src="' + user_img + '"></a><div><a href="../view/' + user_id + '">' + user_name + ' ' + user_surname + '</a><p>' + inp.value + '</p><span>' + str_date + '</span></div></div>';
         document.getElementById('comment' + id).insertAdjacentHTML('beforeend', temp);
        inp.value = '';
      } else {
        notification("Ошибка 202", 'error');
      }
    }
  });
  request.send(data);
}
