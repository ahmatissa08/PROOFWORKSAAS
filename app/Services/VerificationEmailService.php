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
        $apiKey = (string) config('services.resend.key');

        if ($apiKey === '') {
            throw new RuntimeException('Resend API key is not configured.');
        }

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->asJson()
            ->timeout((int) config('services.resend.timeout', 15))
            ->post('https://api.resend.com/emails', [
                'from' => config('services.resend.from'),
                'to' => [$user->email],
                'subject' => 'Verify your ProofWork email',
                'html' => view('emails.verify-email', [
                    'user' => $user,
                    'verificationUrl' => $verificationUrl,
                ])->render(),
                'text' => "Verify your ProofWork email:\n\n{$verificationUrl}\n\nThis link expires in 60 minutes.",
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Resend email failed: '.$response->body());
        }
    }
}
