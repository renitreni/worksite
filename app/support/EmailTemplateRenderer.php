<?php

namespace App\Support;

class EmailTemplateRenderer
{
    public static function render(string $subject, ?string $bodyText, ?string $bodyHtml, array $data = []): array
    {
        $replaceMap = [];

        foreach ($data as $key => $value) {
            $replaceMap['{' . $key . '}'] = (string) $value;
            $replaceMap['{{' . $key . '}}'] = (string) $value;
        }

        $finalSubject = strtr((string) $subject, $replaceMap);
        $finalText = strtr((string) ($bodyText ?? ''), $replaceMap);
        $rawHtml = $bodyHtml ? strtr((string) $bodyHtml, $replaceMap) : null;

        $contentHtml = filled($rawHtml)
            ? $rawHtml
            : self::textToHtml($finalText);

        $finalHtml = view('emails.layouts.app', [
            'subjectLine' => $finalSubject,
            'contentHtml' => $contentHtml,
        ])->render();

        return [
            'subject' => $finalSubject,
            'body_text' => $finalText,
            'body_html' => $finalHtml,
            'content_html' => $contentHtml,
        ];
    }

    protected static function textToHtml(string $text): string
    {
        $escaped = e($text);

        $escaped = preg_replace_callback(
            '/(https?:\/\/[^\s<]+)/i',
            fn ($m) => '<a href="' . e($m[1]) . '" style="color:#059669;text-decoration:underline;">' . e($m[1]) . '</a>',
            $escaped
        );

        $paragraphs = preg_split("/\r\n\r\n|\n\n|\r\r/", trim($escaped)) ?: [];

        $html = collect($paragraphs)
            ->map(function ($paragraph) {
                $paragraph = nl2br(trim($paragraph));
                return '<p style="margin:0 0 16px 0;line-height:1.7;color:#1f2937;">' . $paragraph . '</p>';
            })
            ->implode('');

        return $html !== '' ? $html : '<p style="margin:0;line-height:1.7;color:#1f2937;"></p>';
    }
}