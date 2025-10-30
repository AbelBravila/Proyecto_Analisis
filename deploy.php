<?php

$secret = '7332b2908e4535575bb7b5a71b977ddcb4fd9200be29d4fa6205022acf04d937c6e8148f375872d57330bbfc1f6815f9';
$projectPath = '/var/www/posgt/Proyecto_Analisis';
$logFile = '/var/www/posgt/deploy.log';

$body = file_get_contents('php://input');
$signature = 'sha256='.hash_hmac('sha256', $body, $secret);

if (! hash_equals($signature, $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '')) {
    http_response_code(403);
    echo 'Firma inválida';
    file_put_contents($logFile, date('[Y-m-d H:i:s] ')."Firma inválida\n", FILE_APPEND);
    exit;
}

$payload = json_decode($body, true);
$ref = $payload['ref'] ?? '(sin ref)';

file_put_contents($logFile, "=== Webhook recibido ===\n", FILE_APPEND);
file_put_contents($logFile, date('[Y-m-d H:i:s] ')."Ref: {$ref}\n", FILE_APPEND);
file_put_contents($logFile, date('[Y-m-d H:i:s] ').'Usuario actual: '.get_current_user()."\n", FILE_APPEND);

if (! in_array($ref, ['refs/heads/main', 'refs/heads/master'])) {
    file_put_contents($logFile, date('[Y-m-d H:i:s] ')."Rama no autorizada: {$ref}\n\n", FILE_APPEND);
    http_response_code(200);
    echo 'Rama ignorada';
    exit;
}

if (! file_exists($logFile)) {
    touch($logFile);
    chown($logFile, 'www-data');
    chmod($logFile, 0664);
}

$commands = [
    "cd $projectPath",
    'sudo git config --global --add safe.directory /var/www/posgt/Proyecto_Analisis',
    'sudo git reset --hard',
    'sudo git pull origin main',
    'sudo composer install --no-dev --optimize-autoloader',
    'sudo npm install',
    'sudo npm run build',
    'sudo rm -rf node_modules',
    'sudo php artisan config:clear',
    'sudo php artisan cache:clear',
    'sudo php artisan route:clear',
    'sudo php artisan view:clear',
    'sudo php artisan optimize',
    "sudo chown -R www-data:www-data $projectPath",
    "sudo chmod -R 775 $projectPath/storage $projectPath/bootstrap/cache",
];

$output = "\n=== DEPLOY START ".date('[Y-m-d H:i:s]')." ===\n";
foreach ($commands as $cmd) {
    $output .= "\n$ $cmd\n";
    $res = shell_exec("$cmd 2>&1");
    $output .= $res ?: "(sin salida)\n";
}
$output .= "\n=== DEPLOY END ".date('[Y-m-d H:i:s]')." ===\n\n";

file_put_contents($logFile, $output, FILE_APPEND);

http_response_code(200);
echo 'Despliegue ejecutado correctamente.';
