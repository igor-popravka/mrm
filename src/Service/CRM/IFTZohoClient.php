<?php

namespace App\Service\CRM;

use App\Entity\User;
use App\Service\Country;
use  CristianPontes\ZohoCRMClient\Response\MutationResult;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Buzz\Browser;
use Buzz\Client\Curl;
use CristianPontes\ZohoCRMClient\ZohoCRMClient;
use CristianPontes\ZohoCRMClient\Transport;
use CristianPontes\ZohoCRMClient\Transport\TransportRequest;
use App\Service\CRM\Request;

/**
 * @author: igor.popravka
 * Date: 09.02.2018
 * Time: 12:51
 */
class IFTZohoClient {
    /** @var Transport\XmlDataTransportDecorator */
    protected $transport;
    /** @var array */
    protected $uploadedDir;

    public function __construct($url, $authToken, $timeout, $uploadedDir) {
        $this->uploadedDir = $uploadedDir;

        $curl_client = new Curl();
        $curl_client->setTimeout($timeout);
        $this->transport = new Transport\XmlDataTransportDecorator(
            new Transport\AuthenticationTokenTransportDecorator(
                $authToken,
                new Transport\BuzzTransport(
                    new Browser($curl_client),
                    $url . '/crm/private/xml/'
                )
            )
        );
    }

    /**
     * @param $module
     * @return TransportRequest
     */
    protected function request($module) {
        $request = new Transport\TransportRequest($module);
        $request->setTransport($this->transport);
        return $request;
    }

    protected function client($module) {
        return new ZohoCRMClient($module, $this->transport);
    }

    protected function getUploadedDir($name) {
        return $this->uploadedDir[$name] ?? "";
    }

    public function insertContact(User $user) {
        $country = new Country();
        $records = $this->client('Contacts')->insertRecords()
            ->setRecords([
                [
                    'First Name' => $user->getFirstName(),
                    'Last Name' => $user->getLastName(),
                    'Email' => $user->getEmail(),
                    'Timezone' => $user->getTimezone(),
                    'Phone' => $user->getPhone(),
                    'Mailing Country' => $country->getNameByISOCode($user->getCountry()),
                    'Mailing City' => $user->getCity(),
                    'Mailing State' => $user->getState(),
                    'Mailing Zip' => $user->getZipCode(),
                    'Mailing Street' => $user->getAddress()
                ]
            ])
            ->onDuplicateError()
            ->triggerWorkflow()
            ->request();

        /*$records = [
            new class extends MutationResult {
                public $id = '909090909920452323492#1';

                public function isInserted() {
                    return true;
                }

                public function __construct() {
                    parent::__construct(1, 'code');
                }
            }
        ];*/

        if (count($records) && ($record = array_shift($records)) instanceof MutationResult) {
            /** @var MutationResult $record */

            if ($record->isInserted()) {
                $user->setZohoRecordID($record->id);

                if (($file = $user->getAvatar()) instanceof UploadedFile) {
                    /** @var UploadedFile $file */
                    $user->setAvatar(sprintf("%s.%s", md5(uniqid()), $file->guessExtension()));
                    $target = "{$this->getUploadedDir('avatar')}/{$user->getAvatar()}";

                    @copy($file->getRealPath(), $target);
                    @unlink($file->getRealPath());

                    try {
                        $this->uploadContactPhoto()
                            ->id($user->getZohoRecordID())
                            ->uploadFromPath($target)
                            ->request();
                    } catch (\Exception $e) {
                    }

                }
                return true;
            }
        }
        return false;
    }

    /**
     * @return Request\UploadPhoto
     */
    public function uploadContactPhoto() {
        return new Request\UploadPhoto($this->request('Contacts'));
    }
}