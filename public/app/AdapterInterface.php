<?php
/**
 * @author Pavel Tsydzik <xagero@gmail.com>
 * @date 09.08.2017 18:47
 */

namespace App\Storage;

/**
 * Interface AdapterInterface
 * @package App\Adapter
 */
interface AdapterInterface
{
    /**
     * @param string $username
     * @param string $password
     * @return mixed
     */
    public function connect($username = null, $password = null);

    /**
     * Perform query, return all
     *
     * @param $sql
     * @param $args
     * @return mixed
     */
    public function query($sql, $args = []);

    /**
     * @param $sql
     * @param array $arg
     * @return mixed
     */
    public function queryResult($sql, $arg = []);

}
