<?php
namespace IW\ZipkinPhpHttp;

use GuzzleHttp\Client;
use IW\ZipkinPhpHttp\Model\Endpoint;
use IW\ZipkinPhpHttp\Model\Span;

class Tracer
{
    const API_PREFIX = '/api/v2';

    const DEFAULT_BASE_URI = 'http://localhost:9411';

    const TRACE_ID_LENGTH = 16;


    private $localEndpoint;

    private $spans = [];

    public function __construct(Client $httpClient, Endpoint $localEndpoint, string $traceId, string $parentId=null) {
        $this->httpClient = $httpClient;
        $this->localEndpoint = $localEndpoint;
        $this->traceId = $traceId;
        $this->parentId = $parentId;
    }

    public static function create(string $myName, string $traceId=null, string $parentId=null): self {
        return new self(
            new Client(['base_uri' => self::DEFAULT_BASE_URI]),
            new Endpoint($myName, gethostbyname(gethostname())),
            $traceId ?: Helper::generateId(self::TRACE_ID_LENGTH),
            $parentId
        );
    }

    public function spanRequest(): Span {
        $span = new Span(Span::KIND_CLIENT, $this->traceId, $this->parentId);
        $span->setLocalEndpoint($this->localEndpoint);

        return $this->spans[] = $span;
    }

    public function spanResponse(): Span {
        $span = new Span(Span::KIND_SERVER, $this->traceId, $this->parentId);
        $span->setLocalEndpoint($this->localEndpoint);

        return $this->spans[] = $span;
    }

    public function save(array $options=[]) {
        $body = array_map(function ($span) {
            return $span->toArray();
        }, $this->spans);
        // if ($options['async'] ?? true) {
            // $this->httpClient->postAsync(self::API_PREFIX . '/spans', ['json' => ])
            return $this->httpClient->post(self::API_PREFIX . '/spans', ['json' => $body]);
        // }

    }
}
