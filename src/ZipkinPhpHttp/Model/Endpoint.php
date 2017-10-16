<?php
namespace IW\ZipkinPhpHttp\Model;

class Endpoint extends Model
{
    /**
     * Lower-case label of this node in the service graph, such as "favstar". Leave
     * absent if unknown.
     *
     * This is a primary label for trace lookup and aggregation, so it should be
     * intuitive and consistent. Many use a name from service discovery.
     *
     * @var string
     */
    protected $serviceName;

    /**
     * The text representation of the primary IPv4 address associated with this
     * a connection. Ex. 192.168.99.100 Absent if unknown.
     *
     * @var string
     */
    protected $ipv4;

    /**
     * The text representation of the primary IPv6 address associated with this
     * a connection. Ex. 2001:db8::c001 Absent if unknown.
     *
     * Prefer using the ipv4 field for mapped addresses.
     *
     * @var string
     */
    protected $ipv6;

    /**
     * Depending on context, this could be a listen port or the client-side of
     * a socket. Absent if unknown
     *
     * @var int
     */
    protected $port;

    public function __construct(string $serviceName=null, string $ipv4=null, int $port=null, string $ipv6=null) {
        $this->serviceName = $serviceName;
        $this->ipv4 = $ipv4;
        $this->port = $port;
        $this->ipv6 = $ipv6;
    }
}
