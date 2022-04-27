let email = document.getElementById('email');
  let password = document.getElementById('password');

  let emailError = document.getElementById('error-email');
  let passwordError = document.getElementById('error-password');

  let emailCheck = /^[a-z0-9_.-]+@([a-z0-9-]+\.)+[a-z]{2,6}$/i;
  let passwordCheck = /^[a-zA-Z0-9_\-\$]{6,30}$/;

  let nameCheck = /^([а-яА-ЯЁёa-zA-Z0-9]+)$/u;
  let surnameCheck = /^([а-яА-ЯЁёa-zA-Z0-9]+)$/u;

  let btnAuth = document.getElementById('btn-auth');

  email.oninput = function(){
    if (emailCheck.test(email.value)){
      emailError.innerHTML = "";
    }
  }

  password.oninput= function(){
    if (passwordCheck.test(password.value)){
      passwordError.innerHTML = "";
    }
  }


  document.getElementById('form').onsubmit = function(){
    let counter = 0;
    if (!emailCheck.test(email.value)){
          emailError.innerHTML = "Неверный email";
      } else{
          counter++;
      }
    if (!passwordCheck.test(password.value)){
      passwordError.innerHTML = "Пароль не соответствует формату";
    } else{
    counter++;
    }
    if (counter != 2){
      notification("Неверно заполнены поля!", 'error');
      return false;
    }
  }

  let name = document.getElementById('name');
  let surname = document.getElementById('surname');
  let email_reg = document.getElementById('email-reg');
  let password_reg = document.getElementById('password-reg');
  let cpassword = document.getElementById('cpassword');


  document.getElementById('form-reg').onsubmit = function(){
    let counter = 0;
    if (!emailCheck.test(email_reg.value)){
          email_reg.classList.add("errorInput");
      } else{
          counter++;
      }
    if (!passwordCheck.test(password_reg.value)){
        password_reg.classList.add("errorInput");
    } else{
        counter++;
    }
    if (cpassword.value != password_reg.value) {
      cpassword.classList.add("errorInput");
    } else {
      counter++;
    }
    if (this.gender.value == ""){
      changeGender(true);
    } else {
      counter++;
    }
    if (!nameCheck.test(name.value)){
        name.classList.add("errorInput");
    } else{
        counter++;
    }
    if (!surnameCheck.test(surname.value)){
        surname.classList.add("errorInput");
    } else{
        counter++;
    }
    if (counter != 6){
      notification("Неверно заполнены поля!", 'error');
      report("Формат пароля: Пароли должны совпадать. Пароль – строка длиной от 6 до 20 символов,может содержать в себе символы латинского алфавита, цифры и спецсимволы «$», «_», «-».", 10000)
      return false;
    }
  }

  name.onfocus = function(){
    name.classList.remove("errorInput");
  }
  name.onblur = function(){
    if (!nameCheck.test(name.value)){
        name.classList.add("errorInput");
    }
  }

  surname.onfocus = function(){
    surname.classList.remove("errorInput");
  }
  surname.onblur = function(){
    if (!surnameCheck.test(surname.value)){
        surname.classList.add("errorInput");
    }
  }

  email_reg.onfocus = function(){
    email_reg.classList.remove("errorInput");
  }
  email_reg.onblur = function(){
    if (!emailCheck.test(email_reg.value)){
        email_reg.classList.add("errorInput");
    }
  }

  password_reg.onfocus = function(){
    password_reg.classList.remove("errorInput");
  }
  password_reg.onblur = function(){
    if (!passwordCheck.test(password_reg.value)){
        password_reg.classList.add("errorInput");
    }
  }

  cpassword.onfocus = function(){
    cpassword.classList.remove("errorInput");
  }
  cpassword.onblur = function(){
    if (password_reg.value != cpassword.value){
        cpassword.classList.add("errorInput");
    }
  }

  document.getElementById('form-reg').onchange = function(){
    changeGender(false);
  }


  let btn_auth = document.getElementById('auth-button');
  btn_auth.onclick = function(){
    document.getElementById('layer_bg').classList.toggle('active');
    document.getElementById('auth').classList.toggle('active');
    document.getElementById('close-area').classList.add('active');
    //controlInput(true);
  }

  document.getElementById('close-auth').onclick = function(){
    document.getElementById('layer_bg').classList.toggle('active');
    document.getElementById('auth').classList.toggle('active');
    document.getElementById('close-area').classList.remove('active');
    //controlInput(false);
  }

  let btn_reg = document.getElementById('reg-button');
  btn_reg.onclick = function(){
    document.getElementById('layer_bg').classList.toggle('active');
    document.getElementById('join').classList.toggle('active');
    document.getElementById('close-area').classList.add('active');
    controlInput(true);
  }

  document.getElementById('close-reg').onclick = function(){
    document.getElementById('layer_bg').classList.toggle('active');
    document.getElementById('join').classList.toggle('active');
    document.getElementById('close-area').classList.remove('active');
    controlInput(false);
  }

  let btn_auth_code = document.getElementById('auth_code-button');
  btn_auth_code.onclick = function(){
    document.getElementById('layer_bg').classList.toggle('active');
    document.getElementById('auth_code').classList.toggle('active');
    document.getElementById('close-area').classList.add('active');
    //controlInput(true);
  }

  document.getElementById('close-auth_code').onclick = function(){
    document.getElementById('layer_bg').classList.toggle('active');
    document.getElementById('auth_code').classList.toggle('active');
    document.getElementById('close-area').classList.remove('active');
    //controlInput(false);
  }

  document.getElementById('close-code_yet').onclick = function(){
    document.getElementById('layer_bg').classList.toggle('active');
    document.getElementById('auth_code').classList.toggle('active');
    document.getElementById('close-area').classList.remove('active');

    //controlInput(false);
  }

  document.getElementById('close-area').onclick = function(){
    document.getElementById('layer_bg').classList.remove('active');
    document.getElementById('auth_code').classList.remove('active');
    document.getElementById('join').classList.remove('active');
    document.getElementById('auth').classList.remove('active');
    document.getElementById('close-area').classList.remove('active');

    controlInput(false);

  }


function controlInput(bool) {
  email.disabled = bool;
  password.disabled = bool;
  btnAuth.disabled = bool;
}

function changeGender(bool){
  let gendersBlock = document.getElementsByClassName('gender');
  for (var i = 0; i < gendersBlock.length; i++) {
    if (bool) {
      gendersBlock[i].classList.add("errorInput");
    } else{
      gendersBlock[i].classList.remove("errorInput");
    }
  }
}

document.getElementById('form_auth_code').oninput = function(){
  if (document.getElementById('code').value.trim() != '') {
    document.getElementById('error-code').innerHTML = '';
    document.getElementById('code').classList.remove('error');
  }
}


document.getElementById('form_auth_code').onsubmit = function() {
  if (document.getElementById('code').value.trim() == '') {
    document.getElementById('error-code').innerHTML = 'Поле обязательно для заполнения';
    document.getElementById('code').classList.add('error');
    return false;
  }
}
