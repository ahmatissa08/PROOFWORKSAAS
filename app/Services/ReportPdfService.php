<?php

namespace App\Services;

use App\Models\Report;
use TCPDF;

class ReportPdfService
{
    /**
     * Generate a PDF for the report with QR code verification
     */
    public function generate(Report $report): string
    {
        $report->loadMissing(['user', 'project', 'client', 'entries']);
        $this->prepareTcpdfCache();

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 20);

        $pdf->SetCreator('ProofWork');
        $pdf->SetAuthor($report->user->name);
        $pdf->SetTitle($report->title);
        $pdf->SetSubject('Proof of Work Report');
        $this->applyDigitalSignature($pdf);

        // Generate content pages
        $this->addCoverPage($pdf, $report);
        $this->addSummaryPage($pdf, $report);
        $this->addEntriesPages($pdf, $report);
        $this->addVerificationPage($pdf, $report);

        $filename = 'report_'.$report->id.'_'.now()->format('Y-m-d_His_u').'.pdf';
        $path = storage_path('app/pdfs/'.$filename);

        if (! is_dir(storage_path('app/pdfs'))) {
            mkdir(storage_path('app/pdfs'), 0755, true);
        }

        $pdf->Output($path, 'F');

        return $path;
    }

    /**
     * Cover page with report title and branding
     */
    private function addCoverPage(TCPDF $pdf, Report $report): void
    {
        $pdf->AddPage();

        // Background color (dark #0c0c0e)
        $pdf->SetFillColor(12, 12, 14);
        $pdf->Rect(0, 0, 210, 297, 'F');

        // Accent bar at top (#e8a325)
        $pdf->SetFillColor(232, 163, 37);
        $pdf->Rect(0, 0, 210, 3, 'F');

        // Brand
        $pdf->SetFont('helvetica', 'I', 14);
        $pdf->SetTextColor(242, 240, 235);
        $pdf->SetXY(15, 20);
        $pdf->Cell(0, 10, 'ProofWork', 0, 1, 'L');

        // Title
        $pdf->SetFont('helvetica', 'B', 24);
        $pdf->SetTextColor(242, 240, 235);
        $pdf->SetXY(15, 60);
        $pdf->MultiCell(180, 12, $report->title, 0, 'L');

        // Period
        $pdf->SetFont('helvetica', '', 12);
        $pdf->SetTextColor(160, 158, 154);
        $pdf->SetXY(15, 100);
        $pdf->Cell(0, 8, $report->periodLabel(), 0, 1, 'L');

        // Meta info
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(90, 88, 85);
        $pdf->SetXY(15, 115);
        $pdf->Cell(0, 6, 'Generated: '.now()->format('F d, Y \\a\\t H:i'), 0, 1, 'L');
        $pdf->Cell(0, 6, 'Prepared by: '.$report->user->name, 0, 1, 'L');

        if ($report->project) {
            $pdf->Cell(0, 6, 'Project: '.$report->project->name, 0, 1, 'L');
        }

        if ($report->client) {
            $pdf->Cell(0, 6, 'Client: '.$report->client->name, 0, 1, 'L');
        }

        // Verification badge box
        $pdf->SetFillColor(19, 19, 22);
        $pdf->SetDrawColor(36, 36, 40);
        $pdf->RoundedRect(15, 180, 180, 50, 3, '1111', 'DF');

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetTextColor(39, 201, 63);
        $pdf->SetXY(25, 190);
        $pdf->Cell(0, 8, 'VERIFIED REPORT', 0, 1, 'L');

        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(90, 88, 85);
        $pdf->SetXY(25, 200);
        $pdf->MultiCell(160, 5,
            'This report is cryptographically verifiable. Scan the QR code on the last page to verify online, '.
            'or compare the document hash below with the online version.',
            0, 'L'
        );

        // Document hash
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(90, 88, 85);
        $pdf->SetXY(25, 218);
        $pdf->Cell(0, 5, 'Verification Hash: '.$report->verificationHash(), 0, 1, 'L');
    }

    /**
     * Summary page with stats and AI summary
     */
    private function addSummaryPage(TCPDF $pdf, Report $report): void
    {
        $pdf->AddPage();

        // Background
        $pdf->SetFillColor(12, 12, 14);
        $pdf->Rect(0, 0, 210, 297, 'F');

        // Header
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(242, 240, 235);
        $pdf->SetXY(15, 20);
        $pdf->Cell(0, 10, 'Summary', 0, 1, 'L');

        $pdf->SetDrawColor(36, 36, 40);
        $pdf->Line(15, 32, 195, 32);

        // Stats grid
        $bySource = $report->entries->groupBy('source');
        $stats = [
            ['Total Items', $report->entries->count(), [232, 163, 37]],
            ['Code Changes', ($bySource['github'] ?? collect())->count(), [74, 158, 255]],
            ['Tasks Done', ($bySource['linear'] ?? collect())->count() + ($bySource['notion'] ?? collect())->count(), [39, 201, 63]],
            ['Meetings', ($bySource['google_calendar'] ?? collect())->count(), [232, 92, 58]],
        ];

        $y = 45;
        foreach ($stats as $stat) {
            $pdf->SetFillColor(19, 19, 22);
            $pdf->SetDrawColor(36, 36, 40);
            $pdf->RoundedRect(15, $y, 42, 35, 2, '1111', 'DF');

            $pdf->SetFont('helvetica', 'B', 18);
            $pdf->SetTextColor($stat[2][0], $stat[2][1], $stat[2][2]);
            $pdf->SetXY(15, $y + 5);
            $pdf->Cell(42, 10, (string) $stat[1], 0, 0, 'C');

            $pdf->SetFont('helvetica', '', 7);
            $pdf->SetTextColor(90, 88, 85);
            $pdf->SetXY(15, $y + 18);
            $pdf->Cell(42, 6, strtoupper($stat[0]), 0, 0, 'C');

            $y += 42;
        }

        // AI Summary box
        if ($report->ai_summary) {
            $pdf->SetFillColor(19, 19, 22);
            $pdf->SetDrawColor(232, 163, 37);
            $pdf->RoundedRect(65, 45, 130, 80, 3, '1111', 'DF');

            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetTextColor(232, 163, 37);
            $pdf->SetXY(75, 52);
            $pdf->Cell(0, 6, 'AI SUMMARY', 0, 1, 'L');

            $pdf->SetFont('helvetica', 'I', 10);
            $pdf->SetTextColor(160, 158, 154);
            $pdf->SetXY(75, 62);
            $pdf->MultiCell(110, 5, $report->ai_summary, 0, 'L');
        }
    }

    /**
     * Entries pages grouped by source
     */
    private function addEntriesPages(TCPDF $pdf, Report $report): void
    {
        $bySource = $report->entries->groupBy('source');

        $sourceConfig = [
            'github' => 'GitHub',
            'linear' => 'Linear',
            'notion' => 'Notion',
            'google_calendar' => 'Google Calendar',
            'manual' => 'Manual Entries',
        ];

        foreach ($bySource as $source => $entries) {
            $pdf->AddPage();

            // Background
            $pdf->SetFillColor(12, 12, 14);
            $pdf->Rect(0, 0, 210, 297, 'F');

            // Section header
            $label = $sourceConfig[$source] ?? ucfirst($source);

            $pdf->SetFillColor(19, 19, 22);
            $pdf->SetDrawColor(36, 36, 40);
            $pdf->RoundedRect(15, 15, 180, 12, 2, '1111', 'DF');

            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->SetTextColor(242, 240, 235);
            $pdf->SetXY(20, 18);
            $pdf->Cell(0, 6, $label, 0, 1, 'L');

            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(90, 88, 85);
            $pdf->SetXY(160, 18);
            $pdf->Cell(30, 6, $entries->count().' items', 0, 0, 'R');

            $y = 35;
            foreach ($entries as $entry) {
                if ($y > 270) {
                    $pdf->AddPage();
                    $pdf->SetFillColor(12, 12, 14);
                    $pdf->Rect(0, 0, 210, 297, 'F');
                    $y = 20;
                }

                // Entry card
                $pdf->SetFillColor(19, 19, 22);
                $pdf->SetDrawColor(36, 36, 40);
                $pdf->RoundedRect(15, $y, 180, 22, 2, '1111', 'DF');

                // Type tag
                $pdf->SetFillColor(24, 24, 28);
                $pdf->SetDrawColor(46, 46, 52);
                $pdf->RoundedRect(20, $y + 3, 30, 5, 1, '1111', 'DF');

                $pdf->SetFont('helvetica', '', 6);
                $pdf->SetTextColor(90, 88, 85);
                $pdf->SetXY(20, $y + 3);
                $pdf->Cell(30, 5, strtoupper($entry->type), 0, 0, 'C');

                // Title
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->SetTextColor(242, 240, 235);
                $pdf->SetXY(20, $y + 10);
                $pdf->Cell(0, 5, $entry->title, 0, 1, 'L');

                // Date
                if ($entry->occurred_at) {
                    $pdf->SetFont('helvetica', '', 7);
                    $pdf->SetTextColor(90, 88, 85);
                    $pdf->SetXY(160, $y + 10);
                    $pdf->Cell(30, 5, $entry->occurred_at->format('M d, Y'), 0, 0, 'R');
                }

                $y += 28;
            }
        }
    }

    /**
     * Verification page with QR code
     */
    private function addVerificationPage(TCPDF $pdf, Report $report): void
    {
        $pdf->AddPage();

        // Background
        $pdf->SetFillColor(12, 12, 14);
        $pdf->Rect(0, 0, 210, 297, 'F');

        // Header
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(242, 240, 235);
        $pdf->SetXY(15, 20);
        $pdf->Cell(0, 10, 'Verification', 0, 1, 'L');

        $pdf->SetDrawColor(36, 36, 40);
        $pdf->Line(15, 32, 195, 32);

        // QR Code
        $qrUrl = $report->shareUrl();
        $style = [
            'border' => false,
            'padding' => 2,
            'fgcolor' => [232, 163, 37],
            'bgcolor' => [12, 12, 14],
        ];

        $pdf->write2DBarcode(
            $qrUrl,
            'QRCODE,H',
            65,
            50,
            80,
            80,
            $style,
            'N'
        );

        // URL below QR
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(90, 88, 85);
        $pdf->SetXY(15, 140);
        $pdf->Cell(180, 6, $qrUrl, 0, 1, 'C');

        // Instructions
        $pdf->SetFont('helvetica', 'I', 10);
        $pdf->SetTextColor(160, 158, 154);
        $pdf->SetXY(15, 160);
        $pdf->MultiCell(180, 6,
            'Scan this QR code with your phone to verify this report online. '.
            'This ensures the document matches the official record stored on ProofWork.',
            0, 'C'
        );

        // Verification details box
        $pdf->SetFillColor(19, 19, 22);
        $pdf->SetDrawColor(36, 36, 40);
        $pdf->RoundedRect(15, 200, 180, 60, 3, '1111', 'DF');

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetTextColor(232, 163, 37);
        $pdf->SetXY(25, 210);
        $pdf->Cell(0, 6, 'VERIFICATION DETAILS', 0, 1, 'L');

        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(90, 88, 85);
        $pdf->SetXY(25, 220);
        $pdf->Cell(0, 5, 'Verification Method: SHA-256 Content Hash', 0, 1, 'L');
        $pdf->Cell(0, 5, 'Generated: '.now()->format('Y-m-d H:i:s \\U\\T\\C'), 0, 1, 'L');
        $pdf->Cell(0, 5, 'Verification Hash: '.$report->verificationHash(), 0, 1, 'L');
        $pdf->Cell(0, 5, 'Report ID: '.$report->id, 0, 1, 'L');
        $pdf->Cell(0, 5, 'Share Token: '.$report->share_token, 0, 1, 'L');
    }

    private function applyDigitalSignature(TCPDF $pdf): void
    {
        $paths = $this->certificatePaths();

        if (! $this->certificateFilesExist($paths) && app()->environment(['local', 'testing'])) {
            $this->generateSelfSignedCertificate($paths);
        }

        if (! $this->certificateFilesExist($paths)) {
            if (app()->environment('production')) {
                throw new \RuntimeException('PDF signature certificate is not configured.');
            }

            return;
        }

        $certificate = 'file://'.$paths['cert'];
        $privateKey = 'file://'.$paths['key'];
        $info = [
            'Name' => 'ProofWork',
            'Location' => config('app.url'),
            'Reason' => 'ProofWork report integrity verification',
            'ContactInfo' => config('proofwork.admin_email'),
        ];

        $pdf->setSignature(
            $certificate,
            $privateKey,
            (string) config('proofwork.pdf_signature.private_key_password', ''),
            '',
            2,
            $info
        );
    }

    private function prepareTcpdfCache(): void
    {
        $path = storage_path('framework/cache/tcpdf');

        if (! is_dir($path)) {
            mkdir($path, 0755, true);
        }

        @chmod($path, 0755);

        if (! is_writable($path)) {
            throw new \RuntimeException("TCPDF cache directory is not writable: {$path}");
        }

        if (! defined('K_PATH_CACHE')) {
            define('K_PATH_CACHE', str_replace('\\', '/', $path).'/');
        }

        if (defined('K_PATH_CACHE') && ! is_dir(K_PATH_CACHE)) {
            mkdir(K_PATH_CACHE, 0755, true);
        }
    }

    private function certificatePaths(): array
    {
        return [
            'cert' => config('proofwork.pdf_signature.certificate_path')
                ?: storage_path('app/certificates/proofwork.crt'),
            'key' => config('proofwork.pdf_signature.private_key_path')
                ?: storage_path('app/certificates/proofwork.key'),
        ];
    }

    private function certificateFilesExist(array $paths): bool
    {
        return is_file($paths['cert']) && is_file($paths['key']);
    }

    private function generateSelfSignedCertificate(array $paths): void
    {
        if (! extension_loaded('openssl')) {
            return;
        }

        $directory = dirname($paths['cert']);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $privateKey = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'digest_alg' => 'sha256',
        ]);

        if (! $privateKey) {
            return;
        }

        $subject = [
            'countryName' => 'US',
            'organizationName' => 'ProofWork',
            'organizationalUnitName' => 'Verification',
            'commonName' => parse_url(config('app.url'), PHP_URL_HOST) ?: 'proofwork.local',
            'emailAddress' => config('proofwork.admin_email') ?: 'verify@proofwork.local',
        ];

        $csr = openssl_csr_new($subject, $privateKey, ['digest_alg' => 'sha256']);
        $certificate = $csr ? openssl_csr_sign($csr, null, $privateKey, 3650, ['digest_alg' => 'sha256']) : false;

        if (! $certificate) {
            return;
        }

        openssl_x509_export($certificate, $certificateOutput);
        openssl_pkey_export($privateKey, $privateKeyOutput);

        file_put_contents($paths['cert'], $certificateOutput);
        file_put_contents($paths['key'], $privateKeyOutput);
        @chmod($paths['key'], 0600);
    }

    // Intentionally rely on Report::verificationHash() for the verification hash
}
