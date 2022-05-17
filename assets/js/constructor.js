let masBtn = [];
    let masBlock = [];
    for (let i = 0; i < 3; i++) {
    masBtn[i] = 'btn' + (i + 1) ;
    masBlock[i] = 'block' + (i + 1);
    }
    for (let i = 0; i < 3; i++) {
    document.getElementById(masBtn[i]).onclick = function(){
      for (let j = 0; j < 3; j++) {
        document.getElementById(masBtn[j]).classList.remove('active');
        document.getElementById(masBlock[j]).classList.remove('active');
      }
      if (i == 2 && !settingTestCheck()) {
        document.getElementById(masBtn[1]).classList.add('active');
        document.getElementById(masBlock[1]).classList.add('active');
      } else {
      document.getElementById('test_name').innerHTML = name.value;
        document.getElementById(masBtn[i]).classList.add('active');
        document.getElementById(masBlock[i]).classList.add('active');
      }
    }
    }


    let name = document.getElementById('name'),
     subject = document.getElementById('subject'),
     describe = document.getElementById('describe'),
     attempts_allow = document.getElementById('attempts-allow'),
     attempts = document.getElementById('attempts'),
     show = document.getElementById('show'),
     privacy = document.getElementById('privacy'),
     method = document.getElementById('method'),
     time_allow = document.getElementById('time-allow'),
     hour = document.getElementById('hour'),
     minutes = document.getElementById('minutes'),
     seconds = document.getElementById('seconds'),
     img = document.getElementById('img');

     let test_id = 0;

     let cnt_tasks = document.getElementById('cnt_tasks');

     let cnt = 0;

     attempts_allow.onchange = function(){
       document.getElementById('attempts-block').classList.toggle('active');
     }

     time_allow.onchange = function(){
       document.getElementById('time-block').classList.toggle('active');
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
           notification("Выберете изображение", 'warning');
           return;
         }else {
           counterCheckImg++;
         }
         for (let i = 0; i < files.length; i++){
           filesSizeTotal = files[i].size;
           if (!checkTypeFile(files[i].type)) {
             notification("Расширение изображения не соответствует формату", 'warning');
             return;
           } else {
             counterCheckImg++;
           }
         if (filesSizeTotal > 5000000) {
           notification("Размер изобоажения не соответствует формату", 'warning');
           return;
         } else {
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

     let checkName = /^([а-яА-ЯЁёa-zA-Z0-9\s\.\!]+)$/;

     let describeBool = false;

     describe.oninput = function(){
      let val = this.value;
      if (val.trim() == ""){
        val = val.trim();
      }
      document.getElementById('spanLen').innerHTML = val.length;
      if (val.length > 255 || val.trim().length == 0){
        document.getElementById('spanLen').style.color = 'red';
        document.getElementById('describeError').innerHTML = "Описание теста не соответствует формату";
        describeBool = false;
      } else{
        document.getElementById('spanLen').style.color = '#434343';
        document.getElementById('describeError').innerHTML = "";
        describeBool = true;
      }
    }

    let boolName = false;

    name.oninput = function(){
    if (!checkName.test(this.value) || name.value.length > 45){
      document.getElementById('nameError').innerHTML = 'Название теста не соответствует формату';
    } else{
      var request = createRequest();
			request.open('POST','formchecking',true);
			let nameTxt = 'nameTxt=' + encodeURIComponent(name.value);
			request.addEventListener('readystatechange', function() {
			if ((request.readyState==4) && (request.status==200)) {
			document.getElementById('nameError').innerHTML = request.responseText;
			if (request.responseText == "") {
				boolName = true;
			} else {
				boolName = false;
			}
			}
			});
			request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			request.send(nameTxt);
    }
  }

  subject.onchange = function(){
    if (subject.value == "choose") {
      document.getElementById('subjectError').innerHTML = "Выберете значение!";
    } else {
      document.getElementById('subjectError').innerHTML = "";
    }
  }

  method.onchange = function(){
    if (method.value == "choose") {
      document.getElementById('methodError').innerHTML = "Выберете значение!";
    } else {
      document.getElementById('methodError').innerHTML = "";
    }
  }

  attempts.oninput = function(){
    if (!(attempts.value > 0 && attempts.value < 10)) {
      document.getElementById('attemptsError').innerHTML = "Кол-во попыток  не соответствует формату!";
    } else {
      document.getElementById('attemptsError').innerHTML = "";
    }
  }

  hour.onchange = function(){
    if (hour.value == 0 && minutes.value == 0 && seconds.value == 0)  {
      document.getElementById('timeError').innerHTML = "Измените ограничение времени!";
    } else {
      document.getElementById('timeError').innerHTML = "";
    }
  }
  minutes.onchange = function(){
    if (hour.value == 0 && minutes.value == 0 && seconds.value == 0)  {
      document.getElementById('timeError').innerHTML = "Измените ограничение времени!";
    } else {
      document.getElementById('timeError').innerHTML = "";
    }
  }
  seconds.onchange = function(){
    if (hour.value == 0 && minutes.value == 0 && seconds.value == 0)  {
      document.getElementById('timeError').innerHTML = "Измените ограничение времени!";
    } else {
      document.getElementById('timeError').innerHTML = "";
    }
  }



    function settingTestCheck() {
      let counter = 0;
      if (!boolName){
        document.getElementById('nameError').innerHTML = "Название теста не соответствует формату";
      } else {
        document.getElementById('nameError').innerHTML = "";
        counter++;
      }
      if (subject.value == "choose") {
        document.getElementById('subjectError').innerHTML = "Выберете значение!";
      } else {
        document.getElementById('subjectError').innerHTML = "";
        counter++;
      }
      if (!describeBool){
        document.getElementById('describeError').innerHTML = "Описание теста не соответствует формату!";
      } else {
        document.getElementById('describeError').innerHTML = "";
        counter++;
      }
      if (attempts_allow.checked) {
        if (!(attempts.value > 0 && attempts.value < 10)) {
          document.getElementById('attemptsError').innerHTML = "Кол-во попыток  не соответствует формату!";
        } else {
          document.getElementById('attemptsError').innerHTML = "";
          counter++;
        }
      } else {
        document.getElementById('attemptsError').innerHTML = "";
        counter++;
      }
      if (method.value == "choose") {
        document.getElementById('methodError').innerHTML = "Выберете значение!";
      } else {
        document.getElementById('methodError').innerHTML = "";
        counter++;
      }

      if (time_allow.checked) {
        if (hour.value == 0 && minutes.value == 0 && seconds.value == 0 )  {
          document.getElementById('timeError').innerHTML = "Измените ограничение времени!";
      } else {
        document.getElementById('timeError').innerHTML = "";
        counter++;
      }
    } else {
      document.getElementById('timeError').innerHTML = "";
      counter++;
    }

      if (boolCheckImg == false) {
        if (document.getElementById('input_file').value == '') {
           document.getElementById('imgError').innerHTML = "";
           counter++;
        } else {
          document.getElementById('imgError').innerHTML = "Изображение не соответствует формату";
        }
      } else {
        document.getElementById('imgError').innerHTML = "";
        counter++;
      }

      if (counter == 7) {
      return true;
      } else {
        notification("Неверно заполнены поля!" , 'warning');
        return false;
      }
    }

    document.getElementById('continue').onclick = function(){
      if (settingTestCheck()) {
        document.getElementById('test_name').innerHTML = name.value;
        for (let j = 0; j < 3; j++) {
          document.getElementById(masBtn[j]).classList.remove('active');
          document.getElementById(masBlock[j]).classList.remove('active');
        }
          document.getElementById(masBtn[2]).classList.add('active');
          document.getElementById(masBlock[2]).classList.add('active');
      }
    }

    let type_q = document.getElementById('type_question');
    let type_a = document.getElementById('type_answer');

    let addBtn = document.getElementById('addBlock');
    let blocks = document.getElementById('ques-blocks');

    let counterRad =0;
    addBtn.onclick = function(){
      if (cnt >= 20){
       notification("Превышен лимит вопросов", "error");
       return;
      }
      cnt++;
      cnt_tasks.innerHTML = cnt;
      let text = "<div id=block_ques" + cnt + " data-type=" + type_a.value +">";
      text += '<button id="close' + cnt + '" title="' + cnt + '" class="close"><i class="fa fa-times" aria-hidden="true"></i></button>';
      text += '<h2 id="titleBlock' + cnt +  '">Блок №' + cnt + '</h2>';

      text += '<div class="ques">';
      text += '<label>Вопрос: </label>';
      if (type_q.value == 1) {
          text += '<input type="text" data-ques="' + type_q.value + '" data-type="' + type_a.value + '"  id="inpQues'+ cnt + '" class="text inpAns">';
      } else {
        text += '<textarea id="inpQues'+ cnt + '" data-type="' + type_a.value + '" rows="8" cols="80"></textarea>';
      }
      text += '</div>';

      text += '<div class="ans">';
      text += '<label>Ответ: </label>';
      if (type_a.value == 1) {
        text += '<input type="text" id="inpAns' +  cnt +  '" class="text inpAns">';
      } else if (type_a.value == 2) {
          text += '<textarea id="inpAns' +  cnt +  '" rows="8" cols="80"></textarea>';
      } else if (type_a.value == 3) {
        text += '<div id="listRad' + cnt + '">';
        text += '<label class="labelRad"><input type="radio" checked  name="inpRad' + cnt + '" value="0"><input type="text" name="ans'+cnt+'" class="inpAns"></label>';
        text += '</div>';
        text += '<button title="0" class="addOpt" data-blocknum="' + cnt + '"  id="addRad'+ cnt +'">Добавить вариант</button>'
      } else if (type_a.value == 4) {
        text += '<div id="listCheck' + cnt + '">';
        text += '<label class="labelCheck"><input type="checkbox" name="inpCheck'+ cnt + '[]" value="0"><input type="text"  name="ans'+cnt+'" class="inpAns"></label>';
        text += '</div>';
        text += '<button title="0" class="addOpt" data-blocknum="' + cnt + '"  id="addCheck'+ cnt +'">Добавить вариант</button>'
      }
      text += '</div>';
      text += "</div>"
      blocks.insertAdjacentHTML('beforeend', text);

      document.getElementById('close' + cnt).onclick = function(){
        removeBlock(this.title);
      }

      if (type_a.value == 3) {
        document.getElementById('addRad'+ cnt).onclick = function(){
          counterRad = this.title;
            if (counterRad >= 5){
               notification("Превышен лимит ответов", "warning");
               return;
             }
            counterRad++;
             document.getElementById('listRad' + this.getAttribute('data-blockNum')).insertAdjacentHTML('beforeend', '<label class="labelRad"><input type="radio" name="inpRad' + this.getAttribute('data-blockNum') + '" value="' + counterRad +  '"><input type="text" name="ans'+this.getAttribute('data-blockNum')+'" class="inpAns"></label>');
             this.title = counterRad;
        }
      } else if (type_a.value == 4) {
        document.getElementById('addCheck'+ cnt).onclick = function(){
          counterRad = this.title;
            if (counterRad >= 5){
               notification("Превышен лимит ответов", "warning");
               return;
             }
            counterRad++;
             document.getElementById('listCheck' + this.getAttribute('data-blockNum')).insertAdjacentHTML('beforeend','<label class="labelCheck"><input type="checkbox" name="inpCheck' + this.getAttribute('data-blockNum') + '[]" value="' + counterRad +  '"><input type="text" name="ans'+cnt+'" class="inpAns"></label>');
             this.title = counterRad;
        }
      }
      
      let inpAnswerCheck = document.getElementsByClassName('inpAns');
       for (var i = 0; i < inpAnswerCheck.length; i++) {
         inpAnswerCheck[i].oninput = function(){
           var last = this.value.toString().slice(-1);
           if (last == "|") {
             notification("Символ запрещен");
             this.value = this.value.slice(0, -1);
           }
         }
       }

      }

      let ques = [];
      let answ = [];
      let right_answ = [];
      let types_answ = [];
      let types_ques = [];


      document.getElementById('btn_safe').onclick = function(){
        if (cnt == 0) {
          notification("Что-то вопросов маловато)");
          return false;
        }
        ques = [];
        answ = [];
        right_answ = [];
        types= [];
        let right_val;
        let right_val_mas = [];
        for (var i = 0; i < cnt; i++) {
          let ques_inter = document.getElementById('inpQues' + (i+1));
          ques[i] = ques_inter.value.trim();
          if (ques[i] == '') {
            notification("Все поля должны быть заполнены!", 'warning');
            return false;
          }
          types_answ[i] = ques_inter.getAttribute('data-type') ;
          types_ques[i] = ques_inter.getAttribute('data-ques') ;
          if (ques_inter.getAttribute('data-type') == "1" || ques_inter.getAttribute('data-type') == "2") {
            answ[i] = [document.getElementById('inpAns' + (i+1)).value.trim()];
            if (answ[i] == '') {
              notification("Все поля должны быть заполнены!", 'warning');
              return false;
            }
            right_answ[i] = [1];
          } else if (ques_inter.getAttribute('data-type') == "3") {
            let mas = document.querySelectorAll('input[name="ans'  + (i+1) + '"]');
           right_val = document.querySelector('input[name="inpRad' + (i+1) + '"]:checked').value;
            let arr = [];
            let right_arr = [];
            let boolAns = false;
            for (var j = 0; j < mas.length; j++) {
              arr[j] = mas[j].value.trim();
              if (arr[j] == '') {
                notification("Все поля должны быть заполнены!", 'warning');
                return false;
              }
              right_arr[j] = 0;
              if (right_val == j) {
                right_arr[j] = 1;
                boolAns = true;
              }
            }
            if (!boolAns) {
              notification("На каждый вопрос должен быть хотя бы один ответ!", 'warning');
              return false;
            }
            answ[i] = arr;
            right_answ[i] = right_arr;
          } else if (ques_inter.getAttribute('data-type') == "4") {
            let mas = document.querySelectorAll('input[name="ans'  + (i+1) + '"]');
            right_val_mas = document.querySelectorAll('input[name="inpCheck' + (i+1) + '[]"]:checked');
            let arr = [];
            let right_arr = [];
            let arr_rgt = [];
            for (var j = 0; j < right_val_mas.length; j++) {
                arr_rgt[j] = right_val_mas[j].value;
            }
            let boolAns = false;
            for (var j = 0; j < mas.length; j++) {
              arr[j] = mas[j].value;
              if (arr[j] == '') {
                notification("Все поля должны быть заполнены!", 'warning');
                return false;
              }
              right_arr[j] = 0;
              if (arr_rgt.indexOf(j.toString()) != -1) {
                right_arr[j] = 1;
                boolAns = true;
              }
            }
            if (!boolAns) {
              notification("На каждый вопрос должен быть хотя бы один ответ!", 'warning');
              return false;
            }
              answ[i] = arr;
              right_answ[i] = right_arr;
          }
        }
        if (!settingTestCheck()) {
          notification("Ифнормация о тесте должна быть заполнена корректно!");
          for (let j = 0; j < 3; j++) {
            document.getElementById(masBtn[j]).classList.remove('active');
            document.getElementById(masBlock[j]).classList.remove('active');
          }
          document.getElementById(masBtn[1]).classList.add('active');
          document.getElementById(masBlock[1]).classList.add('active');
          return false;
        }
        document.getElementById('layer_bg').classList.add('active');
        document.getElementById('cons_loading').classList.add('active');
        sendInfo()
        setInterval(function(){
          for (var i = 0; i < cnt; i++) {
            var request = createRequest();
            var data = new FormData();
            data.append('ques', ques[i]);
            data.append('answ[]', answ[i].join('|'));
            data.append('right_answ[]', right_answ[i].join('|'));
            data.append('types_answ', types_answ[i]);
            data.append('types_ques', types_ques[i]);
            data.append('test_id', test_id);
            request.open('POST','sendques');
            request.addEventListener('readystatechange', function() {
              if ((request.readyState==4) && (request.status==200)) {
                console.log("success");
              }
            });
           request.send(data);
          }

        window.location.href = "./report/success";
      }, 10000);

      }

      function removeBlock(num) {
        if (!confirm("Вы точно хотите удалить данный блок?")) {
          return false;
        }
        document.getElementById('block_ques' + num).remove();
        num = parseInt(num);
        num += 1;
        while (cnt >= num) {
          let type = document.getElementById('block_ques' + num).getAttribute('data-type');
          document.getElementById('block_ques' + num).id = 'block_ques' + (num-1);
          let btn_close = document.getElementById('close' + num);
          btn_close.id = 'close' + (num-1);
          btn_close.title = (num-1);
          let titleBlock = document.getElementById('titleBlock' + num);
          titleBlock.innerHTML = "Блок №" + (num-1);
          titleBlock.id = 'titleBlock' + (num-1);
          document.getElementById('inpQues' + num).id = 'inpQues' + (num-1);
          if (type == '1' || type == '2') {
            document.getElementById('inpAns' + num).id = 'inpAns' + (num-1);
          } else if (type == '3') {
            let addRad = document.getElementById('addRad' + num);
            addRad.id = 'addRad' + (num-1);
            addRad.setAttribute('data-blocknum', (num-1));
            document.getElementById('listRad' + num).id = 'listRad' + (num-1);
            let masInpRad = document.querySelectorAll('input[name="inpRad' + num + '"]');
            let masInpAns  = document.querySelectorAll('input[name="ans' + num + '"]');
            for (var i = 0; i < masInpRad.length; i++) {
              masInpRad[i].name = 'inpRad' + (num-1);
              masInpAns[i].name = 'ans' + (num-1);
            }
          } else if (type == 4) {
            let addCheck = document.getElementById('addCheck' + num);
            addCheck.id = 'addCheck' + (num-1);
            addCheck.setAttribute('data-blocknum', (num-1));
            document.getElementById('listCheck' + num).id = 'listCheck' + (num-1);
            let masInpCheck = document.querySelectorAll('input[name="inpCheck' + num + '[]"]');
            let masInpAns  = document.querySelectorAll('input[name="ans' + num + '"]');
            for (var i = 0; i < masInpCheck.length; i++) {
              masInpCheck[i].name = 'inpCheck' + (num-1) + "[]";
              masInpAns[i].name = 'ans' + (num-1);
            }
          }
          notification("Блок успешно удалён!");
          num += 1
        }
        cnt-=1;
        cnt_tasks.innerHTML = cnt;
      }


      function sendInfo() {
        var request = createRequest();
        var data = new FormData();
        data.append('name', name.value);
        data.append('describe', describe.value);
        data.append('method', method.value);
        data.append('subject', subject.value);
        data.append('cnt', cnt);
        if (attempts_allow.checked) {
          data.append('attempts', attempts.value);
        }
        if (show.checked) {
          data.append('show', 1);
        } else {
          data.append('show', 0);
        }
        if (privacy.checked) {
          data.append('privacy', 1);
        } else {
          data.append('privacy', 0);
        }
        if (time_allow.checked) {
          let t = (parseInt(hour.value)*60*60) + (parseInt(minutes.value)*60) + parseInt(seconds.value);
          data.append('time', t);
        }

        request.open('POST','infoinserting');
        request.addEventListener('readystatechange', function() {
          if ((request.readyState==4) && (request.status==200)) {
            if (request.responseText != "error") {
              test_id = parseInt(request.responseText);
            } else {
              notification("error", 'error');
              return false;
            }
            }
        });
       request.send(data);
      }
