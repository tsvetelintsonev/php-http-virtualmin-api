<?php
namespace Nilemin\Virtualmin\Entities;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class SubServer extends Entity {

    public function __construct(\stdClass $object) {
        parent::__construct($object);
    }

    public function getPHPMaxExecutionTime() {
        return $this->object->php_max_execution_time[0];
    }

    public function getPasswordForMysql() {
        return $this->object->password_for_mysql[0];
    }

    public function getSpamassassinClient() {
        return $this->object->spamassassin_client[0];
    }

    public function getDatabasesSize() {
        return $this->object->databases_size[0];
    }

    public function getCgiDirectory() {
        return $this->object->cgi_directory[0];
    }

    public function getCreatedOn() {
        return $this->object->created_on[0];
    }

    public function getPHPVersion() {
        return $this->object->php_version[0];
    }

    public function getPlan() {
        return $this->object->plan[0];
    }

    public function getPlanId() {
        return $this->object->plan_id[0];
    }

    public function getSPF_DNS_Record_Status() {
        return $this->object->spf_dns_record[0];
    }

    public function getHomeDirectory() {
        return $this->object->home_directory[0];
    }

    public function getErrorLog() {
        return $this->object->error_log[0];
    }

    public function getTemplate() {
        return $this->object->template[0];
    }

    public function getPHPExecutionMode() {
        return $this->object->php_execution_mode[0];
    }

    public function getDescription() {
        return $this->object->description[0];
    }

    public function getUsername() {
        return $this->object->username[0];
    }

    public function getHtmlDirectory() {
        return $this->object->html_directory[0];
    }

    public function getSpamClearingPolicy() {
        return $this->object->spam_clearing_policy[0];
    }

    public function getVirusDelivery() {
        return $this->object->virus_delivery[0];
    }

    public function getDatabasesCount() {
        return $this->object->databases_count[0];
    }

    public function getType() {
        return $this->object->type[0];
    }

    public function getContactAddress() {
        return $this->object->contact_address[0];
    }

    public function getParentDomainName() {
        return $this->object->parent_domain[0];
    }

    public function getDatabasesByteSize() {
        return $this->object->databases_byte_size[0];
    }

    public function getIPAddress() {
        return $this->object->ip_address[0];
    }

    public function getTrashClearingPolicy() {
        return $this->object->trash_clearing_policy[0];
    }

    public function getRubyExecutionMode() {
        return $this->object->ruby_execution_mode[0];
    }

    public function getTemplateId() {
        return $this->object->template_id[0];
    }

    public function getGroupName() {
        return $this->object->group_name[0];
    }

    public function getFeatures() {
        return $this->object->features[0];
    }

    public function hasFeature(string $feature) {
        return strpos($this->getFeatures(), strtolower($feature)) !== false;
    }

    public function getDMARC_DNS_Record_Status() {
        return $this->object->dmarc_dns_record[0];
    }

    public function getAccessLog() {
        return $this->object->access_log[0];
    }

    public function getUsernameForMysql() {
        return $this->object->username_for_mysql[0];
    }

    public function getGroupId() {
        return $this->object->group_id[0];
    }

    public function getContactEmail() {
        return $this->object->contact_email[0];
    }

    public function getSSLKeyFile() {
        return $this->object->ssl_key_file[0];
    }

    public function getSSLCertFile() {
        return $this->object->ssl_cert_file[0];
    }
}
