<?php
namespace Nilemin\Virtualmin\Managers\SSL;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class CSRInfo {

    /**
     * @var string
     */
    private $organization;

    /**
     * @var string
     */
    private $organizationUnit;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $domainName;

    /**
     * @return string
     */
    public function getOrganization(): string {
        return $this->organization;
    }

    /**
     * @param string $organization
     * @return $this
     */
    public function setOrganization(string $organization) {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrganizationUnit(): string {
        return $this->organizationUnit;
    }

    /**
     * @param string $organizationUnit
     * @return $this
     */
    public function setOrganizationUnit(string $organizationUnit) {
        $this->organizationUnit = $organizationUnit;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     * @return $this
     */
    public function setCountryCode(string $countryCode) {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getState(): string {
        return $this->state;
    }

    /**
     * @param string $state
     * @return $this
     */
    public function setState(string $state) {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city) {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email) {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getDomainName(): string {
        return $this->domainName;
    }

    /**
     * @param string $domainName
     * @return $this
     */
    public function setDomainName(string $domainName) {
        $this->domainName = $domainName;
        return $this;
    }


}
