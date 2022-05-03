<div id="layer_bg"></div>
<main id="main">
        <div class="clm">
          <div class="linkBack">
            <a href="../testview/<?= $id_view; ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
          </div>
          <h1><?= $view['test_name']; ?></h1>
          <?php if (isset($attempt)): ?>
            <?php echo "<div class='alert warning'>У вас продолжается прошлая попытка</div>"; ?>
          <?php endif; ?>
          <form id="form_check_test" action="../checktest/<?= $id_view; ?>" method="post">
          <?php
            $query = "SELECT * FROM `questions` WHERE  `ques_test_id` = '" . $id_view . "' ORDER BY RAND()";
            $resQues = mysqli_query($con, $query);
            $counter = 1;
            while($row = mysqli_fetch_assoc($resQues)){
              $queryAns = "SELECT * FROM `answers` WHERE `answer_ques_id` = '" . $row['ques_id'] . "'";
              $resAns = mysqli_query($con, $queryAns);

              echo '<div class="block_ques">';
              echo "<div>";
              echo "<div class='ques'><span>Вопрос №" . $counter . "</span><br><span class='pointer'>Макс. балл " . $row['ques_score'] . ",00</span></div>";
              echo "<div class='form_group'>";
              echo "<div>" . $row['ques_text'] . "</div>";
              echo "<div>";
              if ($row['ques_ans_type'] == 1) {
                echo '<input type="text" class="input" autocomplete="off" name="' . $row['ques_id'] . '">';
              } elseif ($row['ques_ans_type'] == 3) {
                while($item = mysqli_fetch_assoc($resAns)){
                  echo '<label class="labelRad"><input type="radio" name="' . $row['ques_id'] . '" value="' . $item['answer_id'] . '">' . $item['answer_text'] .  '</label>';
                }
              } elseif ($row['ques_ans_type'] == 4) {
                while($item = mysqli_fetch_assoc($resAns)){
                  echo '<label class="labelCheck"><input type="checkbox" name="' . $row['ques_id'] . '[]" value="' . $item['answer_id'] . '">' . $item['answer_text'] .  '</label>';
                }
              }
              echo "</div>";
              echo "</div>";
              echo "</div>";
              echo "</div>";
              $counter++;
            }
           ?>
           <input type="submit" id="sub" class="sub" value="Отправить на проверку">
         </form>
      </div>
      <div class="clm menu-test">
        <h3>Меню теста</h3>
        <span>Всего заданий: <span id="task_num"><?= $view['test_cnt']; ?></span></span>
        <?php if ($view['test_toe'] != 0): ?>
        <div class="timerBlock"><span>Осталось времени </span><span id="timer"></span></div>
        <?php endif; ?>
      </div>
    </main>
    <script>
    let timeBool = false;
        let stopTimer = false;
        <? if($boolAtmpt): ?>
          let timeNum = <?= $attempt['test_status_doe'] - time(); ?>;
        <? else: ?>
        let timeNum = <?= $view['test_toe']; ?>;
        <? endif; ?>


        document.body.onload = function(){
          <?php if ($view['test_toe'] != 0): ?>
            timerPusk();
            <?php endif; ?>
        }


        function timerPusk() {
            let timeProm = timeNum;
            let timeX = 0;
            while (timeProm > 3600){
               timeX += 3600;
                timeProm -= 3600;
            }
            let hour = timeX/3600;
            let min = parseInt((timeNum - hour * 3600) / 60);
            let sec = timeNum-hour*3600-min*60;

            if (min == 60){
                hour += 1;
                min = 0;
            }
            hour = hour % 24;

            let minStr, secStr, hourStr;

            minStr = min;
            secStr = sec;
            hourStr = hour;

            if (min < 10){
                minStr = "0" + minStr;
            }
            if (sec < 10){
                secStr = "0" + secStr;
            }
            if (hour < 10) {
               hourStr = "0" + hourStr;
            }

            let timerStr = hourStr + ":" +  minStr + ":" + secStr;
            document.getElementById('timer').innerHTML = timerStr;
          if (timeNum <= 8) {
            document.getElementById('timer').style.color = 'red';
          }
          timeNum--;
          if (timeNum < 0) {
            timeBool = true;
            timeOver();
            return;
          }
          setTimeout('timerPusk()', 1000);
    }

      /*setInterval(function(){
        <?php if ($view['test_toe'] == 0): ?>
          return false;
        <? endif; ?>
        var request = createRequest();
        var data = new FormData();
        data.append('id', <?= $id_view; ?>);
        request.open('POST','timechecking');
        request.addEventListener('readystatechange', function() {
          if ((request.readyState==4) && (request.status==200)) {
            if (request.responseText == "time_end") {
              //timeOver();
            } else {
              timeNum = request.responseText;
            }
          }
        });
        request.send(data);
      }, 10000);*/

      function timeOver() {
        notification("Время закончились!");
        document.getElementById('form_check_test').submit();
        document.getElementById('layer_bg').classList.add('active');
        document.getElementById('sub').style.transform = "scale(3) translateX(-50%) translateY(-50%)";
        document.getElementById('sub').style.position = "fixed";
        document.getElementById('sub').style.top = "50%";
        document.getElementById('sub').style.left = "50%";
        document.getElementById('sub').style.zIndex = "2000";
      }
    </script>
