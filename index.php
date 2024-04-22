<?php
$json = file_get_contents('https://cdn.jsdelivr.net/gh/andr-04/inputmask-multi@master/data/phone-codes.json');
$phoneNumbers = json_decode($json, true);

// Функция для определения страны по номеру телефона
function phoneCountry($phoneNumber, $phoneNumbers) {
    // Если отсутствует '+', то добавляем
    if (!preg_match('/\+/', $phoneNumber)) {
        $phoneNumber = "+" . $phoneNumber;
    }
    // Если есть пробел перед скобкой, то убираем
    if (preg_match('/\s\(/', $phoneNumber)) {
        $phoneNumber = str_replace(' (','(', $phoneNumber);
    }
    // Если есть пробел после скобки, то убираем
    if (preg_match('/\)\s/', $phoneNumber)) {
        $phoneNumber = str_replace(') ',')', $phoneNumber);
    }
    foreach ($phoneNumbers as $countryInfo) {
        // Экранируем символ '+'
        $mask = str_replace('+', '\+', $countryInfo['mask']);
        // Заменяем '(' на '.'
        $mask = str_replace('(', '.', $mask);
        // Заменяем ')' на '.'
        $mask = str_replace(')', '.', $mask);
        // Заменяем '-' на '.'
        $mask = str_replace('-', '.', $mask);
        // Заменяем '#' на '\d'
        $mask = str_replace('#', '\d', $mask);
        // Преобразуем маску в регулярное выражение
        $mask = "/^{$mask}$/";
        if (preg_match($mask, $phoneNumber)) {
            return $countryInfo['name_ru']; // Возвращаем русское название страны
        }
    }
    return 'Неизвестно';
}

// Пример использования
$phoneNumber1 = '+375(29)123-45-67';
$phoneNumber2 = '+7 (495) 123 45 67';
$phoneNumber3 = '7 777 123-45-67';

echo "<h3>Номер $phoneNumber1 принадлежит стране: " . phoneCountry($phoneNumber1, $phoneNumbers) . "</h3>";
echo "<h3>Номер $phoneNumber2 принадлежит стране: " . phoneCountry($phoneNumber2, $phoneNumbers) . "</h3>";
echo "<h3>Номер $phoneNumber3 принадлежит стране: " . phoneCountry($phoneNumber3, $phoneNumbers) . "</h3>";

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phoneNumber = $_POST['phone']; // Получаем номер телефона из формы
    $country = phoneCountry($phoneNumber, $phoneNumbers);
    echo "<h3>Номер $phoneNumber принадлежит стране: $country</h3>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grizzly Digital - ТЗ Азявчиков Г.М.</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Стили для попапа */
        .cookie-popup {
            position: fixed;
            right: 20px;
            bottom: 20px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: none;
        }
    </style>
</head>
<body>
<form action="" method="post">
    <label for="phone"><b>Введите номер телефона:<b/></label>
    <input type="text" id="phone" name="phone" placeholder="+375(29)123-45-67" required>
    <button class="btn btn-primary" type="submit">Определить страну</button>
</form>
<div class="cookie-popup" id="cookiePopup">
    На этой странице используются куки. Нажмите "Принять", чтобы закрыть это уведомление.
    <button class="btn btn-primary" onclick="acceptCookies()">Принять</button>
    <button class="btn btn-secondary" onclick="closePopup()">Закрыть</button>
</div>
<script>
    // Проверяем, был ли попап уже показан сегодня
    const lastPopupDate = localStorage.getItem('lastPopupDate');
    const today = new Date().toLocaleDateString();
    if (lastPopupDate !== today) {
        document.getElementById('cookiePopup').style.display = 'block';
    }

    // Закрытие попапа
    function closePopup() {
        document.getElementById('cookiePopup').style.display = 'none';
    }

    // Принятие информации о куки
    function acceptCookies() {
        closePopup();
        localStorage.setItem('lastPopupDate', today);
    }
</script>
</body>
</html>
