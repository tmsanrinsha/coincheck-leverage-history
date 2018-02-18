<?php
require "vendor/autoload.php";

// https://github.com/coincheckjp/coincheck-php
// https://coincheck.com/documents/exchange/api?locale=ja

date_default_timezone_set('Asia/Tokyo');

$coincheck = new Coincheck\Coincheck(getenv('ACCESS_KEY'), getenv('API_SECRET'));

$start_time = new DateTime(getenv('START_TIME'));
$end_time = new DateTime(getenv('END_TIME'));

$num = getenv('REQUEST_NUM');

$starting_after = null;
$pl_sum = 0;

for ($i=0; $i < $num; $i++) {
    $ret = $coincheck->leverage->positions([
        'status' => "closed",
        'limit' => 25,
        'starting_after' => $starting_after
    ]);
    if ($ret['success'] !== true) {
        break;
    }

    foreach ($ret['data'] as $data) {
        $date = new DateTime($data['closed_at']);
        if ($date < $start_time || $date >= $end_time) {
            continue;
        }

        echo "i:{$i}" . "\t";
        echo "id:{$data['id']}" . "\t";

        $date->setTimeZone(new DateTimeZone('Asia/Tokyo'));
        echo "time:{$date->format('c')}" . "\t";

        $pl = $data['pl'];
        echo "pl:{$pl}" . "\n";

        $pl_sum += $pl;
    }

    if (count($ret['data']) === 25) {
        $starting_after = $ret['data'][24]['id'];
    } else {
        break;
    }
}

echo "損益: {$pl_sum}" . PHP_EOL;
