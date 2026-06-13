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
            'gmail_api' => app(GmailApiEmailService::class)->send($user->email, $subject, $html, $text),
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

}
