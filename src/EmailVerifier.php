<?php

namespace EmailVerifier;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailVerifier
{
    /**
     * Проверяет email на валидность и существование.
     *
     * @param string $email
     * @return bool
     */
    public static function isValidEmail(string $email): bool
    {
        // Проверка формата email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Проверка DNS MX-записей
        $domain = substr(strrchr($email, "@"), 1);
        if (!checkdnsrr($domain, "MX")) {
            return false;
        }

        // Проверка существования почтового ящика через SMTP
        return self::verifyMailboxExists($email);
    }

    /**
     * Проверяет существование почтового ящика через SMTP.
     *
     * @param string $email
     * @return bool
     */
    private static function verifyMailboxExists(string $email): bool
    {
        $mail = new PHPMailer();

        try {
            // Настройка SMTP
            $mail->isSMTP();
            $mail->Host = self::getMailHost($email);
            $mail->SMTPAuth = false;
            $mail->SMTPAutoTLS = false;
            $mail->Port = 25;

            // Симуляция команд SMTP
            $mail->setFrom('verify@example.com');
            $mail->addAddress($email);

            if (!$mail->send()) {
                return false;
            }

            return true; // Почтовый ящик существует
        } catch (Exception $e) {
            // Логируем ошибки при необходимости
            return false;
        }
    }

    /**
     * Получает основной MX-хост для email.
     *
     * @param string $email
     * @return string
     */
    private static function getMailHost(string $email): string
    {
        $domain = substr(strrchr($email, "@"), 1);
        $mxRecords = [];
        getmxrr($domain, $mxRecords);

        return $mxRecords[0] ?? $domain; // Если MX-записей нет, возвращаем домен
    }
}
