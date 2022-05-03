document.getElementById('form').onsubmit = function(){
  if (document.getElementById('inp').value.trim() == '') {
    notification('Заполните поле Название организации!', 'warning');
    return false;
  }
}
