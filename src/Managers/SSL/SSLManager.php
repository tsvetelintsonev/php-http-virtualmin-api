<?php
namespace Nilemin\Virtualmin\Managers\SSL;

use Nilemin\Virtualmin\Http\HttpClientInterface;
use Nilemin\Manager\BaseManager;
use Nilet\Components\FileSystem\IFile;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class SSLManager extends BaseManager implements SSLManagerInterface {

    public function __construct(HttpClientInterface $httpClient) {
        parent::__construct($httpClient);
    }

    /**
     * Generates a Certificate Signing Request file and RSA(Rivest-Shamir-Adleman) private key for a given domain.
     *
     * @param CSRInfo $csrInfo CSR information - organization, org. unit, country, city etc.
     * @return string CSR file content.
     */
    public function generateCSR(CSRInfo $csrInfo) {
        $domain = $csrInfo->getDomainName();
        $queryBuilder = $this->httpClient->queryStringBuilder();
        $queryBuilder->addParameter("program", "generate-cert");
        $queryBuilder->addParameter("domain", $domain);
        $queryBuilder->addParameter("csr");
        $queryBuilder->addParameter("o", $csrInfo->getOrganization());
        $queryBuilder->addParameter("ou", $csrInfo->getOrganizationUnit());
        $queryBuilder->addParameter("c", $csrInfo->getCountryCode());
        $queryBuilder->addParameter("st", $csrInfo->getState());
        $queryBuilder->addParameter("l", $csrInfo->getCity());
        $queryBuilder->addParameter("email", $csrInfo->getEmail());
        $queryBuilder->addParameter("cn", $domain);

        $this->httpClient->sendRequest();
        return $this->getCSRContent($domain);
    }

    /**
     * Retrieves CSR content of a given domain.
     *
     * @param string $domain Domain name.
     * @return string
     */
    public function getCSRContent(string $domain) : string {
        $queryBuilder = $this->httpClient->queryStringBuilder();
        $queryBuilder->addParameter("program", "list-certs");
        $queryBuilder->addParameter("domain", $domain);
        $queryBuilder->addParameter("csr");

        $this->httpClient->sendRequest();
        $data = array_slice($this->httpClient->getResponseMessage()->data, 1);
        $csr = "";
        foreach ($data as $csrLine) {
            $csr .= $csrLine->name.PHP_EOL;
        }
        return $csr;
    }

    /**
     * Installs a signed SSL certificate fpr a given domain.
     *
     * @param string $domain
     * @param IFile $file
     * @return bool
     */
    public function installSSL(string $domain, IFile $file) : bool {
        $queryBuilder = $this->httpClient->queryStringBuilder();
        $queryBuilder->addParameter("program", "install-cert");
        $queryBuilder->addParameter("domain", $domain);
        $queryBuilder->addParameter("cert", $file->getRealPath());
        $queryBuilder->addParameter("use-newkey");

        return $this->httpClient->sendRequest();
    }

}
