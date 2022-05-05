document.getElementById('allow').onchange = function(){
  var request = createRequest();
   request.open('POST','../updateallow',true);
   var data = new FormData();
   data.append('allow', document.getElementById('allow').checked);
   data.append('id', id_group);
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
