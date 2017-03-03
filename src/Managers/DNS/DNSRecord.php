<?php
namespace Nilemin\Virtualmin\DNS;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class DNSRecord {

    /**
     * Name of the dns record.
     *
     * @var string
     */
    private $name;

    /**
     * Type of the dns record.
     *
     * @var string
     */
    private $type;

    /**
     * Value of the dns record.
     *
     * @var string
     */
    private $value;

    /**
     * Time to live in seconds.
     *
     * @var int
     */
    private $ttl = 300;

    /**
     * @var string
     */
    private $ttlUnit = "m";

    /**
     * DNSRecord constructor.
     *
     * @param string $name  Dns record name.
     * @param string $type  DNSRecordTypes constant.
     * @param string $value Dns record value.
     * @param int $ttl Time to live in seconds.
     * @param string $ttlUnit. "s" for seconds, "m" for minutes, "h" for hours, "d" for days, "w" for weeks.
     */
    public function __construct(string $name, string $type, string $value, int $ttl = null, string $ttlUnit = null) {
        $this->name = $this->normalizeName($name);
        $this->type = $type;
        $this->value = $value;
        if($ttl !== null) {
            $this->ttl = $ttl;
        }
        if($ttlUnit !== null) {
            $this->ttlUnit = $ttlUnit;
        }
    }

    /**
     * Normalizes/extracts record's name.
     * Virtualmin returns subdomain DNS records with names consisting of subdomain name, domain name and a "." suffix.
     * We are interested only in the subdomain name.
     *
     * @param string $name
     * @return string
     */
    private function normalizeName(string $name) : string {
        if(substr_count($name, ".") > 2) {
            return substr($name, 0, strrpos(substr($name, 0, strrpos($name, ".", -2)), "."));
        }
        return $name;
    }

    /**
     * Creates string with the following format - "name type value",
     * which is ready for use when adding dns records.
     *
     * @return string
     */
    public function __toString() {
        return trim($this->name . " " . $this->type . " " . $this->ttl . $this->ttlUnit . " " . $this->value);
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getValue(): string {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getTtl(): int {
        return $this->ttl;
    }

    public function getTtlUnit() : string {
        return $this->ttlUnit;
    }

}
