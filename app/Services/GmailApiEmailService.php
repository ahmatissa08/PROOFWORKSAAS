<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class GmailApiEmailService
{
    public function send(string|array $to, string $subject, string $html, ?string $text = null): void
    {
        $clientId = (string) config('services.gmail_api.client_id');
        $clientSecret = (string) config('services.gmail_api.client_secret');
        $refreshToken = (string) config('services.gmail_api.refresh_token');
        $from = (string) config('services.gmail_api.from');

        if ($clientId === '' || $clientSecret === '' || $refreshToken === '' || $from === '') {
            throw new RuntimeException('Gmail API credentials are not configured.');
        }

        $tokenResponse = Http::asForm()
            ->timeout((int) config('services.gmail_api.timeout', 15))
            ->post('https://oauth2.googleapis.com/token', [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token',
            ]);

        if (! $tokenResponse->successful() || ! $tokenResponse->json('access_token')) {
            throw new RuntimeException('Gmail API token refresh failed: '.$tokenResponse->body());
        }

        $response = Http::withToken($tokenResponse->json('access_token'))
            ->acceptJson()
            ->asJson()
            ->timeout((int) config('services.gmail_api.timeout', 15))
            ->post('https://gmail.googleapis.com/gmail/v1/users/me/messages/send', [
                'raw' => $this->base64UrlEncode($this->buildMimeMessage(
                    $from,
                    (array) $to,
                    $subject,
                    $text ?: trim(strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $html))),
                    $html
                )),
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Gmail API send failed: '.$response->body());
        }
    }

    private function buildMimeMessage(string $from, array $to, string $subject, string $text, string $html): string
    {
        $boundary = 'proofwork_'.bin2hex(random_bytes(12));
        $lines = [
            'From: '.$this->cleanHeader($from),
            'To: '.$this->cleanHeader(implode(', ', $to)),
            'Subject: '.$this->encodeHeader($subject),
            'MIME-Version: 1.0',
            'Content-Type: multipart/alternative; boundary="'.$boundary.'"',
            '',
            '--'.$boundary,
            'Content-Type: text/plain; charset=UTF-8',
            'Content-Transfer-Encoding: 8bit',
            '',
            $text,
            '',
            '--'.$boundary,
            'Content-Type: text/html; charset=UTF-8',
            'Content-Transfer-Encoding: 8bit',
            '',
            $html,
            '',
            '--'.$boundary.'--',
        ];

        return implode("\r\n", $lines);
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function cleanHeader(string $value): string
    {
        return trim(str_replace(["\r", "\n"], '', $value));
    }

    private function encodeHeader(string $value): string
    {
        $value = $this->cleanHeader($value);

        return function_exists('mb_encode_mimeheader')
            ? mb_encode_mimeheader($value, 'UTF-8', 'B', "\r\n")
            : $value;
    }
}
