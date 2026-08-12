<?php

namespace App\Support;

final class EditorSpacing
{
    public const TAB_SPACES = 4;

    public static function normalizeText(string $text): string
    {
        return str_replace("\t", str_repeat(' ', self::TAB_SPACES), $text);
    }

    public static function normalizeHtml(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $html = self::normalizeText($html);

        return preg_replace(
            '/(?:&#0*9;|&#x0*9;|&Tab;)/i',
            str_repeat(' ', self::TAB_SPACES),
            $html
        ) ?? $html;
    }
}
