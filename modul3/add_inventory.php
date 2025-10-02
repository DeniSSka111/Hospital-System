<?php
require_once __DIR__ . '/../db.php';
$errors=[];
$values=['medication_id'=>'','batch_number'=>'','quantity'=>0,'unit_price'=>'0.00','expiry_date'=>'','supplier_id'=>'','received_date'=>'','location'=>'','status'=>'активно','notes'=>''];
// Простая форма: вводим medication_id вручную; можно улучшить выпадающим списком
if ($_SERVER['REQUEST_METHOD']==='POST'){
  foreach($values as $k=>$v) $values[$k]=isset($_POST[$k])?trim($_POST[$k]):$v;
  if ($values['medication_id']==='') $errors[]='Укажите ID лекарства.';
  if ($values['batch_number']==='') $errors[]='Укажите номер партии.';
  if (empty($errors)){
    $stmt=$conn->prepare("INSERT INTO inventory (medication_id,batch_number,quantity,unit_price,expiry_date,supplier_id,received_date,location,status,notes,created_at) VALUES (?,?,?,?,?,?,?,?,?,?,NOW())");
    if ($stmt){
      $stmt->bind_param('isidisssss',$values['medication_id'],$values['batch_number'],$values['quantity'],$values['unit_price'],$values['expiry_date'],$values['supplier_id'],$values['received_date'],$values['location'],$values['status'],$values['notes']);
      if ($stmt->execute()){ $stmt->close(); $conn->close(); header('Location: inventory.php'); exit; }
      $errors[]='Ошибка: '.htmlspecialchars($stmt->error); $stmt->close();
    } else $errors[]='Ошибка подготовки: '.htmlspecialchars($conn->error);
  }
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Добавить партию</title>
  <link rel="stylesheet" href="../styles/theme-red-white.css">
</head>
<body>
  <div class="container">
    <div class="card" style="text-align:left;">
      <h2>Добавить партию на склад</h2>
      <?php if ($errors) { echo '<div style="color:#b00020;margin-bottom:12px;"><ul>'; foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; echo '</ul></div>'; } ?>
      <form method="post" action="add_inventory.php">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
          <input class="input" name="medication_id" placeholder="ID лекарства" value="<?php echo htmlspecialchars($values['medication_id']); ?>">
          <input class="input" name="batch_number" placeholder="Номер партии" value="<?php echo htmlspecialchars($values['batch_number']); ?>">
          <input class="input" name="quantity" placeholder="Количество" value="<?php echo htmlspecialchars($values['quantity']); ?>">
          <input class="input" name="unit_price" placeholder="Цена за ед." value="<?php echo htmlspecialchars($values['unit_price']); ?>">
          <input class="input" name="expiry_date" type="date" placeholder="Годен до" value="<?php echo htmlspecialchars($values['expiry_date']); ?>">
          <input class="input" name="received_date" type="date" placeholder="Дата получения" value="<?php echo htmlspecialchars($values['received_date']); ?>">
          <input class="input" name="supplier_id" placeholder="ID поставщика" value="<?php echo htmlspecialchars($values['supplier_id']); ?>">
          <input class="input" name="location" placeholder="Локация" value="<?php echo htmlspecialchars($values['location']); ?>">
          <select class="input" name="status">
            <option value="активно">активно</option>
            <option value="использовано">использовано</option>
            <option value="списано">списано</option>
            <option value="заблокировано">заблокировано</option>
          </select>
          <input class="input" name="notes" placeholder="Примечания" value="<?php echo htmlspecialchars($values['notes']); ?>">
        </div>
        <div class="form-row" style="margin-top:12px; justify-content:space-between; width:100%;">
          <a href="inventory.php" class="button secondary">Отмена</a>
          <button class="button" type="submit">Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
