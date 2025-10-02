<?php
require_once __DIR__ . '/../db.php';
$errors=[];
$values=['patient_id'=>'','visit_at'=>'','doctor'=>'','department'=>'','diagnosis'=>'','prescription'=>'','notes'=>''];
if ($_SERVER['REQUEST_METHOD']==='POST'){
  foreach($values as $k=>$v) $values[$k]=isset($_POST[$k])?trim($_POST[$k]):'';
  if ($values['patient_id']==='') $errors[]='Укажите ID пациента.';
  if ($values['visit_at']==='') $errors[]='Укажите дату приёма.';
  if (empty($errors)){
    $stmt=$conn->prepare("INSERT INTO outpatient_visits (patient_id, visit_at, doctor, department, diagnosis, prescription, notes, created_at) VALUES (?,?,?,?,?,?,?,NOW())");
    if ($stmt){
      $stmt->bind_param('issssss',$values['patient_id'],$values['visit_at'],$values['doctor'],$values['department'],$values['diagnosis'],$values['prescription'],$values['notes']);
      if ($stmt->execute()){ $stmt->close(); $conn->close(); header('Location: visits.php'); exit; }
      $errors[]='Ошибка выполнения: '.htmlspecialchars($stmt->error); $stmt->close();
    } else { $errors[]='Ошибка подготовки: '.htmlspecialchars($conn->error); }
  }
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Добавить приём</title>
  <link rel="stylesheet" href="../styles/theme-red-white.css">
</head>
<body>
  <div class="container">
    <div class="card" style="text-align:left;">
      <h2>Добавить приём</h2>
      <?php if ($errors){ echo '<div style="color:#b00020;margin-bottom:12px;"><ul>'; foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; echo '</ul></div>'; } ?>
      <form method="post" action="add_visit.php">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
          <input class="input" name="patient_id" placeholder="ID пациента" value="<?php echo htmlspecialchars($values['patient_id']); ?>">
          <input class="input" name="visit_at" type="datetime-local" value="<?php echo htmlspecialchars($values['visit_at']); ?>">
          <input class="input" name="doctor" placeholder="Врач" value="<?php echo htmlspecialchars($values['doctor']); ?>">
          <input class="input" name="department" placeholder="Отделение" value="<?php echo htmlspecialchars($values['department']); ?>">
          <input class="input" name="diagnosis" placeholder="Диагноз" value="<?php echo htmlspecialchars($values['diagnosis']); ?>">
          <input class="input" name="prescription" placeholder="Рецепт" value="<?php echo htmlspecialchars($values['prescription']); ?>">
          <input class="input" name="notes" placeholder="Примечания" value="<?php echo htmlspecialchars($values['notes']); ?>">
        </div>
        <div class="form-row" style="margin-top:12px; justify-content:space-between; width:100%;">
          <a href="visits.php" class="button secondary">Отмена</a>
          <button class="button" type="submit">Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
