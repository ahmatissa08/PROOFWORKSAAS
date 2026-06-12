<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use RuntimeException;

class VerificationEmailService
{
    public function send(User $user): void
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $subject = 'Verify your ProofWork email';
        $html = view('emails.verify-email', [
            'user' => $user,
            'verificationUrl' => $verificationUrl,
        ])->render();
        $text = "Verify your ProofWork email:\n\n{$verificationUrl}\n\nThis link expires in 60 minutes.";

        match (config('services.verification_email.provider', 'resend')) {
            'gmail_api' => $this->sendWithGmailApi($user, $subject, $html, $text),
            default => $this->sendWithResend($user, $subject, $html, $text),
        };
    }

    private function sendWithResend(User $user, string $subject, string $html, string $text): void
    {
        $apiKey = (string) config('services.resend.key');

        if ($apiKey === '') {
            throw new RuntimeException('Resend API key is not configured.');
        }

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->asJson()
            ->timeout((int) config('services.resend.timeout', 15))
            ->post('https://api.resend.com/emails', [
                'from' => config('services.resend.from'),
                'to' => [$user->email],
                'subject' => $subject,
                'html' => $html,
                'text' => $text,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Resend email failed: '.$response->body());
        }
    }

    private function sendWithGmailApi(User $user, string $subject, string $html, string $text): void
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
                'raw' => $this->base64UrlEncode($this->buildMimeMessage($from, $user->email, $subject, $text, $html)),
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Gmail API send failed: '.$response->body());
        }
    }

    private function buildMimeMessage(string $from, string $to, string $subject, string $text, string $html): string
    {
        $boundary = 'proofwork_'.bin2hex(random_bytes(12));
        $lines = [
            'From: '.$this->cleanHeader($from),
            'To: '.$this->cleanHeader($to),
            'Subject: '.$this->cleanHeader($subject),
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
}
