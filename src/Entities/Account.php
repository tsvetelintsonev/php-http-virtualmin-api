<?php
namespace Nilemin\Virtualmin\Entities;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class Account {

    /**
     * @var string
     */
    private $name;

    /**
     * @var \stdClass
     */
    protected $account;

    public function __construct(\stdClass $account) {
        $this->name = $account->name;
        $this->account = $account->values;
    }

    public function getUnixUsername() {
        return $this->account->unix_username[0];
    }

    public function getMailStorageType() {
        return $this->account->mail_storage_type[0];
    }

    public function getUserType() {
        return $this->account->user_type[0];
    }

    public function getDisabled() {
        return $this->account->disabled[0];
    }

    public function getFtpAccess() {
        return $this->account->ftp_access[0];
    }

    public function getDomain() {
        return $this->account->domain[0];
    }

    public function getHomeByteQuota() {
        return $this->account->home_byte_quota[0];
    }

    public function getHomeByteQuotaUsed() {
        return $this->account->home_byte_quota_used[0];
    }

    public function getHomeQuota() {
        return $this->account->home_quota[0];
    }

    public function getHomeQuotaUsed() {
        return $this->account->home_quota_used[0];
    }

    public function getHomeDirectory() {
        return $this->account->home_directory[0];
    }

    public function getEmailAddress() {
        return $this->account->email_address[0];
    }

    public function getRealName() {
        return $this->account->real_name[0];
    }

    public function getLoginPermissions() {
        return $this->account->login_permissions[0];
    }

    public function getMailLocation() {
        return $this->account->mail_location[0];
    }


}
