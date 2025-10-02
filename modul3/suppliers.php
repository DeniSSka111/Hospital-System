<?php
require_once __DIR__ . '/../db.php';
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Поставщики</title>
  <link rel="stylesheet" href="../styles/theme-red-white.css">
</head>
<body>
  <div class="container">
    <div class="card" style="text-align:left;">
      <h2>Поставщики</h2>
      <div class="form-row" style="justify-content:space-between; margin-bottom:12px;">
        <div style="display:flex; gap:10px;"><a href="add_supplier.php" class="button">Добавить поставщика</a></div>
        <a href="../index.html" class="button secondary">Главная</a>
        <div style="display:flex; gap:10px;"><a href="inventory.php" class="button">Склад</a><a href="medications.php" class="button">Лекарства</a></div>
      </div>
      <?php
      $q = "SELECT id, name, contact_person, phone, email, inn, is_active, created_at FROM suppliers ORDER BY name";
      $res = $conn->query($q);
      if ($res && $res->num_rows>0) {
        echo '<table class="table" cellspacing="0">';
        echo '<thead><tr><th>ID</th><th>Название</th><th>Контакт</th><th>Телефон</th><th>Email</th><th>ИНН</th><th>Активен</th></tr></thead><tbody>';
        while ($r=$res->fetch_assoc()) {
          echo '<tr>';
          echo '<td>'.htmlspecialchars((string)$r['id']).'</td>';
          echo '<td>'.htmlspecialchars($r['name']).'</td>';
          echo '<td>'.htmlspecialchars($r['contact_person']).'</td>';
          echo '<td>'.htmlspecialchars($r['phone']).'</td>';
          echo '<td>'.htmlspecialchars($r['email']).'</td>';
          echo '<td>'.htmlspecialchars($r['inn']).'</td>';
          echo '<td>'.htmlspecialchars($r['is_active']? 'Да':'Нет').'</td>';
          echo '</tr>';
        }
        echo '</tbody></table>';
      } else { echo '<p>Записей не найдено.</p>'; }
      if ($res instanceof mysqli_result) $res->free();
      $conn->close();
      ?>
    </div>
  </div>
</body>
</html>
