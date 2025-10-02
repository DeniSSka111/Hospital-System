<?php
require_once __DIR__ . '/../db.php';
$errors = [];
$values = ['patient_id'=>'', 'admitted_at'=>'', 'discharged_at'=>'', 'department'=>'', 'bed'=>'', 'diagnosis'=>'', 'notes'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $k => $v) $values[$k] = isset($_POST[$k]) ? trim($_POST[$k]) : '';

    if ($values['patient_id'] === '') $errors[] = 'Укажите ID пациента.';
    if ($values['admitted_at'] === '') $errors[] = 'Укажите дату поступления.';

    if (empty($errors)) {
        // Если дата выписки не указана, не включаем поле в INSERT (чтобы избежать проблем с NULL/binding)
        if ($values['discharged_at'] === '') {
            $stmt = $conn->prepare("INSERT INTO hospitalizations (patient_id, admitted_at, department, bed, diagnosis, notes) VALUES (?,?,?,?,?,?)");
            if ($stmt) {
                $stmt->bind_param('isssss', $values['patient_id'], $values['admitted_at'], $values['department'], $values['bed'], $values['diagnosis'], $values['notes']);
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO hospitalizations (patient_id, admitted_at, discharged_at, department, bed, diagnosis, notes) VALUES (?,?,?,?,?,?,?)");
            if ($stmt) {
                $stmt->bind_param('issssss', $values['patient_id'], $values['admitted_at'], $values['discharged_at'], $values['department'], $values['bed'], $values['diagnosis'], $values['notes']);
            }
        }

        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                header('Location: hospitalizations.php');
                exit;
            } else {
                $errors[] = 'Ошибка выполнения запроса: '.htmlspecialchars($stmt->error);
                $stmt->close();
            }
        } else {
            $errors[] = 'Ошибка подготовки запроса: '.htmlspecialchars($conn->error);
        }
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Добавить госпитализацию</title>
  <link rel="stylesheet" href="../styles/theme-red-white.css">
</head>
<body>
  <div class="container">
    <div class="card" style="text-align:left;">
      <h2>Добавить госпитализацию</h2>
      <?php if ($errors){ echo '<div style="color:#b00020;margin-bottom:12px;"><ul>'; foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; echo '</ul></div>'; } ?>

      <form method="post" action="add_hospitalization.php">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
          <input class="input" name="patient_id" placeholder="ID пациента" value="<?php echo htmlspecialchars($values['patient_id']); ?>">
          <input class="input" name="admitted_at" type="datetime-local" value="<?php echo htmlspecialchars($values['admitted_at']); ?>">
          <input class="input" name="discharged_at" type="datetime-local" value="<?php echo htmlspecialchars($values['discharged_at']); ?>">
          <input class="input" name="department" placeholder="Отделение" value="<?php echo htmlspecialchars($values['department']); ?>">
          <input class="input" name="bed" placeholder="Койка" value="<?php echo htmlspecialchars($values['bed']); ?>">
          <input class="input" name="diagnosis" placeholder="Диагноз" value="<?php echo htmlspecialchars($values['diagnosis']); ?>">
          <input class="input" name="notes" placeholder="Примечания" value="<?php echo htmlspecialchars($values['notes']); ?>">
        </div>

        <div class="form-row" style="margin-top:12px; justify-content:space-between; width:100%;">
          <a href="hospitalizations.php" class="button secondary">Отмена</a>
          <button class="button" type="submit">Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
