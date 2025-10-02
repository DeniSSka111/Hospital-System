<?php
require_once __DIR__ . '/../db.php';
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Госпитализации</title>
  <link rel="stylesheet" href="styles/theme-red-white.css">
  <link rel="stylesheet" href="../styles/theme-red-white.css">
</head>
<body>
  <div class="container">
    <div class="card" style="text-align:left;">
      <h2>Госпитализации</h2>
      <div class="form-row" style="justify-content:space-between; margin-bottom:12px;">

        <a href="../modul1/patients.php" class="button secondary">Пациенты</a>
      </div>

      <?php
      $q = "SELECT h.id, h.patient_id, p.last_name, p.first_name, h.admitted_at, h.discharged_at, h.department, h.bed FROM hospitalizations h LEFT JOIN patients p ON p.patient_id=h.patient_id ORDER BY h.admitted_at DESC";
      $res = $conn->query($q);
      if ($res && $res->num_rows > 0) {
        echo '<table class="table" cellspacing="0">';
        echo '<thead><tr><th>ID</th><th>Пациент</th><th>Поступил</th><th>Выписан</th><th>Отделение</th><th>Койка</th></tr></thead><tbody>';
        while ($r = $res->fetch_assoc()) {
          $pid = htmlspecialchars((string)($r['patient_id'] ?? ''));
          $pname = htmlspecialchars(trim((string)(($r['last_name'] ?? '') . ' ' . ($r['first_name'] ?? ''))));
          echo '<tr>';
          echo '<td>' . htmlspecialchars((string)($r['id'] ?? '')) . '</td>';
          echo '<td><a href="../modul1/patients.php">' . $pname . '</a></td>';
          echo '<td>' . htmlspecialchars((string)($r['admitted_at'] ?? '')) . '</td>';
          echo '<td>' . htmlspecialchars((string)($r['discharged_at'] ?? '')) . '</td>';
          echo '<td>' . htmlspecialchars((string)($r['department'] ?? '')) . '</td>';
          echo '<td>' . htmlspecialchars((string)($r['bed'] ?? '')) . '</td>';
          echo '</tr>';
        }
        echo '</tbody></table>';
      } else {
        echo '<p>Записей не найдено.</p>';
      }
      if ($res instanceof mysqli_result) $res->free();
      $conn->close();
      ?>
      <div class="form-row" style="margin-top:12px; justify-content:space-between; width:100%;">
        <div style="display:flex; gap:10px; justify-content:flex-start;">
          <a href="add_hospitalization.php" class="button">Добавить госпитализацию</a>
        </div>
        <div style="display:flex; gap:10px; justify-content:flex-end;">
          <a href="../index.html" class="button secondary">Назад</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
