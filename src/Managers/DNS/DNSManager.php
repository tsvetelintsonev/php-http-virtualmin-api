<?php
namespace Nilemin\Virtualmin\Managers\DNS;

use Nilemin\Virtualmin\Http\HttpClient;
use Nilemin\Virtualmin\Http\HttpClientInterface;
use Nilemin\Virtualmin\Managers\BaseManager;
use Nilet\Components\Configuration\Config;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class DNSManager extends BaseManager implements DNSManagerInterface {

    private $dnsRecordTypes = [
        DNSRecordTypes::A => "A - IPv4 Address",
        DNSRecordTypes::AAAA => "AAAA - IPv6 Address",
        DNSRecordTypes::CNAME => "CNAME - Name Alias",
        DNSRecordTypes::MX => "MX - Mail Server",
        DNSRecordTypes::NS => "NS - Name Server",
        DNSRecordTypes::PTR => "PTR - Reverse Address",
        DNSRecordTypes::SRV => "SRV - Service record",
        DNSRecordTypes::TXT => "TXT - Text"
    ];

    /**
     * @var array
     */
    private $ttlUnits = [
        "s", // seconds
        "m", // minutes
        "h", // hours
        "d", // days
        "w" // weeks
    ];

    /**
     * DNSManager constructor.
     *
     * @param HttpClient $httpClient
     * @param Config $config
     */
    public function __construct(HttpClientInterface $httpClient, Config $config) {
        parent::__construct($httpClient, $config);
    }

    /**
     * Adds a dns records to a given domain.
     *
     * @param string $domain Domain name.
     * @param array $dnsRecords DNSRecord instances.
     * @return bool TRUE on success, FALSE otherwise.
     */
    public function addDnsRecords(string $domain, array $dnsRecords) : bool {
        $this->httpClient->queryStringBuilder()->addParameter("program", "modify-dns");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        foreach ($dnsRecords as $record) {
            /* @var $record DNSRecord */
            $this->httpClient->queryStringBuilder()->addParameter("add-record-with-ttl", (string)$record);
        }
        return $this->httpClient->sendRequest();
    }

    /**
     * Deletes a dns records to a given domain.
     *
     * @param string $domain Domain name.
     * @param array $dnsRecords DNSRecord instances.
     * @return bool TRUE on success, FALSE otherwise.
     */
    public function deleteDnsRecords(string $domain, array $dnsRecords) : bool {
        $this->httpClient->queryStringBuilder()->addParameter("program", "modify-dns");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        foreach ($dnsRecords as $record) {
            /* @var $record DNSRecord */
            $this->httpClient->queryStringBuilder()->addParameter("remove-record", $record->getName() . " " . $record->getType());
        }
        return $this->httpClient->sendRequest();
    }

    /**
     * Adds SPF (Sender Policy Framework) host names option to a given domain.
     *
     * @param string $domain
     * @param array $hostnames Host names e.g ["domain.com", "192.168.1", etc.]
     * @return bool TRUE on success, FALSE otherwise.
     */
    public function addSpfHostnames(string $domain, array $hostnames) : bool {
        return $this->handleSpfHostnames($domain, $hostnames, "add");
    }

    /**
     * * Deletes SPF (Sender Policy Framework) host names from a given domain.
     *
     * @param string $domain
     * @param array $hostnames Host names e.g ["domain.com", "192.168.1", etc.]
     * @return bool TRUE on success, FALSE otherwise.
     */
    public function deleteSpfHostnames(string $domain, array $hostnames) : bool {
        return $this->handleSpfHostnames($domain, $hostnames, "remove");
    }

    private function handleSpfHostnames(string $domain, array $hostnames, string $action) {
        $this->httpClient->queryStringBuilder()->addParameter("program", "modify-dns");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        foreach ($hostnames as $hostname) {
            $this->httpClient->queryStringBuilder()->addParameter("spf-{$action}-a", $hostname);
        }
        return $this->httpClient->sendRequest();
    }

    /**
     * Retrieves all domain DNS records.
     *
     * @param string $domain
     * @return array
     */
    public function fetchDNSRecords(string $domain) : array {
        $this->httpClient->queryStringBuilder()->addParameter("program", "get-dns");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("multiline");

        $this->httpClient->sendRequest();
        return $this->filterDNSRecords($this->httpClient->getResponseMessage()->data);
    }

    /**
     * Retrieves record's SPF options
     *
     * @param string $domain
     * @return array Array with SPF options if any, empty array otherwise.
     */
    public function fetchSPFOptions(string $domain) : array {
        $this->httpClient->queryStringBuilder()->addParameter("program", "get-dns");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("multiline");

        $this->httpClient->sendRequest();
        return $this->filterSPFOptions($this->httpClient->getResponseMessage()->data);
    }

    /**
     * Filters DNS records. Remove NSEC, DNSSEC, DNSKEY, RRSIG etc.
     *
     * @param array $dnsRecords
     * @return array
     */
    private function filterDNSRecords(array $dnsRecords) : array {
        $filteredRecords = [];
        foreach ($dnsRecords as $record) {
            if (isset($this->dnsRecordTypes[$record->values->type[0]]) && !$this->isDKIMRecord($record)) {
                $value = trim(implode(" ", $record->values->value));
                $ttl = $this->processTtl($record->values->ttl[0]);
                $filteredRecords[] = new DNSRecord($record->name, $record->values->type[0], $value, $ttl["value"], $ttl["unit"]);
            }
        }
        return $filteredRecords;
    }

    /**
     * Extracts record's ttl value and unit.
     *
     * @param $ttl
     * @return array
     */
    private function processTtl($ttl) : array {
        $ttlData = [];
        $ttl = isset($ttl) ? $ttl : $this->config->get("config")["defaultTtl"];
        $ttlData["value"] = substr($ttl, 0, strlen($ttl) - 1);
        $ttlData["unit"] = substr($ttl, -1);
        return $ttlData;
    }

    private function filterSPFOptions(array $dnsRecords) : array {
        foreach ($dnsRecords as $record) {
            if ($this->isSPFRecord($record)) {
                return $this->extractSPFOptions($record->values->value[0]);
            }
        }
        return [];
    }

    private function extractSPFOptions(string $value) : array {
        $matches = [];
        preg_match_all('/a:([^\s]+)/i', $value, $matches);
        return $matches[1];
    }

    /**
     * Determine if a given DNS record is DomainKeys Identified Mail.
     *
     * @param \stdClass $record
     * @return bool
     */
    private function isDKIMRecord(\stdClass $record) : bool {
        return strpos($record->name, "_domainkey") !== false;
    }

    private function isSPFRecord(\stdClass $record) : bool {
        return $record->values->type[0] === "SPF";
    }

    /**
     * Retrieves DNS record types.
     *
     * @return array
     */
    public function getDNSRecordTypes() : array {
        return $this->dnsRecordTypes;
    }

    /**
     * Ttl units. ["s", "m", "h", "d", w]
     * Respectively seconds, minutes, hours, days, weeks
     *
     * @return array
     */
    public function getTtlUnits() : array {
        return $this->ttlUnits;
    }

}
