<?php
require_once __DIR__ . '/../db.php';

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Пациенты</title>
    <link rel="stylesheet" href="../styles/theme-red-white.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <div class="badge">Р</div>
                <div>
                    <div class="title">Администратор</div>
                </div>
            </div>
        </div>

    <div class="card" style="text-align:left;">
            <h2>Список пациентов</h2>
            <p>Ниже приведён список всех пациентов из базы данных.</p>

            <?php
            $query = "SELECT patient_id, last_name, first_name, middle_name, dob, gender, phone, address, insurance_number, blood_type, allergies, created_at FROM patients ORDER BY patient_id";
            $res = $conn->query($query);

            if ($res === false) {
                echo '<p>Ошибка запроса: ' . htmlspecialchars($conn->error) . '</p>';
            } elseif ($res->num_rows === 0) {
                echo '<p>Записей не найдено.</p>';
            } else {
                echo '<div style="overflow:auto">';
                echo '<table class="table" cellspacing="0" style="margin-left:0">';
                echo '<thead><tr>';
                echo '<th>ID</th>';
                echo '<th>Фамилия</th>';
                echo '<th>Имя</th>';
                echo '<th>Отчество</th>';
                echo '<th>ДР</th>';
                echo '<th>Пол</th>';
                echo '<th>Телефон</th>';
                echo '<th>Адрес</th>';
                echo '<th>Номер полиса</th>';
                echo '<th>Группа крови</th>';
                echo '<th>Аллергии</th>';
                echo '<th>Создано</th>';
                echo '</tr></thead><tbody>';

                while ($row = $res->fetch_assoc()) {
                    $id = htmlspecialchars($row['patient_id']);
                    $last = htmlspecialchars($row['last_name']);
                    $first = htmlspecialchars($row['first_name']);
                    $middle = htmlspecialchars($row['middle_name']);
                    $dob = $row['dob'] ? htmlspecialchars($row['dob']) : '';
                    $gender = htmlspecialchars($row['gender']);
                    $phone = htmlspecialchars($row['phone']);
                    $address = htmlspecialchars($row['address']);
                    $insurance = htmlspecialchars($row['insurance_number']);
                    $blood = htmlspecialchars($row['blood_type']);
                    $all = htmlspecialchars($row['allergies']);
                    $created = $row['created_at'] ? htmlspecialchars($row['created_at']) : '';

                    echo '<tr>';
                    echo "<td>{$id}</td>";
                    echo "<td>{$last}</td>";
                    echo "<td>{$first}</td>";
                    echo "<td>{$middle}</td>";
                    echo "<td>{$dob}</td>";
                    echo "<td>{$gender}</td>";
                    echo "<td>{$phone}</td>";
                    echo "<td>{$address}</td>";
                    echo "<td>{$insurance}</td>";
                    echo "<td>{$blood}</td>";
                    echo "<td>{$all}</td>";
                    echo "<td>{$created}</td>";
                    echo '</tr>';
                }

                echo '</tbody></table>';
                echo '</div>';
            }

    
            if ($res instanceof mysqli_result) {
                $res->free();
            }
            $conn->close();
            ?>
            <div class="form-row" style="margin-top:12px; justify-content:space-between; width:100%;">
                <div style="display:flex; gap:10px; justify-content:flex-start;">
                        <a href="add_patient.php" class="button">Добавить пациента</a>
                    </div>
                <div style="display:flex; gap:10px; justify-content:flex-end;">
          <a href="../index.html" class="button secondary">Назад</a>
        </div>
            </div>
        </div>
    </div>
</body>
</html>
