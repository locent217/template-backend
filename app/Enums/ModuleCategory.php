<?php

namespace App\Enums;

enum ModuleCategory: string
{
    case MANAGEMENT = 'management';
    case TRANSACTION = 'transaction';
    case REPORT = 'report';
    case SETTINGS = 'settings';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::MANAGEMENT => 'Management',
            self::TRANSACTION => 'Transaction',
            self::REPORT => 'Reports',
            self::SETTINGS => 'Settings',
            self::OTHER => 'Others',
        };
    }

    public static function options(): array
    {
        return array_map(
            static fn (self $category): array => [
                'value' => $category->value,
                'label' => $category->label(),
            ],
            self::cases(),
        );
    }
}
