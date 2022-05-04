let inputs = document.querySelectorAll('.input_file');
let boolCheckImg = false;
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
    if (files.length == 1){
      document.getElementById("imgError").innerHTML = "";
      counterCheckImg++;
    } else {
      return;
    }
    for (let i = 0; i < files.length; i++){
      filesSizeTotal = files[i].size;
      if (!checkTypeFile(files[i].type)) {
        document.getElementById("imgError").innerHTML = "Расширение изображения не соответствует формату";
        return;
      } else {
        document.getElementById("imgError").innerHTML = "";
        counterCheckImg++;
      }
    if (filesSizeTotal > 5000000) {
      document.getElementById("imgError").innerHTML = "Размер изобоажения не соответствует формату";
      return;
    } else {
      document.getElementById("imgError").innerHTML = "";
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

let checkName = /^([а-яА-ЯЁёa-zA-Z0-9\s\.\!]+)$/;
let name = document.getElementById('name');
let describe = document.getElementById('describe');

name.oninput = function(){
  if (!(!checkName.test(name.value) || name.value.length > 45)){
    document.getElementById('nameError').innerHTML = '';
  }
}

describe.oninput = function(){
  if (!(describe.value.length <= 0 || describe.value.length > 1000)){
    document.getElementById('describeError').innerHTML = '';
  }
}

document.getElementById('form').onsubmit = function(){
  let cnt = 0;
  if (!checkName.test(name.value) || name.value.length > 45){
    document.getElementById('nameError').innerHTML = 'Название теста не соответствует формату';
  } else {
    cnt++;
  }
  if (describe.value.length <= 0 || describe.value.length > 1000) {
    document.getElementById('describeError').innerHTML = 'Описание теста не соответствует формату (0;1000]';
  }else {
    cnt++;
  }
  if (document.getElementById('input_file').value == '') {
      cnt++;
  } else {
    if (boolCheckImg) {
      cnt++;
    }
  }
  if (cnt != 3) {
    notification("Неверно заполнены поля!", 'error');
      return false;
  }
}



function checkTypeFile(ext) {
  let allowed = ['image/gif', 'image/png', 'image/jpeg', 'image/jpg'];
  if (allowed.indexOf(ext) == -1) {
    return false;
  } else {
    return true;
  }
}
