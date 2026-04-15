<?php

declare(strict_types=1);

namespace MyVisits;

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use MyVisits\VisitsLog\VisitTable;

/**
 * Обработчик события OnEpilog — фиксирует каждое посещение.
 */
final readonly class VisitLogger
{
    /** Пути, исключаемые из логирования */
    private const EXCLUDED_PATHS = [
        '/bitrix/',
        '/upload/',
        '/ajax/',
    ];

    /** Расширения статических файлов */
    private const STATIC_EXTENSIONS = [
        'ico', 'png', 'jpg', 'jpeg', 'gif', 'css', 'js', 'svg', 'woff', 'woff2', 'ttf',
    ];

    /**
     * Вызывается на OnEpilog — в конце каждого запроса.
     */
    public static function onEpilog(): void
    {
        if (!Loader::includeModule('local.myvisits')) {
            return;
        }

        $request = Application::getInstance()->getContext()->getRequest();
        $pageUrl = $request->getRequestUri();

        if (self::shouldSkip($pageUrl)) {
            return;
        }

        try {
            VisitTable::add([
                'TIMESTAMP_X' => new DateTime(),
                'IP_ADDRESS'  => self::resolveIp(),
                'PAGE_URL'    => $pageUrl,
                'REFERER'     => $_SERVER['HTTP_REFERER'] ?: null,
            ]);
        } catch (\Throwable) {
            // В продакшене — логирование через \Bitrix\Main\Diag\Diag
        }
    }

    /**
     * Проверка — нужно ли пропустить логирование.
     */
    private static function shouldSkip(string $pageUrl): bool
    {
        // Системные пути
        foreach (self::EXCLUDED_PATHS as $skipPath) {
            if (str_starts_with(strtolower($pageUrl), strtolower($skipPath))) {
                return true;
            }
        }

        // POST-запросы
        if (Application::getInstance()->getContext()->getRequest()->isPost()) {
            return true;
        }

        // AJAX
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) === 'XMLHTTPREQUEST'
        ) {
            return true;
        }

        // Статические файлы
        $extension = pathinfo($pageUrl, PATHINFO_EXTENSION);
        if ($extension !== '' && in_array(strtolower($extension), self::STATIC_EXTENSIONS, true)) {
            return true;
        }

        return false;
    }

    /**
     * Определение реального IP посетителя.
     */
    private static function resolveIp(): string
    {
        return match (true) {
            !empty($_SERVER['HTTP_X_FORWARDED_FOR'])  => trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]),
            !empty($_SERVER['HTTP_X_REAL_IP'])        => $_SERVER['HTTP_X_REAL_IP'],
            !empty($_SERVER['HTTP_CF_CONNECTING_IP']) => $_SERVER['HTTP_CF_CONNECTING_IP'],
            !empty($_SERVER['REMOTE_ADDR'])           => $_SERVER['REMOTE_ADDR'],
            default                                   => '127.0.0.1',
        };
    }
}
