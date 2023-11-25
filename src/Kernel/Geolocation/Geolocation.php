<?php
namespace App\Kernel\Geolocation;

use Symfony\Component\Yaml\Yaml;
use App\Kernel\Filesystem\Folder;
use App\Kernel\Geolocation\Enum\Providers;
use App\Kernel\Geolocation\Enum\SchemaParams;
use App\Kernel\Geolocation\Providers\GeoIp2Provider;
use App\Kernel\Exceptions\CannotReadFileFromFileSource;
use App\Kernel\Exceptions\GeoProviderNotFound;

final class Geolocation
{
  /** @var string */
  private $conf = [];

  /**
   * Constructor
   */
  public function __construct()
  {
    // @localePath
    $localePath = Folder::getConfigPath().'/locale.yml';

    // @valid
    if (!is_file($localePath))
      throw new CannotReadFileFromFileSource('Cannot read locale yaml configuration file due to insufficient permissions');

    // @conf
		$this->conf = Yaml::parseFile($localePath)??[];
  }

  /**
   * @return string
   */
  public function countryISO(): string
  {
    return $this->provider()->countryISO();
  }

  /**
   * @return mixed
   */
  private function provider(): mixed
  {
    $provider = $this->conf[SchemaParams::geolocate][SchemaParams::provider];

    switch ($provider) {
      case Providers::maxmind:
        return new GeoIp2Provider();
      default:
        throw new GeoProviderNotFound("The defined geo location provider {$provider} was not found");
    }
  }

  /**
   * @return boolean
   */
  public function hasEnabled(): bool
  {
    return isset($this->conf[SchemaParams::geolocate][SchemaParams::enabled]) &&
      (bool)$this->conf[SchemaParams::geolocate][SchemaParams::enabled] === true;
  }

  /**
   * @return boolean
   */
  public function hasProvider(): bool
  {
    // @valid
    if (!isset($this->conf[SchemaParams::geolocate][SchemaParams::provider]))
      return false;

    // @available
    switch ($this->conf[SchemaParams::geolocate][SchemaParams::provider]) {
      case Providers::maxmind:
        return true;
      default:
        return false;
    }

    // @default
    return false;
  }
}
