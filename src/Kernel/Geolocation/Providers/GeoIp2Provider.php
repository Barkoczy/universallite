<?php
namespace App\Kernel\Geolocation\Providers;

use GeoIp2\WebService\Client;
use GeoIp2\Model\Country;
use App\Kernel\Environment;
use App\Kernel\Router\IPAddress;

final class GeoIp2Provider 
{
  /** @var Client */
  private $client;

  /** @var Country */
  private $record;

  /**
   * Constructor
   */
  public function __construct()
  {
    // @client
    $this->client = new Client(
      Environment::var('MAXMIND_ACCOUNT_ID'), 
      Environment::var('MAXMIND_LICENSE_KEY'),
      ['en'], 
      ['host' => 'geolite.info']
    );

    // @country
    $this->record = $this->client->country(
      (new IPAddress())->get()
    );
  }

  /**
   * Country ISO
   *
   * @return string
   */
  public function countryISO(): string
  {
    return $this->record->country->isoCode;
  }
}