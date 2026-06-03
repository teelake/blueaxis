<?php

declare(strict_types=1);

namespace App\Models;

final class Setting extends Model
{
    public static function get(string $key, ?string $default = null): ?string
    {
        $stmt = self::db()->prepare('SELECT value FROM settings WHERE `key` = :key LIMIT 1');
        $stmt->execute(['key' => $key]);
        $row = $stmt->fetch();
        return $row ? (string) $row['value'] : $default;
    }

    /** @return array<string, string> */
    public static function allByGroup(string $group): array
    {
        $stmt = self::db()->prepare('SELECT `key`, value FROM settings WHERE group_name = :group');
        $stmt->execute(['group' => $group]);
        $out = [];
        foreach ($stmt->fetchAll() as $row) {
            $out[$row['key']] = (string) $row['value'];
        }
        return $out;
    }

    public static function set(string $key, ?string $value, string $type = 'text', string $group = 'general'): void
    {
        $stmt = self::db()->prepare(
            'INSERT INTO settings (`key`, value, type, group_name) VALUES (:key, :value, :type, :group)
             ON DUPLICATE KEY UPDATE value = VALUES(value), type = VALUES(type), group_name = VALUES(group_name)'
        );
        $stmt->execute(['key' => $key, 'value' => $value, 'type' => $type, 'group' => $group]);
    }
}
