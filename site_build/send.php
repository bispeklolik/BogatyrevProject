<?php
/* ─────────────────────────────────────────────────────────────
   Обработчик формы заявки. Принимает данные формы и шлёт письмо.

   НАСТРОЙКА ПЕРЕД ЗАПУСКОМ (см. ЗАПУСК_ПОШАГОВО.md):
   1. Впишите в $to адрес, КУДА должны приходить заявки.
   2. Впишите в $from адрес-отправитель НА ВАШЕМ домене
      (важно для доставки — иначе письма уходят в спам).
   ───────────────────────────────────────────────────────────── */

$to      = "hello@matveybogatyrev.ru";   // ← КУДА приходят заявки (ваша почта)
$from    = "noreply@matveybogatyrev.ru";  // ← отправитель на вашем домене
$subject = "Заявка с сайта — собеседование";

// Принимаем только отправку формы (POST)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit;
}

// Honeypot: скрытое поле должно быть пустым. Заполнено — это бот.
if (!empty($_POST["website"])) {
    header("Location: thanks.html"); // тихо имитируем успех
    exit;
}

// Согласие на обработку данных обязательно
if (empty($_POST["consent"])) {
    header("Location: index.html#booking");
    exit;
}

// Убираем переносы строк из коротких полей (защита заголовков письма)
function clean_line($v) {
    $v = isset($v) ? trim((string)$v) : "";
    return str_replace(array("\r", "\n", "%0a", "%0d", "%0A", "%0D"), " ", $v);
}

$name    = clean_line($_POST["name"]    ?? "");
$contact = clean_line($_POST["contact"] ?? "");
$age     = clean_line($_POST["age"]     ?? "");
$format  = clean_line($_POST["format"]  ?? "");
$request = trim((string)($_POST["request"] ?? ""));
$tried   = trim((string)($_POST["tried"]   ?? ""));

// Минимальная проверка обязательных полей
if ($name === "" || $contact === "" || $request === "") {
    header("Location: index.html#booking");
    exit;
}

// Тело письма
$body  = "Новая заявка с сайта\n";
$body .= "========================================\n";
$body .= "Имя:      " . $name . "\n";
$body .= "Контакт:  " . $contact . "\n";
$body .= "Возраст:  " . ($age !== "" ? $age : "—") . "\n";
$body .= "Формат:   " . ($format !== "" ? $format : "не выбран") . "\n";
$body .= "----------------------------------------\n";
$body .= "Запрос:\n" . $request . "\n\n";
$body .= "Что пробовал:\n" . ($tried !== "" ? $tried : "—") . "\n";
$body .= "========================================\n";
$body .= "Дата: " . date("d.m.Y H:i") . "\n";

// Заголовки письма
$reply = filter_var($contact, FILTER_VALIDATE_EMAIL) ? $contact : $from;
$headers  = "From: " . $from . "\r\n";
$headers .= "Reply-To: " . $reply . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Тема в UTF-8 (чтобы кириллица не «ломалась» в почте)
$encoded_subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";

@mail($to, $encoded_subject, $body, $headers);

// В любом случае ведём человека на страницу благодарности
header("Location: thanks.html");
exit;
