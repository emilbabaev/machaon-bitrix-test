<?php

declare(strict_types=1);

namespace MyVisits\VisitsLog;

use Bitrix\Main\Entity;
use Bitrix\Main\Type\DateTime;

/**
 * ORM-модель таблицы b_visits_log.
 */
final class VisitTable extends Entity\DataManager
{
    public static function getTableName(): string
    {
        return 'b_visits_log';
    }

    /**
     * @return list<Entity\Field>
     */
    public static function getMap(): array
    {
        return [
            new Entity\IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                ]
            ),
            new Entity\DatetimeField(
                'TIMESTAMP_X',
                [
                    'required' => true,
                ]
            ),
            new Entity\StringField(
                'IP_ADDRESS',
                [
                    'required' => true,
                    'size' => 45,
                ]
            ),
            new Entity\StringField(
                'PAGE_URL',
                [
                    'required' => true,
                    'size' => 500,
                ]
            ),
            new Entity\StringField(
                'REFERER',
                [
                    'size' => 500,
                ]
            ),
        ];
    }
}
