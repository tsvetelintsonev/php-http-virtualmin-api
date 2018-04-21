<?php
namespace Nilemin\Virtualmin;

use Nilemin\Virtualmin\Accounts\Email\EmailAccountManager;
use Nilemin\Virtualmin\Managers\Account\Email\EmailAccountBaseManager;
use Nilemin\Virtualmin\Managers\Account\Ftp\FtpManager;
use Nilemin\Virtualmin\Managers\Database\DatabaseManager;
use Nilemin\Virtualmin\Managers\PHP\PHPManager;
use Nilemin\Virtualmin\Managers\Server\VirtualServerManager;
use Nilemin\Virtualmin\Managers\DNS\DNSManager;
use Nilemin\Virtualmin\Managers\SSL\SSLManager;
use Nilemin\Virtualmin\Managers\Scripts\ScriptsManager;
use Nilemin\Virtualmin\Managers\Cron\CronManager;
use Nilemin\Virtualmin\Http\HttpClientInterface;
use Nilet\Components\Configuration\Config;
use Nilet\Components\Container\DependencyContainer;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class Virtualmin {

    /**
     * Http client.
     * @var Nilemin\Virtualmin\Http\HttpClientInterface
     */
    private $httpClient = null;

    private $dc = null;

    /**
     * VirtualminApi new instance.
     *
     * @param string $url Virualmins URL.
     * @param int $port Virualmins port.
     * @param string $rootName Root user name.
     * @param string $rootPassword Root user password.
     * @param Config $config
     */
    public function __construct(string $url, int $port, string $rootName, string $rootPassword, Config $config) {
        $this->httpClient = new HttpClient($url, $port, $rootName, $rootPassword, "json");
        $this->dc = new DependencyContainer();
        $this->dc->instance(Config::class, $config);
        $this->dc->instance("Nilemin\\Virtualmin\\Http\\HttpClientInterface", function() { return $this->httpClient; });
    }

    /**
     * Retrieves the email account manager.
     *
     * @return EmailAccountBaseManager
     */
    public function createEmailManager() : EmailManager {
        return $this->dc->create(EmailManager::class);
    }

    /**
     * Retrieves the ftp account manager.
     *
     * @return FtpManager
     */
    public function createFtpManager() : FtpManager {
        return $this->dc->create(FtpManager::class);
    }

    /**
     * Retrieves the database manager.
     *
     * @return DatabaseManager
     */
    public function createDatabaseManager() : DatabaseManager {
        return $this->dc->create(DatabaseManager::class);
    }

    /**
     * Retrieves the virtual server manager.
     *
     * @return VirtualServerManager
     */
    public function createVirtualServerManager() : VirtualServerManager {
        return $this->dc->create(VirtualServerManager::class);
    }

    /**
     * Retrieves the domain manager.
     *
     * @return DNSManager
     */
    public function createDnsManager() : DNSManager {
        return $this->dc->create(DNSManager::class);
    }

    /**
     * Retrieves the SSL manager.
     *
     * @return SSLManager
     */
    public function createSslManager(): SSLManager {
        return $this->dc->create(SSLManager::class);
    }

    /**
     * Retrieves the PHP manager.
     *
     * @return PHPManager
     */
    public function createPhpManager(): PHPManager {
        return $this->dc->create(PHPManager::class);
    }

    /**
     * Retrieves the scripts manager.
     *
     * @return ScriptsManager
     */
    public function createScriptsManager(): ScriptsManager {
        return $this->dc->create(ScriptsManager::class);
    }

    /**
     * Retrieves the cron manager.
     *
     * @return ScriptsManager
     */
    public function createCronManager(): ScriptsManager {
        return $this->dc->create(CronManager::class);
    }

    /**
     * Retrieves Virtualmin's response message.
     *
     * @return \stdClass
     */
    public function getResponseMessage() : \stdClass {
        return $this->httpClient->getResponseMessage();
    }
}
