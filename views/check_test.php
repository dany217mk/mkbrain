<?php


   $queryTest = "SELECT * FROM `tests` WHERE `test_id` = '" . $id_view . "'";
   $resTestView = mysqli_query($con, $queryTest);

     $view = mysqli_fetch_assoc($resTestView);

   ?>
   <main id="main">
       <div class="clm">
         <div class="linkBack">
           <a href="../testview/<?= $id_view ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
         </div>
         <h1><?= $view['test_name']; ?></h1>
         <div class="total">
           <h3>Итоги теста</h3>
           <h4>Баллы: <span id="score"></span> из <span id="maxScore"></span></h4>
           <div>Ваша оценка за тест <span id="mark">5</span></div>
           <div>Тест решён на <span id="procent"></span>%</div>
         </div>
         <form>
         <?php
         $counterScore = 0;
         $counterMaxSxore = 0;
         if ($view['test_show_ans'] == 0) {
           $showAns = true;
         } else {
           $showAns = false;
         }
           $query = "SELECT * FROM `questions` WHERE  `ques_test_id` = '" . $id_view . "' ORDER BY RAND()";
           $resQues = mysqli_query($con, $query);
           $counter = 1;
           while($row = mysqli_fetch_assoc($resQues)){
             $queryAns = "SELECT * FROM `answers` WHERE `answer_ques_id` = '" . $row['ques_id'] . "'";
             $resAns = mysqli_query($con, $queryAns);

             $counterMaxSxore += $row['ques_score'];

             $classAns = 'noright';

             $answ = [];
             $answ_id = [];
             $answer_correct = [];
             $answer_correct_text = [];

             $numIterAnsw = 0;
             $numIterCorr = 0;
             while($item = mysqli_fetch_assoc($resAns)){
               $answ[$numIterAnsw] = $item['answer_text'];
               $answ_id[$numIterAnsw] = $item['answer_id'];
               if ($item['answer_correct'] == 1) {
                 $answer_correct[$numIterCorr] = $item['answer_id'];
                 $answer_correct_text[$numIterCorr] = $item['answer_text'];
               } else {
                 $answer_correct[$numIterCorr] = 0;
                 $answer_correct_text[$numIterCorr] = "error-mk-error-test-code-1213423525251";
               }
               $numIterAnsw++;
               $numIterCorr++;
             }


             $rights = [];

             echo '<div class="block_ques">';
             echo "<div>";
             echo "<div class='ques'><span>Вопрос №" . $counter . "</span><br><span class='pointer'>Макс. балл " . $row['ques_score'] . ",00</span></div>";
             echo "<div class='form_group'>";
             echo "<div>" . $row['ques_text'] . "</div>";
             echo "<div>";
             $cntRightCheck = 0;
             if ($row['ques_ans_type'] == 1) {
               echo '<input type="text" class="input"  disabled value="' . $_POST[$row['ques_id']]  . '">';
               for ($i=0; $i < count($answ); $i++) {
                 if ((mb_strtolower($_POST[$row['ques_id']]) == mb_strtolower($answ[$i]))){
                   $counterScore+= $row['ques_score'];
                   $classAns = "right";
                 }
               }
             } elseif ($row['ques_ans_type'] == 3) {

               if (isset($_POST[$row['ques_id']])) {
                 for ($i=0; $i < count($answ); $i++) {
                   if ($_POST[$row['ques_id']] == $answ_id[$i]) {
                     if ($answer_correct[$i] == $answ_id[$i]) {
                       $counterScore+= $row['ques_score'];
                       $classAns = 'right';
                     }
                       echo '<label class="labelRad"><input type="radio" checked disabled>' . $answ[$i] .  '</label>';
                   } else {
                     echo '<label class="labelRad"><input type="radio" disabled>' . $answ[$i] .  '</label>';
                   }
                 }
               } else {
                 for ($i=0; $i < count($answ); $i++) {
                   echo '<label class="labelRad"><input type="radio" disabled>' . $answ[$i] .  '</label>';
                 }
               }

             } elseif ($row['ques_ans_type'] == 4) {
               $countNumCorrect = countingCorrect($answer_correct);
               if (isset($_POST[$row['ques_id']])) {
               $boolRight = false;
               $changeBool = false;
               for ($i=0; $i < count($answ); $i++) {
                 if (in_array($answ_id[$i], $_POST[$row['ques_id']])) {
                   if ($answer_correct[$i] == $answ_id[$i]) {
                     if (!$changeBool) {
                       $boolRight = true;
                     }
                   }else {
                     $boolRight = false;
                     $changeBool = true;
                   }
                     $cntRightCheck++;
                     echo '<label class="labelCheck"><input type="checkbox" checked disabled>' . $answ[$i] .  '</label>';
                 } else {
                   echo '<label class="labelCheck"><input type="checkbox" disabled>' . $answ[$i] .  '</label>';
                 }
               }
               if ($boolRight) {
                 $counterScore+= $row['ques_score'];
                 $classAns = 'right';
               }
             } else {
               for ($i=0; $i < count($answ); $i++) {
                 echo '<label class="labelCheck"><input type="checkbox" disabled>' . $answ[$i] .  '</label>';
               }
             }
             }
             if ($row['ques_ans_type'] == 4 && isset($_POST[$row['ques_id']]) && $classAns == 'right') {
               if ($cntRightCheck != $countNumCorrect) {
                 $counterScore-= $row['ques_score'];
                 $classAns = 'noright';
               }
             }
             echo "</div>";
             echo "</div>";
             echo "</div>";
               for ($i=0; $i < count($answer_correct_text); $i++) {
                 if ($answer_correct_text[$i] != "error-mk-error-test-code-1213423525251") {
                     echo '<div class="ans ' . $classAns . '">';
                     if ($showAns) {
                     echo 'Правильный ответ: ' . $answer_correct_text[$i] . '';
                   } else {
                     if ($classAns == "right") {
                       echo "Правильный ответ";
                     } else {
                       echo "Неправильный ответ";
                     }
                     break;
                   }
                     echo '<br></div>';
                 }
               }
             echo "</div>";
             $counter++;
           }

           echo "<script>document.getElementById('score').innerHTML='" . $counterScore . "'</script>";
             echo "<script>document.getElementById('maxScore').innerHTML='" . $counterMaxSxore . "'</script>";
             $procent = number_format(($counterScore*100)/$counterMaxSxore, 1, ",","");
             $mark = 2;
             if ((int)$procent >= 85) {
               $mark = 5;
             } elseif ((int)$procent >= 65) {
               $mark = 4;
             } elseif ((int)$procent >= 50) {
               $mark = 3;
             }
             echo "<script>document.getElementById('procent').innerHTML='" . $procent . "'</script>";
             echo "<script>document.getElementById('mark').innerHTML='" . $mark . "'</script>";

             $queryUpd = "UPDATE `test_status` SET `test_status_is_completed` = 1 WHERE `test_status_test_id` = '" . $id_view . "' AND `test_status_user_id` = '" . $_COOKIE['uid'] . "';";
             mysqli_query($con, $queryUpd);
             $queryMark = "INSERT INTO `marks` (`mark_user_id`, `mark_value`, `mark_test_id`) VALUES ('" . $_COOKIE['uid'] . "', '$mark', '" . $id_view  . "');";
             mysqli_query($con, $queryMark);




             function countingCorrect($arr){
               while (array_search(0, $arr) !== false) {
                 unset($arr[array_search(0, $arr)]);
               }
               return count($arr);
             }
          ?>
        </form>
     </div>
   </div>
     <div class="clm menu-test">
       <h3>Меню теста</h3>
       <span>Всего заданий: <span id="task_num"><?= $view['test_cnt']; ?></span></span>
     </div>
   </main>
