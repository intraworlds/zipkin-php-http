<?php
namespace IW\ZipkinPhpHttp\Model;

use IW\ZipkinPhpHttp\Helper;

/**
 * Associates an event that explains latency with a timestamp.
 * Unlike log statements, annotations are often codes. Ex. “ws” for WireSend
 *
 * Zipkin v1 core annotations such as “cs” and “sr” have been replaced with
 * Span.Kind, which interprets timestamp and duration.
 */
class Annotation extends Model
{
    /**
     * Epoch microseconds of this event.
     *
     * For example, 1502787600000000 corresponds to 2017-08-15 09:00 UTC
     *
     * This value should be set directly by instrumentation, using the most precise
     * value possible. For example, gettimeofday or multiplying epoch millis by 1000.
     *
     * @var int
     */
    protected $timestamp;

    /**
     * Usually a short tag indicating an event, like “error”
     *
     * While possible to add larger data, such as garbage collection details, low
     * cardinality event names both keep the size of spans down and also are easy
     * to search against.
     *
     * @var string
     */
    protected $value;

    /**
     * Constructor
     *
     * @param string $value     Usually a short tag indicating an event, like “error”
     * @param int    $timestamp Epoch microseconds of this event.
     */
    public function __construct(string $value, int $timestamp=null) {
        $this->value = $value;
        $this->timestamp = $timestamp ?? Helper::timestamp();
    }
}
