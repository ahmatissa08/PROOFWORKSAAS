<?php

/**
 * Local certificate generation for PDF signing.
 *
 * This is only for local development/testing. In production, configure
 * PROOFWORK_PDF_CERTIFICATE_PATH and PROOFWORK_PDF_PRIVATE_KEY_PATH with a
 * real certificate/key pair stored outside the public web root.
 */

// Set OpenSSL config for XAMPP
$xamppPaths = [
    'C:\\xampp\\php\\extras\\openssl\\openssl.cnf',
    'C:\\xampp\\apache\\conf\\openssl.cnf',
    'C:\\xampp\\php\\extras\\ssl\\openssl.cnf',
    'C:\\xampp\\php\\openssl.cnf',
    'D:\\xampp\\php\\extras\\openssl\\openssl.cnf',
    'D:\\xampp\\apache\\conf\\openssl.cnf',
    'D:\\xampp\\php\\extras\\ssl\\openssl.cnf',
];

$found = false;
$opensslConfigPath = null;
foreach ($xamppPaths as $path) {
    if (file_exists($path)) {
        $opensslConfigPath = realpath($path) ?: $path;
        putenv('OPENSSL_CONF='.$opensslConfigPath);
        $_ENV['OPENSSL_CONF'] = $opensslConfigPath;
        $_SERVER['OPENSSL_CONF'] = $opensslConfigPath;
        echo "Found OpenSSL config: $opensslConfigPath\n";
        $found = true;
        break;
    }
}

if (! $found) {
    echo "ERROR: Could not find openssl.cnf\n";
    echo "Please check your XAMPP installation.\n";
    exit(1);
}

$certDir = dirname(__DIR__).'/storage/app/certificates';
if (! is_dir($certDir)) {
    mkdir($certDir, 0755, true);
}

$certPath = $certDir.'/proofwork.crt';
$keyPath = $certDir.'/proofwork.key';

$config = [
    'config' => $opensslConfigPath,
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
    'digest_alg' => 'sha256',
];

$privKey = openssl_pkey_new($config);
if (! $privKey) {
    echo "ERROR: Failed to generate private key\n";
    while (($e = openssl_error_string()) !== false) {
        echo "  - $e\n";
    }
    exit(1);
}

$dn = [
    'countryName' => 'US',
    'stateOrProvinceName' => 'CA',
    'localityName' => 'San Francisco',
    'organizationName' => 'ProofWork',
    'organizationalUnitName' => 'Verification',
    'commonName' => 'proofwork.app',
    'emailAddress' => 'verify@proofwork.app',
];

$csr = openssl_csr_new($dn, $privKey, $config);
if (! $csr) {
    echo "ERROR: Failed to create CSR\n";
    while (($e = openssl_error_string()) !== false) {
        echo "  - $e\n";
    }
    exit(1);
}

$cert = openssl_csr_sign($csr, null, $privKey, 3650, $config);
if (! $cert) {
    echo "ERROR: Failed to sign certificate\n";
    while (($e = openssl_error_string()) !== false) {
        echo "  - $e\n";
    }
    exit(1);
}

$certOut = '';
$keyOut = '';

openssl_x509_export($cert, $certOut);
openssl_pkey_export($privKey, $keyOut, null, $config);

file_put_contents($certPath, $certOut);
file_put_contents($keyPath, $keyOut);

openssl_pkey_free($privKey);

echo "SUCCESS! Certificate generated.\n";
echo "Certificate: $certPath\n";
echo "Private key: $keyPath\n";
