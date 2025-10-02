<?php
require_once __DIR__ . '/../db.php';

$errors = [];
$values = [
    'last_name'=>'', 'first_name'=>'', 'middle_name'=>'', 'dob'=>'', 'gender'=>'', 'phone'=>'', 'address'=>'', 'insurance_number'=>'', 'blood_type'=>'', 'allergies'=>''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем и тримим данные
    foreach ($values as $k => $v) {
        $values[$k] = isset($_POST[$k]) ? trim($_POST[$k]) : '';
    }

    // Простая валидация
    if ($values['last_name'] === '') $errors[] = 'Укажите фамилию.';
    if ($values['first_name'] === '') $errors[] = 'Укажите имя.';

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO patients (last_name, first_name, middle_name, dob, gender, phone, address, insurance_number, blood_type, allergies, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,NOW())");
        if ($stmt === false) {
            $errors[] = 'Ошибка подготовки запроса: ' . htmlspecialchars($conn->error);
        } else {
            $stmt->bind_param('ssssssssss', $values['last_name'], $values['first_name'], $values['middle_name'], $values['dob'], $values['gender'], $values['phone'], $values['address'], $values['insurance_number'], $values['blood_type'], $values['allergies']);
            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                header('Location: patients.php');
                exit;
            } else {
                $errors[] = 'Ошибка выполнения запроса: ' . htmlspecialchars($stmt->error);
                $stmt->close();
            }
        }
    }
}

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Добавить пациента</title>
        <link rel="stylesheet" href="../styles/theme-red-white.css">
    
</head>
<body>
    <div class="container">
        <div class="card" style="text-align:left;">
            <h2>Добавить пациента</h2>

            <?php if ($errors): ?>
                <div style="color:#b00020; margin-bottom:12px;">
                    <ul>
                        <?php foreach ($errors as $e): ?>
                            <li><?php echo htmlspecialchars($e); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" action="add_patient.php">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <input class="input" name="last_name" placeholder="Фамилия" value="<?php echo htmlspecialchars($values['last_name']); ?>">
                    <input class="input" name="first_name" placeholder="Имя" value="<?php echo htmlspecialchars($values['first_name']); ?>">
                    <input class="input" name="middle_name" placeholder="Отчество" value="<?php echo htmlspecialchars($values['middle_name']); ?>">
                    <input class="input" name="dob" type="date" placeholder="Дата рождения" value="<?php echo htmlspecialchars($values['dob']); ?>">
                    <select class="input" name="gender">
                        <option value="" <?php echo $values['gender']==''?'selected':''; ?>>Пол</option>
                        <option value="M" <?php echo $values['gender']=='M'?'selected':''; ?>>M</option>
                        <option value="F" <?php echo $values['gender']=='F'?'selected':''; ?>>F</option>
                    </select>
                    <input class="input" name="phone" placeholder="Телефон" value="<?php echo htmlspecialchars($values['phone']); ?>">
                    <input class="input" name="address" placeholder="Адрес" value="<?php echo htmlspecialchars($values['address']); ?>">
                    <input class="input" name="insurance_number" placeholder="Номер полиса" value="<?php echo htmlspecialchars($values['insurance_number']); ?>">
                    <input class="input" name="blood_type" placeholder="Группа крови" value="<?php echo htmlspecialchars($values['blood_type']); ?>">
                    <input class="input" name="allergies" placeholder="Аллергии" value="<?php echo htmlspecialchars($values['allergies']); ?>">
                </div>

                <div class="form-row" style="margin-top:12px; justify-content:space-between; width:100%;">
                    <div style="display:flex; gap:10px;">
                            <a href="patients.php" class="button secondary">Отмена</a>
                    </div>
                    <div>
                        <button class="button" type="submit">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
