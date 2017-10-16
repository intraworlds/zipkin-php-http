<?php
namespace IW\ZipkinPhpHttp\Model;

use InvalidArgumentException;
use IW\ZipkinPhpHttp\Helper;

class Span extends Model
{

    const ID_LENGTH = 8;

    const KIND_CLIENT = 'CLIENT';
    const KIND_SERVER = 'SERVER';
    const KIND_PRODUCER = 'PRODUCER';
    const KIND_CONSUMER = 'CONSUMER';


    /**
     * Randomly generated, unique identifier for a trace, set on all spans within it.
     * Encoded as 16 or 32 lowercase hex characters corresponding to 64 or 128 bits.
     * For example, a 128bit trace ID looks like 4e441824ec2b6a44ffdc9bb9a6453df3
     *
     * @var string
     */
    protected $traceId;

    /**
     * The logical operation this span represents in lowercase (e.g. rpc method).
     * Leave absent if unknown.
     * As these are lookup labels, take care to ensure names are low cardinality.
     * For example, do not embed variables into the name.
     *
     * @var string
     */
    protected $name;

    /**
     * The parent span ID or absent if this the root span in a trace.
     *
     * @var string
     */
    protected $parentId;

    /**
     * Unique 64bit identifier for this operation within the trace.
     * Encoded as 16 lowercase hex characters. For example ffdc9bb9a6453df3
     *
     * @var string
     */
    protected $id;

    /**
     * When present, clarifies timestamp, duration and remoteEndpoint. When
     * absent, the span is local or incomplete.
     *
     * @var string
     */
    protected $kind;

    /**
     * Epoch microseconds of the start of this span, possibly absent if incomplete.
     * For example, 1502787600000000 corresponds to 2017-08-15 09:00 UTC
     * This value should be set directly by instrumentation, using the most precise
     * value possible. For example, gettimeofday or multiplying epoch millis by 1000.
     * There are three known edge-cases where this could be reported absent.
     *
     * @var int
     */
    protected $timestamp;

    /**
     * Duration in microseconds of the critical path, if known. Durations
     * of less than one are rounded up.
     * For example 150 milliseconds is 150000 microseconds.
     *
     * @var int
     */
    protected $duration;

    /**
     * True is a request to store this span even if it overrides sampling policy.
     * This is true when the X-B3-Flags header has a value of 1.
     *
     * @var bool
     */
    protected $debug;

    /**
     * True if we are contributing to a span started by another tracer (ex on a different host).
     *
     * @var bool
     */
    protected $shared;

    /**
     * The network context of a node in the service graph
     *
     * @var Endpoint
     */
    protected $localEndpoint;

    /**
     * The network context of a node in the service graph
     *
     * @var Endpoint
     */
    protected $remoteEndpoint;

    /**
     * Associates events that explain latency with the time they happened.
     *
     * @var Annotation[]
     */
    protected $annotations = [];

    /**
     * Adds context to a span, for search, viewing and analysis.
     * For example, a key “your_app.version” would let you lookup traces by version.
     * A tag “sql.query” isn’t searchable, but it can help in debugging when viewing
     * a trace.
     *
     * @var array
     */
    protected $tags = [];

    public function __construct(string $kind, string $traceId, string $parentId=null) {
        if (!in_array($kind, [self::KIND_CLIENT, self::KIND_SERVER, self::KIND_PRODUCER, self::KIND_CONSUMER])) {
            throw new InvalidArgumentException('Invalid kind of span: ' . $kind);
        }

        if (!preg_match('/^[a-z0-9]{16,32}$/', $traceId)) {
            throw new InvalidArgumentException('Invalid traceId: ' . $traceId);
        }

        $this->kind = $kind;
        $this->traceId = $traceId;
        $this->parentId = $parentId;
        $this->id = Helper::generateId(self::ID_LENGTH);
    }

    public function addAnnotation(Annotation $annotation) {
        $this->annotation[] = $annotation;
    }

    public function setTag(string $name, string $value) {
        $this->tags[$name] = $value;
    }

    public function setLocalEndpoint(Endpoint $localEndpoint)
    {
        $this->localEndpoint = $localEndpoint;
    }

    public function start(float $microtime=null) {
        $this->timestamp = Helper::timestamp($microtime);
    }

    public function stop(float $microtime=null) {
        $this->duration = Helper::timestamp($microtime) - $this->timestamp;
    }
}
