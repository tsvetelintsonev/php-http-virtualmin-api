<?php
namespace Nilemin\Virtualmin\Managers\SSL;
use Nilet\Components\FileSystem\IFile;


/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
interface SSLManagerInterface {
    /**
     * Generates a Certificate Signing Request file and RSA(Rivest-Shamir-Adleman) private key for a given domain.
     *
     * @param CSRInfo $csrInfo CSR information - organization, org. unit, country, city etc.
     * @return string CSR file content.
     */
    public function generateCSR(CSRInfo $csrInfo);

    /**
     * Retrieves CSR content of a given domain.
     *
     * @param string $domain Domain name.
     * @return string
     */
    public function getCSRContent(string $domain) : string;

    /**
     * Installs a signed SSL certificate fpr a given domain.
     *
     * @param string $domain
     * @param IFile $file
     * @return bool
     */
    public function installSSL(string $domain, IFile $file) : bool;
}
