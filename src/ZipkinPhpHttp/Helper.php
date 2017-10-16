<?php
namespace IW\ZipkinPhpHttp;


class Helper
{
    /**
     * Generates random unique identifier of requested length
     *
     * @param int $bytes how many bytes should be ID long
     *
     * @return string hex representation of the ID
     */
    public static function generateId(int $bytes): string {
        return bin2hex(random_bytes($bytes));
    }

    /**
     * Returns epoch microseconds for given time or for now if nothing given
     *
     * @param float $microtime unixtimestamp with as float
     *
     * @return int
     */
    public static function timestamp(float $microtime=null): int {
        if ($microtime === null) {
            $microtime = microtime(true);
        }

        return round($microtime * 1000000);
    }
}
