let form = document.querySelector('.typing-area');
     form.onsubmit = (e)=>{
       e.preventDefault();
     }
     let inputField = form.querySelector(".input-msg"),
     sendBtn = form.querySelector("button"),
     chatBox = document.querySelector(".chat-box");

     chatBox.onmouseenter = ()=>{
       chatBox.classList.add("active");
     }

     chatBox.onmouseleave = ()=>{
         chatBox.classList.remove("active");
     }


     inputField.focus();
     inputField.oninput = function(){
       if (inputField.value.trim() != "") {
         sendBtn.classList.add("active");
       }else {
         sendBtn.classList.remove("active");
       }
     }

     sendBtn.onclick = function(){
       if (inputField.value.trim() == "") {
         sendBtn.classList.remove("active");
         return false;
       }
       var request = createRequest();
       var data = new FormData();
       data.append('id', userChatId);
       data.append('msg', inputField.value);
       request.open('POST','../addmsg');
       request.addEventListener('readystatechange', function() {
         if ((request.readyState==4) && (request.status==200)) {
           if (request.responseText == 'success') {
             inputField.value = "";
             sendBtn.classList.remove("active");
             scrollToBottom();
           } else {
             notification(request.responseText, 'error');
           }
         }
       });
      request.send(data);
     }

     function scrollToBottom(){
       chatBox.scrollTop = chatBox.scrollHeight;
     }




     let strLen = 0;

     setInterval(() =>{
       var request = createRequest();
       var data = new FormData();
       data.append('id', userChatId);
       request.open('POST','../getchat');
       request.addEventListener('readystatechange', function() {
         if ((request.readyState==4) && (request.status==200)) {
           document.getElementById('loading-icon').classList.add('hide');
           if (strLen == request.responseText.length) {
             return false;
           }
           chatBox.innerHTML = request.responseText;
           strLen = request.responseText.length;
           if(!chatBox.classList.contains("active")){
               scrollToBottom();
             }
             readAllMsg();
         }
       });
      request.send(data);
     }, 500);

     function readAllMsg() {
       var request = createRequest();
       var data = new FormData();
       data.append('id', userChatId);
       request.open('POST','../readmsg');
       request.addEventListener('readystatechange', function() {
         /*if ((request.readyState==4) && (request.status==200)) {
         }*/
       });
      request.send(data);
     }


     function deleteSpaces(str) {
       return str.replace(/\s+/g, '');
     }
