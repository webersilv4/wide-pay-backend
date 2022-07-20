<?php

    namespace Widepay\Scraping\Cron;

    require __DIR__ . "/../../vendor/autoload.php";
    require __DIR__ . "/../Functions/Functions.php";

    
    use Widepay\Scraping\Models\Model;
    
    class Cron extends Model {
        public function CronJob()
        {
            $smtp = $this->myQuery('SELECT * FROM urls', []);

            foreach ($smtp->fetchAll(\PDO::FETCH_ASSOC) as $value) {
                try {
                    $httpClient = new \GuzzleHttp\Client();
                    $response = $httpClient->get($value['url_website']);
                    $htmlString = (string) $response->getBody();
                    libxml_use_internal_errors(true); // Quando o HTML estiver instável, isso suprime os avisos.

                    $this->myQuery('UPDATE urls SET status_code = ?, requisition_body = ?, updated_at = ? WHERE id = ?', [200, $htmlString, realDate(), $value['id']]);

                } catch (\Throwable $e) { $this->myQuery('UPDATE urls SET status_code = ?, updated_at = ? WHERE id = ?', [404, realDate(), $value['id']]); }

            }
        }
    }

    
    $ronJob = new Cron();
    $ronJob->CronJob();