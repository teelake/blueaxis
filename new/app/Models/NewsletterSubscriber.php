<?php

declare(strict_types=1);

namespace App\Models;

final class NewsletterSubscriber extends Model
{
    public static function subscribe(string $email): bool
    {
        $existing = self::findByEmail($email);
        if ($existing) {
            if ($existing['status'] === 'unsubscribed') {
                $stmt = self::db()->prepare(
                    "UPDATE newsletter_subscribers SET status = 'active' WHERE id = :id"
                );
                $stmt->execute(['id' => $existing['id']]);
            }
            return true;
        }

        $stmt = self::db()->prepare(
            'INSERT INTO newsletter_subscribers (email) VALUES (:email)'
        );
        $stmt->execute(['email' => $email]);
        return true;
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM newsletter_subscribers WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public static function countActive(): int
    {
        return (int) self::db()->query(
            "SELECT COUNT(*) FROM newsletter_subscribers WHERE status = 'active'"
        )->fetchColumn();
    }
}
