<?php
require_once __DIR__ . '/../db.php';
$errors=[];
$values=['name'=>'','contact_person'=>'','phone'=>'','email'=>'','address'=>'','inn'=>'','rating'=>'хорошо','is_active'=>1];
if ($_SERVER['REQUEST_METHOD']==='POST'){
  foreach($values as $k=>$v) $values[$k]=isset($_POST[$k])?trim($_POST[$k]):$v;
  if ($values['name']==='') $errors[]='Укажите название поставщика.';
  if (empty($errors)){
    $stmt=$conn->prepare("INSERT INTO suppliers (name,contact_person,phone,email,address,inn,rating,is_active,created_at) VALUES (?,?,?,?,?,?,?,?,NOW())");
    if ($stmt){
      $ia = $values['is_active']?1:0;
      $stmt->bind_param('sssssssi',$values['name'],$values['contact_person'],$values['phone'],$values['email'],$values['address'],$values['inn'],$values['rating'],$ia);
      if ($stmt->execute()){ $stmt->close(); $conn->close(); header('Location: suppliers.php'); exit; }
      $errors[]='Ошибка: '.htmlspecialchars($stmt->error); $stmt->close();
    } else $errors[]='Ошибка подготовки: '.htmlspecialchars($conn->error);
  }
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Добавить поставщика</title>
  <link rel="stylesheet" href="../styles/theme-red-white.css">
</head>
<body>
  <div class="container">
    <div class="card" style="text-align:left;">
      <h2>Добавить поставщика</h2>
      <?php if ($errors) { echo '<div style="color:#b00020;margin-bottom:12px;"><ul>'; foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; echo '</ul></div>'; } ?>
      <form method="post" action="add_supplier.php">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
          <input class="input" name="name" placeholder="Название" value="<?php echo htmlspecialchars($values['name']); ?>">
          <input class="input" name="contact_person" placeholder="Контактное лицо" value="<?php echo htmlspecialchars($values['contact_person']); ?>">
          <input class="input" name="phone" placeholder="Телефон" value="<?php echo htmlspecialchars($values['phone']); ?>">
          <input class="input" name="email" placeholder="Email" value="<?php echo htmlspecialchars($values['email']); ?>">
          <input class="input" name="inn" placeholder="ИНН" value="<?php echo htmlspecialchars($values['inn']); ?>">
          <input class="input" name="address" placeholder="Адрес" value="<?php echo htmlspecialchars($values['address']); ?>">
          <select class="input" name="rating">
            <option value="отлично">отлично</option>
            <option value="хорошо">хорошо</option>
            <option value="удовлетворительно">удовлетворительно</option>
            <option value="плохо">плохо</option>
          </select>
          <label><input type="checkbox" name="is_active" value="1" <?php echo $values['is_active']? 'checked':''; ?>> Активен</label>
        </div>
        <div class="form-row" style="margin-top:12px; justify-content:space-between; width:100%;">
          <a href="suppliers.php" class="button secondary">Отмена</a>
          <button class="button" type="submit">Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
