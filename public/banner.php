<?php
/**
 * @author Pavel Tsydzik <xagero@gmail.com>
 * @date 14.08.2017 15:06
 */

use App\Storage\MysqlAdapter;

$name = './image/banner.jpg';
$fp = fopen($name, 'rb');
$size = getimagesize($name);

header("Content-Type: {$size['mime']}");
fpassthru($fp);

try {

    require_once 'app/AdapterInterface.php';
    require_once 'app/MysqlAdapter.php';
    $config = require_once 'config/app.config.php';

    $db = $config['app/database'];

    $storage = new MysqlAdapter($db['host'], $db['port'], $db['dbname']);
    $storage->connect($db['username'], $db['password']);

    $sql = "LOCK TABLES {$db['table']} READ, WRITE";
    $storage->queryResult($sql);

    $ip = $_SERVER['REMOTE_ADDR'];
    $agent = $_SERVER['HTTP_USER_AGENT'];
    $link = $_SERVER['HTTP_REFERER'];

    $sql = "SELECT id FROM {$db['table']} WHERE";
    $sql.= " (ip_address = :ip AND user_agent = :agent AND page_url = :link)";
    $result = $storage->query($sql, [
        'ip' => $ip,
        'agent' => $agent,
        'link' => $link
    ]);

    if ($result) {
        $sql = "UPDATE {$db['table']} SET views_count = views_count + 1 WHERE id = :id";
        $storage->query($sql, [
            'id' => $result[0]['id']
        ]);
    } else {

        $keys = 'ip_address, user_agent, view_date, page_url, views_count';
        $values = ':ip_address, :user_agent, :view_date, :page_url, :views_count';
        $sql = "INSERT INTO {$db['table']} ($keys) VALUES ({$values})";

        $result = $storage->queryResult($sql, $d = [
            'ip_address' => $ip,
            'user_agent' => $agent,
            'view_date' => time(),
            'page_url' => $link,
            'views_count' => 1
        ]);

        if (!$result) {
            throw new RuntimeException('Fail to insert visitor into database, ');
        }

    }

    $sql = "UNLOCK TABLES {$db['table']}";
    $storage->queryResult($sql);

} catch (Throwable $e) {
    /** @ */
    file_put_contents('error.log', date('Y-m-d H:i:s ') . $e->getMessage() . PHP_EOL, FILE_APPEND);
}
