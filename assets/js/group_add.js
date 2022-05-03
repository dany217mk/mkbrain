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
  });
});
