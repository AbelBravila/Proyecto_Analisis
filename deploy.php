<?php

$secret = '7332b2908e4535575bb7b5a71b977ddcb4fd9200be29d4fa6205022acf04d937c6e8148f375872d57330bbfc1f6815f9';
$body = file_get_contents('php://input');
$signature = 'sha256='.hash_hmac('sha256', $body, $secret);

if (! hash_equals($signature, $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '')) {
    http_response_code(403);
    exit('Firma inválida');
}

$payload = json_decode($body, true);

if ($payload['ref'] === 'refs/heads/main') {

    $projectPath = '/var/www/posgt/Proyecto_Analisis';
    $logFile = '/var/www/posgt/deploy.log';

    $commands = [
        "cd $projectPath",
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
    ];

    $output = '';
    foreach ($commands as $cmd) {
        $output .= "\n\n$ ".$cmd."\n";
        $output .= shell_exec("$cmd 2>&1");
    }

    file_put_contents($logFile, date('[Y-m-d H:i:s] ').$output."\n", FILE_APPEND);
}

http_response_code(200);
echo 'Despliegue ejecutado';
