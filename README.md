# php-virtualmin-remote-api

> **Note:** This is not a full featured implementation of the API. 
However it is really easy to add new functionalities to the library if needed.

### Requirements
`PHP >= 7.0`

### Install

Composer

```javascript
{
    "require": {
        "nilemin/php-http-virtualmin-api": ">=v1.0"
    }
}
```

### API examples

```php
use Nilemin\Virtualmin\DNS\DNSRecord;
use Nilemin\Virtualmin\Managers\DNS\DNSRecordTypes;
use Nilemin\Virtualmin\Managers\Server\ServerTypes;
use Nilemin\Virtualmin\Managers\SSL\CSRInfo;
use Nilemin\Virtualmin\Virtualmin;
use Nilet\Components\Configuration\Config;
use Nilet\Components\FileSystem\Directory;

require_once "vendor/autoload.php";

$config = new Config();
$config->setConfigDir(new Directory(__DIR__ . "/config"));
$api = new Virtualmin("domain.com", 1221, "root", "password", $config);

$domain = "domain.com";
```

Email and Ftp accounts
```
$emailManager = $api->createEmailManager();
$ftpManager = $api->createFtpManager();
$emailManager->createEmailAccount($domain, "emailaccount1", "123", "Real name", 5);
$ftpManager->createFtpAccount($domain, "onlyftp4", "123");

$emailManager->changeEmailAccountQuota($domain, "emailaccount1", 10);

$emailManager->disableEmailAccount($domain, "emailaccount1");
$ftpManager->disableFtpAccount($domain, "onlyftp1");

$emailManager->enableEmailAccount($domain, "emailaccount1");
$ftpManager->enableFtpAccount($domain, "onlyftp1");

$emailManager->fetchEmailAccounts($domain);
$ftpManager->fetchFtpAccounts($domain);

$emailManager->deleteEmailAccount($domain, "emailaccount1");
$ftpManager->deleteFtpAccount($domain, "onlyftp1");
```

Database
```php
$dBManager = $api->createDatabaseManager();
$dBManager->createDatabase($domain, "mysqldbtest");
$dBManager->deleteDatabase($domain, "mysqldbtest");

$dBManager->fetchDatabasesNames($domain);
$dBManager->fetchDatabases($domain);

$dBManager->grantDatabaseAccess($domain, "emailaccount1", "test2");
$dBManager->grantDatabaseAccess($domain, "onlyftp1", "test1");

$dBManager->removeDatabaseAccess($domain, "emailaccount1", "test2");
$dBManager->removeDatabaseAccess($domain, "onlyftp1", "test1");
```

Virtual server
```php
$vsManager = $api->createVirtualServerManager();
$vsManager->changeServerName($domain, "newdomain.org");
$vsManager->changeAdminPassword($domain, "7777");

$options = [
    "dns",
    "mail"
];
$vsManager->createSubServer("sub1.domain.org", $domain, "Sub 1", $options);
$vsManager->deleteServer("sub1.domain.org");

$vsManager->fetchAddonServers("domain");
$vsManager->fetchSubServers("domain");
$vsManager->fetchSubServersNames($domain, "domain");
$vsManager->fetchAddonServersNames($domain, "domain");
$vsManager->fetchServer($domain, ServerTypes::TOP_LEVEL_SERVER);

$vsManager->createServerAlias("domain-alias.org", "domain.org", "");
$vsManager->fetchAliasServers("domain");

$vsManager->addServerRedirect("domain.org", "/", "http://domain-redirect.org");
$vsManager->addServerRedirect("domain.org", "/asas", "http://domain-redirect-asas.org");

$vsManager->fetchServerRedirects($domain);

$vsManager->deleteServerRedirect($domain, "/");
$vsManager->deleteServerRedirect($domain, "/asas");
```

DNS
```php
$dnsManager = $api->createDnsManager();
$dnsManager->addSpfHostnames($domain, ["domain-spf-test.org", "192.168.1"]);
$dnsManager->deleteSpfHostnames($domain, ["domain-spf-test.org", "192.168.1"]);

$dnsRecords = [
    new DNSRecord("TXT-test1", DNSRecordTypes::TXT, "TXT test value", 666),
    new DNSRecord("TXT-test2", DNSRecordTypes::TXT, "TXT test value", 777),
    new DNSRecord("TXT-test4", DNSRecordTypes::TXT, "TXT test value", 777)
];
$dnsManager->deleteDnsRecords($domain, $dnsRecords);
$dnsManager->addDnsRecords($domain, $dnsRecords);
$dnsManager->fetchDNSRecords($domain);
$dnsManager->fetchSPFOptions($domain);
```

SSL
```php
$sslManager = $api->createSslManager();
$csrInfo = new CSRInfo();
$csrInfo->setOrganization("Organization")
    ->setOrganizationUnit("unit")
    ->setCountryCode("dk")
    ->setState("MidtJylland")
    ->setCity("Viborg")
    ->setEmail("test@email.com")
    ->setDomainName($domain);
echo $sslManager->generateCSR($csrInfo);
echo $sslManager->getCSRContent($domain);
```

PHP
```php
$phpManager = $api->createPhpManager();
$phpManager->fetchPHPDirectories($domain);
$phpManager->fetchInstalledPHPVersions();

$phpManager->addPHPDirectory($domain, "test_php_dr2", "7.0");
$phpManager->addPHPDirectory($domain, "test_php_dr3", "5.6");

$phpManager->deletePHPDirectory($domain, "test_php_dr2");
$phpManager->deletePHPDirectory($domain, "test_php_dr3");

$phpManager->fetchPHPIniVars($domain, "7.0", $config->get("config")["userModifiableIniVars"]);
$phpManager->modifyPHPIniVars($domain, "7.0", ["max_execution_time" => 60, "display_errors" => 0]);
```

Script installers
```php
$scriptManager = $api->createScriptsManager();
$scriptManager->installCMS($domain, $config->get("config")["scripts"]["cms"]["wordpress"]);
$scriptManager->installPHPMyAdmin($domain, $config->get("config")["scripts"]["dbPanels"]["phpmyadmin"]);

$scriptManager->deleteScript($domain, "phpmyadmin");

$scriptManager->fetchInstalledScripts($domain);
$scriptManager->fetchInstalledScript($domain, "wordpress");
```
