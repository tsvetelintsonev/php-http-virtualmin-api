<?php
namespace Nilemin\Virtualmin\Entities;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class Server extends SubServer {

    public function __construct(\stdClass $sever) {
        parent::__construct($sever);
    }

    public function getUserQuota() {
        return $this->server->user_quota[0];
    }

    public function getUserByteQuotaUsed() {
        return $this->server->user_byte_quota_used[0];
    }

    public function getUserQuotaUsed() {
        return $this->server->user_quota_used[0];
    }

    public function getUserBlockQuota() {
        return $this->server->user_block_quota[0];
    }

    public function getUserBlockQuotaUsed() {
        return $this->server->user_block_quota_used[0];
    }

    public function getServerQuota() {
        return $this->server->server_quota[0];
    }

    public function getServerQuotaUsed() {
        return $this->server->server_quota_used[0];
    }

    public function getServerBlockQuota() {
        return $this->server->server_block_quota[0];
    }

    public function getServerBlockQuotaUsed() {
        return $this->server->server_block_quota_used[0];
    }

    public function getServerByteQuotaUsed() {
        return $this->server->server_byte_quota_used[0];
    }

    public function getBandwidthByteLimit() {
        return $this->server->bandwidth_byte_limit[0];
    }

    public function getBandwidthUsage() {
        return $this->server->bandwidth_usage[0];
    }

    public function getBandwidthByteUsage() {
        return $this->server->bandwidth_byte_usage[0];
    }

    public function getBandwidthStart() {
        return $this->server->bandwidth_start[0];
    }


    public function getBandwidthLimit() {
        return $this->server->bandwidth_limit[0];
    }

    public function getEditCapabilities() {
        return $this->server->edit_capabilities[0];
    }

    public function getAllowedMysqlHosts() {
        return $this->server->allowed_mysql_hosts[0];
    }

    public function getAllowedFeatures() {
        return $this->server->allowed_features[0];
    }

    public function getMaximumSubServers() {
        return $this->server->{"maximum_sub-servers"}[0];
    }

    public function getMaximumMailboxes() {
        return $this->server->maximum_mailboxes[0];
    }

    public function getReadOnlyMode() {
        return $this->server->{"read-only_mode"}[0];
    }

    public function getMaximumNonAliasServers() {
        return $this->server->{"maximum_non-alias_servers"}[0];
    }

    public function getSubServersInheritIpAddress() {
        return $this->server->{"sub-servers_inherit_ip_address"}[0];
    }

    public function getMaximumProcesses() {
        return $this->server->maximum_processes[0];
    }

    public function getMaximumDatabases() {
        return $this->server->maximum_databases[0];
    }

    public function getMaximumAliasServers() {
        return $this->server->maximum_alias_servers[0];
    }

    public function getLoginPermissions() {
        return $this->server->login_permissions[0];
    }

    public function getMaximumAliases() {
        return $this->server->maximum_aliases[0];
    }
}
