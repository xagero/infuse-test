<?php
/**
 * @author Pavel Tsydzik <xagero@gmail.com>
 * @date 09.08.2017 18:45
 */

namespace App\Storage;

use PDO;
use PDOStatement;

/**
 * Class MysqlAdapter
 * @package App\Adapter
 */
class MysqlAdapter implements AdapterInterface
{
    /**
     * @var string
     */
    protected $dsn;

    /**
     * @var PDO
     */
    protected $connect;

    /**
     * Mysql constructor.
     * @param $host
     * @param $port
     * @param $dbname
     * @param string $charset
     */
    public function __construct($host, $port, $dbname, $charset = 'UTF8')
    {
        $this->dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset};";
    }

    /**
     * Create connection
     *
     * @param null $username
     * @param null $password
     * @return mixed|void
     */
    public function connect($username = null, $password = null)
    {
        $this->connect = new PDO($this->dsn, $username, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    /**
     * Perform query
     *
     * @param $sql
     * @param $args
     * @return array|mixed
     */
    public function query($sql, $args = [])
    {
        $stmt = $this->connect->prepare($sql);
        $result = $stmt->execute($args);

        return $stmt->fetchAll();
    }

    /**
     * @param $sql
     * @param array $args
     * @return bool
     */
    public function queryResult($sql, $args = [])
    {
        $stmt = $this->connect->prepare($sql);
        $result = $stmt->execute($args);

        return $result;
    }

}
