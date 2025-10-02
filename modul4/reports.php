<?php
require_once __DIR__ . '/../db.php';

// Простая страница отчётов: выбор типа, диапазона дат, вывод HTML или CSV
$report = $_REQUEST['report'] ?? '';
$from = $_REQUEST['from'] ?? '';
$to = $_REQUEST['to'] ?? '';
$export = isset($_REQUEST['export']) ? (bool)$_REQUEST['export'] : false;
$export_format = $_REQUEST['export_format'] ?? 'excel'; // 'excel' or 'csv'
$expiring_days = (int)($_REQUEST['expiring_days'] ?? 90);
$low_threshold = isset($_REQUEST['low_threshold']) ? (int)$_REQUEST['low_threshold'] : 0;
$show_totals = isset($_REQUEST['show_totals']) ? true : false;

function dateFilterClause($col, $from, $to, &$params, &$types) {
    $clauses = [];
    if ($from !== '') { $clauses[] = "$col >= ?"; $params[] = $from . ' 00:00:00'; $types .= 's'; }
    if ($to !== '') { $clauses[] = "$col <= ?"; $params[] = $to . ' 23:59:59'; $types .= 's'; }
    return $clauses ? ' AND (' . implode(' AND ', $clauses) . ')' : '';
}

$query = '';
$dateCol = '';
if ($report === 'patients') {
    $query = "SELECT patient_id, last_name, first_name, middle_name, dob, gender, phone, address, insurance_number, blood_type, allergies, created_at FROM patients WHERE 1";
    $dateCol = 'created_at';
} elseif ($report === 'hospitalizations') {
    $query = "SELECT h.id, h.patient_id, p.last_name, p.first_name, h.admitted_at, h.discharged_at, h.department, h.bed FROM hospitalizations h LEFT JOIN patients p ON p.patient_id=h.patient_id WHERE 1";
    $dateCol = 'h.admitted_at';
} elseif ($report === 'visits') {
    $query = "SELECT v.id, v.patient_id, p.last_name, p.first_name, v.visit_at, v.doctor, v.department FROM outpatient_visits v LEFT JOIN patients p ON p.patient_id=v.patient_id WHERE 1";
    $dateCol = 'v.visit_at';
} elseif ($report === 'inventory') {
  $query = "SELECT i.id, i.medication_id, m.name AS med_name, i.batch_number, i.quantity, i.unit_price, i.expiry_date, i.received_date, i.location, i.status FROM inventory i LEFT JOIN medications m ON m.id=i.medication_id WHERE 1";
  $dateCol = 'i.received_date';
} elseif ($report === 'medications') {
  $query = "SELECT m.id, m.name, m.generic_name, m.form, m.strength, m.manufacturer, 
           m.prescription_required, m.storage_temperature, m.min_stock_level, m.max_stock_level,
           COALESCE(SUM(i.quantity), 0) as total_stock
        FROM medications m 
        LEFT JOIN inventory i ON m.id = i.medication_id AND i.status = 'активно'
        WHERE m.is_active = TRUE
        GROUP BY m.id";
  $dateCol = '';
} elseif ($report === 'expiring') {
  $query = "SELECT m.name, i.batch_number, i.quantity, i.unit_price, i.expiry_date, 
           i.location, DATEDIFF(i.expiry_date, CURDATE()) as days_left,
           (i.quantity * i.unit_price) as total_value
        FROM inventory i 
        JOIN medications m ON i.medication_id = m.id 
        WHERE i.status = 'активно'";
  $dateCol = 'i.expiry_date';
} elseif ($report === 'stock_low') {
  $query = "SELECT m.name, m.min_stock_level, m.max_stock_level,
           COALESCE(SUM(i.quantity), 0) as current_stock,
           (m.min_stock_level - COALESCE(SUM(i.quantity), 0)) as deficit
        FROM medications m 
        LEFT JOIN inventory i ON m.id = i.medication_id AND i.status = 'активно'
        WHERE m.is_active = TRUE
        GROUP BY m.id";
  $dateCol = '';
} elseif ($report === 'financial') {
  $query = "SELECT m.name, 
           SUM(CASE WHEN sm.movement_type = 'приход' THEN sm.quantity ELSE 0 END) as incoming,
           SUM(CASE WHEN sm.movement_type = 'расход' THEN sm.quantity ELSE 0 END) as outgoing,
           SUM(CASE WHEN sm.movement_type = 'приход' THEN sm.quantity * i.unit_price ELSE 0 END) as income,
           SUM(CASE WHEN sm.movement_type = 'расход' THEN sm.quantity * i.unit_price ELSE 0 END) as expense
        FROM stock_movements sm
        JOIN medications m ON sm.medication_id = m.id
        LEFT JOIN inventory i ON sm.medication_id = i.medication_id AND sm.batch_number = i.batch_number
        WHERE 1";
  $dateCol = 'sm.movement_date';
}

// Map колонок в человекочитаемые заголовки для некоторых отчётов
$headers_map = [
  'patients' => [
    'patient_id'=>'ID','last_name'=>'Фамилия','first_name'=>'Имя','middle_name'=>'Отчество','dob'=>'Дата рождения','gender'=>'Пол','phone'=>'Телефон','address'=>'Адрес','insurance_number'=>'№ полиса','blood_type'=>'Группа крови','allergies'=>'Аллергии','created_at'=>'Создано'
  ],
  'hospitalizations' => ['id'=>'ID','patient_id'=>'ID пациента','last_name'=>'Фамилия','first_name'=>'Имя','admitted_at'=>'Поступил','discharged_at'=>'Выписан','department'=>'Отделение','bed'=>'Койка'],
  'visits' => ['id'=>'ID','patient_id'=>'ID пациента','last_name'=>'Фамилия','first_name'=>'Имя','visit_at'=>'Дата приёма','doctor'=>'Врач','department'=>'Отделение'],
  'inventory' => ['id'=>'ID','medication_id'=>'ID лекарства','med_name'=>'Наименование','batch_number'=>'Партия','quantity'=>'Кол-во','unit_price'=>'Цена ед.','expiry_date'=>'Годен до','received_date'=>'Дата прихода','location'=>'Место','status'=>'Статус'],
  'medications' => ['id'=>'ID','name'=>'Наименование','generic_name'=>'Межд. название','form'=>'Форма','strength'=>'Концентрация','manufacturer'=>'Производитель','prescription_required'=>'По рецепту','storage_temperature'=>'Темп. хранения','min_stock_level'=>'Мин. запас','max_stock_level'=>'Макс. запас','total_stock'=>'Текущий остаток'],
  'expiring' => ['name'=>'Наименование','batch_number'=>'Партия','quantity'=>'Кол-во','unit_price'=>'Цена ед.','expiry_date'=>'Годен до','location'=>'Место','days_left'=>'Осталось дней','total_value'=>'Стоимость'],
  'stock_low' => ['name'=>'Наименование','min_stock_level'=>'Мин. запас','max_stock_level'=>'Макс. запас','current_stock'=>'Текущий остаток','deficit'=>'Дефицит'],
  'financial' => ['name'=>'Наименование','incoming'=>'Приход','outgoing'=>'Расход','income'=>'Доход','expense'=>'Расходы']
];

$rows = [];
if ($query) {
  $params = [];
  $types = '';

  // вставляем date-параметры и другие условия в WHERE через замену 'WHERE 1' — это стабильнее
  if ($dateCol) {
    $df = dateFilterClause($dateCol, $from, $to, $params, $types);
    if ($df) {
      if (preg_match('/WHERE\s+1/i', $query)) {
        $query = preg_replace('/WHERE\s+1/i', 'WHERE 1' . $df, $query, 1);
      } else {
        $query .= $df;
      }
    }
  }

  if ($report === 'expiring') {
    $exp_end = date('Y-m-d', strtotime("+{$expiring_days} days"));
    $cond = " AND i.expiry_date BETWEEN CURDATE() AND ?";
    if (preg_match('/WHERE\s+1/i', $query)) {
      $query = preg_replace('/WHERE\s+1/i', 'WHERE 1' . $cond, $query, 1);
    } else {
      $query .= $cond;
    }
    $params[] = $exp_end;
    $types .= 's';
  }

  if ($report === 'stock_low') {
    // убедимся, что есть GROUP BY
    if (stripos($query, 'GROUP BY') === false) $query .= ' GROUP BY m.id';
    if ($low_threshold > 0) {
      $query .= " HAVING (m.min_stock_level - COALESCE(SUM(i.quantity), 0)) >= ?";
      $params[] = $low_threshold;
      $types .= 'i';
    } else {
      $query .= " HAVING COALESCE(SUM(i.quantity),0) < m.min_stock_level OR COALESCE(SUM(i.quantity),0) IS NULL";
    }
  }

  $query .= ' ORDER BY 1';

  if ($params) {
    $stmt = $conn->prepare($query);
    if (!$stmt) {
      // отладочная информация при ошибке синтаксиса
      die('SQL prepare error: ' . htmlspecialchars($conn->error) . '<br>Query: ' . htmlspecialchars($query));
    }
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    $stmt->close();
  } else {
    $res = $conn->query($query);
    if ($res) while ($r = $res->fetch_assoc()) $rows[] = $r;
  }
}

// Excel export
if ($export && $rows) {
  // Подготовка заголовков человекочитаемых колонок
  $headers = $headers_map[$report] ?? array_combine(array_keys($rows[0]), array_keys($rows[0]));

  if ($export_format === 'csv') {
    $filename = 'modul4_report_' . ($report?:'data') . '.csv';
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    echo "\xEF\xBB\xBF"; // BOM
    $out = fopen('php://output', 'w');
    // header row
    fputcsv($out, array_values($headers));
    foreach ($rows as $r) {
      fputcsv($out, array_map(function($v){ return is_null($v)?'':$v; }, $r));
    }
    fclose($out);
    exit;
  }

  // Excel (HTML .xls)
  $filename = 'modul4_report_' . ($report?:'data') . '.xls';
  header('Content-Type: application/vnd.ms-excel; charset=utf-8');
  header('Content-Disposition: attachment; filename="' . $filename . '"');
  // BOM
  echo "\xEF\xBB\xBF";
  echo "<html><head><meta charset=\"utf-8\"></head><body><table border=\"1\">";
  echo '<tr>';
  foreach (array_values($headers) as $h) echo '<th>' . htmlspecialchars($h) . '</th>';
  echo '</tr>';
  foreach ($rows as $r) {
    echo '<tr>';
    foreach ($r as $c) echo '<td>' . htmlspecialchars((string)$c) . '</td>';
    echo '</tr>';
  }
  // totals если нужно
  if ($show_totals) {
    $totals = [];
    foreach ($rows as $r) {
      foreach ($r as $k=>$v) {
        if (is_numeric($v)) $totals[$k] = ($totals[$k] ?? 0) + $v;
      }
    }
    if ($totals) {
      echo '<tr>'; 
      foreach (array_keys($rows[0]) as $k) {
        if (isset($totals[$k])) echo '<td><strong>' . htmlspecialchars((string)$totals[$k]) . '</strong></td>'; else echo '<td></td>';
      }
      echo '</tr>';
    }
  }
  echo '</table></body></html>';
  exit;
}

?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Отчёты</title>
  <link rel="stylesheet" href="../styles/theme-red-white.css">
</head>
<body>
  <div class="container">
    <div class="card" style="text-align:left;">
      <h2>Формирование отчётов</h2>
      <a href="../index.html" class="button secondary">Главная</a>
      <form method="get" action="reports.php" class="form-row" style="margin-bottom:12px; align-items:center;">
        <select name="report" class="input">
          <option value="">-- выберите отчёт --</option>
          <option value="patients" <?php if($report==='patients') echo 'selected'; ?>>Пациенты</option>
          <option value="hospitalizations" <?php if($report==='hospitalizations') echo 'selected'; ?>>Госпитализации</option>
          <option value="visits" <?php if($report==='visits') echo 'selected'; ?>>Амбулаторные приёмы</option>
          <option value="inventory" <?php if($report==='inventory') echo 'selected'; ?>>Склад</option>
          <option value="medications" <?php if($report==='medications') echo 'selected'; ?>>Лекарства</option>
          <option value="expiring" <?php if($report==='expiring') echo 'selected'; ?>>Просроченные лекарства</option>
          <option value="stock_low" <?php if($report==='stock_low') echo 'selected'; ?>>Низкие остатки</option>
          <option value="financial" <?php if($report==='financial') echo 'selected'; ?>>Финансовый отчет</option>
        </select>
        <select name="export_format" class="input" style="width:120px;">
          <option value="excel" <?php if($export_format==='excel') echo 'selected'; ?>>Excel (.xls)</option>
          <option value="csv" <?php if($export_format==='csv') echo 'selected'; ?>>CSV</option>
        </select>
        <input class="input" type="number" name="expiring_days" min="1" value="<?php echo htmlspecialchars($expiring_days); ?>" title="дней для просрочки" style="width:110px;" />
        <input class="input" type="number" name="low_threshold" min="0" value="<?php echo htmlspecialchars($low_threshold); ?>" title="порог дефицита" style="width:110px;" />
        <label style="display:inline-flex;align-items:center;margin-left:6px;"><input type="checkbox" name="show_totals" value="1" <?php if($show_totals) echo 'checked'; ?>> Итоги</label>
        <input class="input" type="date" name="from" value="<?php echo htmlspecialchars($from); ?>">
        <input class="input" type="date" name="to" value="<?php echo htmlspecialchars($to); ?>">
        <button class="button" type="submit">Показать</button>
  <?php if ($report): ?><a class="button secondary" href="reports.php?report=<?php echo urlencode($report); ?>&from=<?php echo urlencode($from); ?>&to=<?php echo urlencode($to); ?>&export=1">Скачать Excel</a><?php endif; ?>
      </form>

      <?php if ($rows): ?>
        <div style="overflow:auto">
          <table class="table" cellspacing="0">
            <thead>
              <tr>
                <?php
                  $headers = $headers_map[$report] ?? null;
                  if ($headers) {
                    foreach ($headers as $col => $label) echo '<th>'.htmlspecialchars($label).'</th>';
                  } else {
                    foreach (array_keys($rows[0]) as $h) echo '<th>'.htmlspecialchars($h).'</th>';
                  }
                ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($rows as $r): ?>
                <tr>
                  <?php
                    if ($headers) {
                      foreach (array_keys($headers) as $k) echo '<td>'.htmlspecialchars((string)($r[$k] ?? '')).'</td>';
                    } else {
                      foreach ($r as $c) echo '<td>'.htmlspecialchars((string)$c).'</td>';
                    }
                  ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php elseif ($report): ?>
        <p>Данных не найдено.</p>
      <?php endif; ?>

    </div>
  </div>
</body>
</html>
