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
    protected $signature = 'curl:send {host} {user} {password}';

    protected $host;
    protected $user;
    protected $password;

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

        $this->host = $this->argument('host');
        $this->user = $this->argument('user');
        $this->password = $this->argument('password');


        $username = "office@nxmarketing.ru";
        $password = "admin";
        $host_api = "http://nxmarketing.loc";

        $host_api = "http://31.31.203.14";

        $host_api = $this->host;

        // авторизация
        $start = time();

        echo "auth";
        echo PHP_EOL;
        $curl = curl_init($host_api);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
        // get запрос
        curl_setopt($curl, CURLOPT_URL, "$host_api/1c_exchange.php?mode=checkauth&type=catalog");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        echo $result;
        echo PHP_EOL;
        echo time() - $start;
        echo PHP_EOL;
        // вывести результат

        echo "init";
        echo PHP_EOL;
        $pr = preg_split("~\n~", $result);
        curl_setopt($curl, CURLOPT_URL, "$host_api/1c_exchange.php?type=catalog&mode=init");
        curl_setopt($curl, CURLOPT_COOKIE, "$pr[1]=$pr[2]");
        $result = curl_exec($curl);
        echo $result;
        echo PHP_EOL;
        echo time() - $start;
        echo PHP_EOL;
        $pf = preg_split("~\n~", $result);
        $size = str_replace("file_limit=", "", $pf[1]);

        echo "file";
        echo PHP_EOL;
        $name = 'v8_7B01_68d4.zip';
        $filename = "/Users/maxim/Projects/contract/storage/app/public/v8_7B01_68d4.zip";

        curl_setopt($curl, CURLOPT_URL, "$host_api/1c_exchange.php?type=catalog&mode=file&filename=$name");


        $handle = fopen($filename, "r");
        if (FALSE === $handle) {
            exit("Не удалось открыть поток по url адресу");
            die;
        }
        $r = 0;
        do {
            $content = fread($handle, $size);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/octet-stream", 'Content-length: ' . (ftell($handle) - $r * $size)));
            $result = curl_exec($curl);
            echo $result;
            echo PHP_EOL;
            echo time() - $start;
            echo PHP_EOL;
            $r++;
        } while (!feof($handle));
        fclose($handle);

        curl_close($curl);
        $curl = curl_init($host_api);

        echo "import-import.xml";
        echo PHP_EOL;
        curl_setopt($curl, CURLOPT_URL, "$host_api/1c_exchange.php?mode=import&filename=import.xml&type=catalog");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_COOKIE, "$pr[1]=$pr[2]");

        do {
            $result = curl_exec($curl);
            echo $result;
            echo PHP_EOL;
            echo time() - $start;
            echo PHP_EOL;
        } while (strstr($result, 'progress'));


        echo "import-offers.xml";
        echo PHP_EOL;
        curl_setopt($curl, CURLOPT_URL, "$host_api/1c_exchange.php?mode=import&filename=offers.xml&type=catalog");

        do {
            $result = curl_exec($curl);
            echo $result;
            echo PHP_EOL;
            echo time() - $start;
            echo PHP_EOL;
        } while (strstr($result, 'progress'));

        curl_close($curl);

        file_put_contents('index.html', $result);
    }
}
