<?php
namespace Nilemin\Virtualmin\Managers\DNS;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class DNSRecordTypes {

    /**
     * IPv4 Address
     */
    const A = "A";

    /**
     * IPv6 Address
     */
    const AAAA = "AAAA";

    /**
     * Name Alias
     */
    const CNAME = "CNAME";

    /**
     * Name Server
     */
    const NS = "NS";

    /**
     * Mail Server
     */
    const MX = "MX";

    /**
     * Text
     */
    const TXT = "TXT";

    /**
     * Reverse address
     */
    const PTR = "PTR";

    /**
     * Service Record
     */
    const SRV = "SRV";
}
