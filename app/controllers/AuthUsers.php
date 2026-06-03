<?php
// app/controllers/AuthUsers.php
// Упрощённая версия, работающая с полем `role` вместо `admin`


$errMsg = '';

// Загружаем функции для работы с БД (должны быть доступны selectOne, insert и т.д.)
require_once __DIR__ . '/../database/db.php';

// Обработка отправленных форм
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ========================
    // ВХОД
    // ========================
    if (isset($_POST['button-signin'])) {
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            $errMsg = "Не все поля заполнены!";
        } else {
            $user = selectOne('users', ['email' => $email]);

            if ($user) {
                // Проверка пароля: старые MD5 и современные password_hash
                $passwordValid = false;
                if (strlen($user['password']) === 32 && ctype_xdigit($user['password'])) {
                    $passwordValid = (md5($password) === $user['password']);
                } else {
                    $passwordValid = password_verify($password, $user['password']);
                }

                if ($passwordValid) {
                    // Записываем сессию
                    $_SESSION['id']    = $user['id'];
                    $_SESSION['name']  = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['admin'] = ($user['role'] === 'admin') ? 1 : 0;

                    // Редирект
                    if ($_SESSION['admin']) {
                        header('Location: ' . BASE_URL . 'admin/post/index.php');
                    } else {
                        header('Location: ' . BASE_URL);
                    }
                    exit;
                } else {
                    $errMsg = "Почта либо пароль введены неверно!";
                }
            } else {
                $errMsg = "Почта либо пароль введены неверно!";
            }
        }
    }

    // ========================
    // РЕГИСТРАЦИЯ
    // ========================
    elseif (isset($_POST['button-signup'])) {
        $name  = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $passF = trim($_POST['pass_first'] ?? '');
        $passS = trim($_POST['pass_second'] ?? '');

        if ($name === '' || $email === '' || $passF === '') {
            $errMsg = "Не все поля заполнены!";
        } elseif (mb_strlen($name, 'UTF-8') < 3) {
            $errMsg = "Имя должно быть более 3-х символов!";
        } elseif ($passF !== $passS) {
            $errMsg = "Пароли не совпадают!";
        } else {
            $existence = selectOne('users', ['email' => $email]);
            if ($existence) {
                $errMsg = "Пользователь с такой почтой уже зарегистрирован!";
            } else {
                // Хэш пароля (используем password_hash, не MD5)
                $hashedPassword = password_hash($passF, PASSWORD_DEFAULT);
                $newUser = [
                    'username' => $name,
                    'email'    => $email,
                    'password' => $hashedPassword,
                    'role'     => 'user'        // Обычный пользователь
                ];

                $userId = insert('users', $newUser);
                if ($userId) {
                    $user = selectOne('users', ['id' => $userId]);
                    $_SESSION['id']    = $user['id'];
                    $_SESSION['name']  = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['admin'] = 0;       // Точно не админ

                    header('Location: ' . BASE_URL);
                    exit;
                } else {
                    $errMsg = "Ошибка при регистрации. Попробуйте позже.";
                }
            }
        }
    }
}

// Если форма не отправлена или произошла ошибка,
// переменная $errMsg будет содержать сообщение, которое можно вывести в signup.php/signin.php