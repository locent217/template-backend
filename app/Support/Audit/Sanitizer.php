<?php
namespace App\Support\Audit;

use Illuminate\Http\UploadedFile;

final class Sanitizer
{
    private const SECRET = '/pass(word|phrase)?|secret|token|authorization|cookie|csrf|xsrf|otp|pin|api[_-]?key|credential|session|private[_-]?key|bank[_-]?account|tin|birth|address|phone|contact|email/i';

    public static function clean(mixed $data, int $depth = 0): mixed
    {
        if ($depth > 5) return '[DEPTH_LIMIT]';
        if ($data instanceof UploadedFile) return ['upload' => true, 'size' => $data->getSize(), 'mime' => $data->getMimeType()];
        if (!is_array($data)) return is_scalar($data) || $data === null ? $data : '[OBJECT]';
        $result = [];
        foreach (array_slice($data, 0, 100, true) as $key => $value) {
            $result[$key] = preg_match(self::SECRET, (string) $key) ? '[REDACTED]' : self::clean($value, $depth + 1);
        }
        return $result;
    }

    public static function bounded(mixed $data): array
    {
        $safe = self::clean($data);
        $json = json_encode($safe, JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR);
        if ($json === false || strlen($json) > config('audit.max_payload_bytes', 8192)) return ['_notice' => 'Payload omitted: exceeds audit size limit'];
        return is_array($safe) ? $safe : ['value' => $safe];
    }
}
