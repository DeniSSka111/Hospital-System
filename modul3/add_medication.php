<?php
require_once __DIR__ . '/../db.php';
$errors=[];
$values=['name'=>'','generic_name'=>'','form'=>'таблетки','strength'=>'','manufacturer'=>'','barcode'=>'','atc_code'=>'','prescription_required'=>1,'storage_temperature'=>'комнатная','min_stock_level'=>10,'max_stock_level'=>100,'is_active'=>1];
if ($_SERVER['REQUEST_METHOD']==='POST'){
  foreach($values as $k=>$v) $values[$k]=isset($_POST[$k])?trim($_POST[$k]):$v;
  if ($values['name']==='') $errors[]='Укажите название лекарства.';
  if (empty($errors)){
    $stmt=$conn->prepare("INSERT INTO medications (name,generic_name,form,strength,manufacturer,barcode,atc_code,prescription_required,storage_temperature,min_stock_level,max_stock_level,is_active,created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
    if ($stmt){
      $pr = $values['prescription_required']?1:0;
      $ia = $values['is_active']?1:0;
      $stmt->bind_param('ssssssssiiis',$values['name'],$values['generic_name'],$values['form'],$values['strength'],$values['manufacturer'],$values['barcode'],$values['atc_code'],$pr,$values['storage_temperature'],$values['min_stock_level'],$values['max_stock_level'],$ia);
      if ($stmt->execute()){ $stmt->close(); $conn->close(); header('Location: medications.php'); exit; }
      $errors[]='Ошибка: '.htmlspecialchars($stmt->error); $stmt->close();
    } else $errors[]='Ошибка подготовки: '.htmlspecialchars($conn->error);
  }
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Добавить лекарство</title>
  <link rel="stylesheet" href="../styles/theme-red-white.css">
</head>
<body>
  <div class="container">
    <div class="card" style="text-align:left;">
      <h2>Добавить лекарство</h2>
      <?php if ($errors) { echo '<div style="color:#b00020;margin-bottom:12px;"><ul>'; foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; echo '</ul></div>'; } ?>
      <form method="post" action="add_medication.php">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
          <input class="input" name="name" placeholder="Название" value="<?php echo htmlspecialchars($values['name']); ?>">
          <input class="input" name="generic_name" placeholder="Международное название" value="<?php echo htmlspecialchars($values['generic_name']); ?>">
          <select class="input" name="form">
            <option value="таблетки">таблетки</option>
            <option value="капсулы">капсулы</option>
            <option value="сироп">сироп</option>
            <option value="инъекции">инъекции</option>
            <option value="мазь">мазь</option>
            <option value="капли">капли</option>
            <option value="спрей">спрей</option>
            <option value="порошок">порошок</option>
          </select>
          <input class="input" name="strength" placeholder="Дозировка" value="<?php echo htmlspecialchars($values['strength']); ?>">
          <input class="input" name="manufacturer" placeholder="Производитель" value="<?php echo htmlspecialchars($values['manufacturer']); ?>">
          <input class="input" name="barcode" placeholder="Штрихкод" value="<?php echo htmlspecialchars($values['barcode']); ?>">
          <input class="input" name="atc_code" placeholder="ATC" value="<?php echo htmlspecialchars($values['atc_code']); ?>">
          <select class="input" name="storage_temperature">
            <option value="комнатная">комнатная</option>
            <option value="холодильник">холодильник</option>
            <option value="морозильник">морозильник</option>
          </select>
          <input class="input" name="min_stock_level" placeholder="Мин. запас" value="<?php echo htmlspecialchars($values['min_stock_level']); ?>">
          <input class="input" name="max_stock_level" placeholder="Макс. запас" value="<?php echo htmlspecialchars($values['max_stock_level']); ?>">
          <label><input type="checkbox" name="prescription_required" value="1" <?php echo $values['prescription_required']? 'checked':''; ?>> По рецепту</label>
          <label><input type="checkbox" name="is_active" value="1" <?php echo $values['is_active']? 'checked':''; ?>> Активен</label>
        </div>
        <div class="form-row" style="margin-top:12px; justify-content:space-between; width:100%;">
          <a href="medications.php" class="button secondary">Отмена</a>
          <button class="button" type="submit">Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
