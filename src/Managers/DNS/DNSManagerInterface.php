<?php
namespace Nilemin\Virtualmin\Managers\DNS;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
interface DNSManagerInterface {
    /**
     * Adds a dns records to a given domain.
     *
     * @param string $domain     Domain name.
     * @param array  $dnsRecords DNSRecord instances.
     *
     * @return bool TRUE on success, FALSE otherwise.
     */
    public function addDnsRecords(string $domain, array $dnsRecords) : bool;

    /**
     * Deletes a dns records to a given domain.
     *
     * @param string $domain     Domain name.
     * @param array  $dnsRecords DNSRecord instances.
     *
     * @return bool TRUE on success, FALSE otherwise.
     */
    public function deleteDnsRecords(string $domain, array $dnsRecords) : bool;

    /**
     * Adds SPF (Sender Privacy Framework) host names option to a given domain.
     *
     * @param string $domain
     * @param array  $hostnames Host names e.g ["domain.com", "192.168.1", etc.]
     *
     * @return bool TRUE on success, FALSE otherwise.
     */
    public function addSpfHostnames(string $domain, array $hostnames) : bool;

    /**
     * * Deletes SPF (Sender Privacy Framework) host names from a given domain.
     *
     * @param string $domain
     * @param array  $hostnames Host names e.g ["domain.com", "192.168.1", etc.]
     *
     * @return bool TRUE on success, FALSE otherwise.
     */
    public function deleteSpfHostnames(string $domain, array $hostnames) : bool;

    /**
     * Retrieves all domain DNS records.
     *
     * @param string $domain
     *
     * @return array
     */
    public function fetchDNSRecords(string $domain) : array;

    /**
     * Retrieves DNS record types.
     *
     * @return array
     */
    public function getDNSRecordTypes() : array;

    /**
     * Ttl units. ["s", "m", "h", "d", w]
     * Respectively seconds, minutes, hours, days, weeks
     *
     * @return array
     */
    public function getTtlUnits() : array;
}
