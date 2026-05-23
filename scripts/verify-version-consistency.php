<?php

declare(strict_types=1);

$pluginRoot = dirname(__DIR__);
$versionFile = $pluginRoot . '/VERSION';
$pluginFile = $pluginRoot . '/ar-design-yaymail-payment-qr.php';
$readmeFile = $pluginRoot . '/readme.txt';

if (! file_exists($versionFile) || ! file_exists($pluginFile) || ! file_exists($readmeFile)) {
	fwrite(STDERR, "Missing VERSION, plugin main file or readme.txt.\n");
	exit(1);
}

$version = trim((string) file_get_contents($versionFile));
$pluginSource = (string) file_get_contents($pluginFile);
$readmeSource = (string) file_get_contents($readmeFile);

if ('' === $version) {
	fwrite(STDERR, "VERSION file is empty.\n");
	exit(1);
}

$errors = array();

if (! preg_match('/^\s*\*\s*Version:\s*' . preg_quote($version, '/') . '\s*$/mi', $pluginSource)) {
	$errors[] = 'Plugin header Version does not match VERSION file.';
}

if (! preg_match("/define\\(\\s*'ARD_YAYMAIL_PAYMENT_QR_VERSION'\\s*,\\s*'" . preg_quote($version, '/') . "'\\s*\\)/", $pluginSource)) {
	$errors[] = 'ARD_YAYMAIL_PAYMENT_QR_VERSION does not match VERSION file.';
}

if (! preg_match('/^Stable tag:\s*' . preg_quote($version, '/') . '\s*$/mi', $readmeSource)) {
	$errors[] = 'readme.txt Stable tag does not match VERSION file.';
}

if (! empty($errors)) {
	foreach ($errors as $error) {
		fwrite(STDERR, $error . "\n");
	}

	exit(1);
}

echo "Version consistency OK ({$version}).\n";