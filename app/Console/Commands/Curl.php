<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Curl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'curl:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $username = "office@nxmarketing.ru";
        $password = "admin";
        $host_api = "http://31.31.203.14";

        // авторизация
        $curl = curl_init($host_api);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
        // get запрос
        curl_setopt($curl, CURLOPT_URL, "$host_api/1c_exchange.php?mode=checkauth&type=catalog");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        echo $result;
        // вывести результат
        $pr = preg_split("~\n~", $result);
        curl_setopt($curl, CURLOPT_URL, "$host_api/1c_exchange.php?type=catalog&mode=init");
        curl_setopt($curl, CURLOPT_COOKIE, "$pr[1]=$pr[2]");
        $result = curl_exec($curl);
        echo $result;

        $name = 'v8_7B01_68d4.zip';
        $filename = "/Users/maxim/Projects/contract/storage/app/public/v8_7B01_68d4.zip";

        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/octet-stream"));
        curl_setopt($curl, CURLOPT_POSTFIELDS, file_get_contents($filename));
        curl_setopt($curl, CURLOPT_URL, "$host_api/1c_exchange.php?type=catalog&mode=file&filename=$name");

        $result = curl_exec($curl);

        file_put_contents('index.html', $result);
        echo $result;
        curl_close($curl);
    }
}
