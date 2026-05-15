<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$password = $argv[1] ?? 'ChangeMe123!';
fwrite(STDOUT, password_hash($password, PASSWORD_DEFAULT) . PHP_EOL);
