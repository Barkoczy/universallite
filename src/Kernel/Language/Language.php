<?php
namespace App\Kernel\Language;

use Symfony\Component\Yaml\Yaml;
use App\Kernel\Environment;
use App\Kernel\Router\IPAddress;
use App\Kernel\Filesystem\Folder;
use App\Kernel\Exceptions\EmptyConfigFile;
use App\Kernel\Exceptions\MissingParameter;
use App\Kernel\Exceptions\CannotReadFileFromFileSource;
use App\Kernel\Exceptions\UnsupportedLanguage;
use App\Kernel\Language\Enum\Config;
use App\Kernel\Language\Enum\Locality;
use App\Kernel\Language\Enum\SchemaParams;
use App\Kernel\Language\Enum\SessionKeys;
use App\Kernel\Geolocation\Enum\Rules as GeoRules;
use App\Kernel\Geolocation\Enum\SchemaParams as GeoSchemaParams;
use App\Kernel\Geolocation\Geolocation;

final class Language
{
  /** @var Geolocation */
  private $geolocation;

  /** @var string */
  private $browserISO;

  /** @var array */
  public $confKernel = [];

  /** @var array */
  public $confLocale = [];

  /** @var array */
  public $confTranslate = [];

  /** @var string */
  public $webLangISO = '';

  /** @var string */
  public $dashbaordLangISO = '';

  /**
   * Construnctor
   */
  public function __construct()
  {
    // @translatePath
    $translatePath = Folder::getLanguageConfPath().'/translate.yml';

    // @kernelPath
    $kernelPath = Folder::getLanguageConfPath().'/languages.yml';

    // @localePath
    $localePath = Folder::getConfigPath().'/locale.yml';

    // @valid
    if (!is_file($translatePath))
      throw new CannotReadFileFromFileSource('Cannot read kernel translate languages yaml configuration file due to insufficient permissions');
    if (!is_file($kernelPath))
      throw new CannotReadFileFromFileSource('Cannot read kernel languages yaml configuration file due to insufficient permissions');
    if (!is_file($localePath))
      throw new CannotReadFileFromFileSource('Cannot read locale yaml configuration file due to insufficient permissions');

    // @confKernel
		$this->confKernel = Yaml::parseFile($kernelPath)??[];

    // @confLocale
		$this->confLocale = Yaml::parseFile($localePath)??[];

    // @confTranslate
		$this->confTranslate = Yaml::parseFile($translatePath)??[];

    // @valid
    if (count($this->confKernel) === 0)
      throw new EmptyConfigFile('Empty kernel language config file');

    // @geolocation
    $this->geolocation = new Geolocation();

    // @browserISO
    $this->browserISO = isset(getallheaders()["Accept-Language"])
      ? explode(',', getallheaders()["Accept-Language"])[0]
      : '';

    // @setup
    $this->setup();
  }

  /**
   * Web Langauge
   *
   * @return array
   */
  public function getWeb(): array
  {
    // @index
    $index = $this->find($this->webLangISO, SchemaParams::iso, Config::kernel);

    // @data
    return $this->confKernel[$index];
  }

  /**
   * dashbaord Language
   *
   * @return array
   */
  public function getdashbaord(): array
  {
    // @index
    $index = $this->find($this->dashbaordLangISO, SchemaParams::iso, Config::kernel);

    // @data
    return $this->confKernel[$index];
  }

  /**
   * Translate Locale
   *
   * @param string $inputISO
   * @param string $outputISO
   * @return string
   */
  public function translateLocale(string $inputISO = '', $outputISO = ''): string
  {
    $data = $this->getConfigData(Config::translate);

    foreach ($data as $v) {
      if ($v[SchemaParams::iso] === $inputISO) {
        foreach ($v[SchemaParams::translate] as $t) {
          if ($t[SchemaParams::iso] === $outputISO) {
            return $t[SchemaParams::name];
          }
        }
      }
    }

    return '';
  }

  /**
   * @param string $iso
   * @param string $locality
   * @return void
   */
  public function rewriteLocale(string $iso = '', string $locality = ''): void
  {
    if ($locality === Locality::web) {
      $_SESSION[SessionKeys::web] = $iso;
      $this->webLangISO = $iso;
    }

    if ($locality === Locality::dashbaord) {
      $_SESSION[SessionKeys::dashbaord] = $iso;
      $this->dashbaordLangISO = $iso;
    }
  }

  /**
   * Setup
   *
   * @return void
   */
  private function setup(): void
  {
    if (!$this->isSetup()) {
      if ($this->hasLocaleConfig()) {
        if (
          !(new IPAddress())->isLocalost() &&
          $this->geolocation->hasEnabled() &&
          $this->geolocation->hasProvider()
        ) {
          $this->setGeoLocale();
        } else {
          $this->setBrowserAcceptLanguage();
        }
      } else {
        $this->setEnviromentLocale();
      }
    } else {
      $this->webLangISO = $_SESSION[SessionKeys::web];
      $this->dashbaordLangISO = $_SESSION[SessionKeys::dashbaord];
    }
  }

  /**
   * @return boolean
   */
  private function isSetup(): bool
  {
    if (!isset($_SESSION[SessionKeys::web]))
      return false;
    if (!isset($_SESSION[SessionKeys::dashbaord]))
      return false;
    if (!$this->hasISO($_SESSION[SessionKeys::web], Config::kernel))
      return false;
    if (!$this->hasISO($_SESSION[SessionKeys::dashbaord], Config::kernel))
      return false;
    return true;
  }

  /**
   * @return void
   */
  private function setGeoLocale(): void
  {
    $iso = $this->geoISO();

    if (!$this->hasISO($this->browserISO, Config::kernel)) {
      if ($this->hasISO($iso, Config::kernel)) {
        $this->setLocale($iso);
      } else {
        $this->setEnviromentLocale();
      }
    } else {
      if ($iso === $this->browserISO) {
        if ($this->hasISO($iso, Config::kernel)) {
          $this->setLocale($iso);
        }
      } else {
        $this->setBrowserAcceptLanguage();
      }
    }
  }

  /**
   * @return void
   */
  private function setBrowserAcceptLanguage(): void
  {
    if (!$this->hasISO($this->browserISO, Config::kernel)) {
      $this->setEnviromentLocale();
    } else {
      $data = $this->getConfigData(Config::locale);
      $valid = false;

      foreach ($data as $v) {
        $acceptISO = $v[SchemaParams::prefered][SchemaParams::browser][SchemaParams::accept_language];

        if (in_array($this->browserISO, $acceptISO)) {
          if ($this->hasISO($this->browserISO, Config::kernel)) {
            $this->setLocale($this->browserISO);
            $valid = true;
          }
        }
      }

      if ($valid === false)
        $this->setEnviromentLocale();
    }
  }

  /**
   * @return void
   */
  private function setEnviromentLocale(): void
  {
    $locale = Environment::var('APP_LOCALE');

    if (strlen($locale) === 0)
      throw new MissingParameter('Missing language iso parameter in enviroment file via APP_LOCALE (.env)');

    if (!$this->hasISO($locale, Config::kernel))
      throw new MissingParameter('Invalid language iso parameter');

    $this->setLocale($locale);
  }

  /**
   * General Locale
   *
   * @param string $iso
   * @return void
   */
  private function setLocale(string $iso = ''): void
  {
    // @session
    if (!isset($_SESSION[SessionKeys::web])) {
      $_SESSION[SessionKeys::web] = $iso;
    }
    if (!isset($_SESSION[SessionKeys::dashbaord])) {
      $_SESSION[SessionKeys::dashbaord] = $iso;
    }

    // @webLangISO
    $this->webLangISO = $iso;

    // @dashbaordLangISO
    $this->dashbaordLangISO = $iso;
  }

  /**
   * @return string
   */
  private function geoISO(): string
  {
    $data = $this->geoConfigData();
    $countryISO = $this->geolocation->countryISO();

    foreach ($data as $v) {
      if (
        $v[GeoSchemaParams::rule] === GeoRules::ONLY &&
        in_array($countryISO, $v[GeoSchemaParams::country])
      ) {
        return $v[SchemaParams::iso];
      }
      if (
        $v[GeoSchemaParams::rule] === GeoRules::EVERYWHERE_EXCEPT &&
        !in_array($countryISO, $v[GeoSchemaParams::country])
      ) {
        return $v[SchemaParams::iso];
      }
    }

    return '';
  }

  /**
   * @return array
   */
  private function geoConfigData(): array
  {
    $data = $this->getConfigData(Config::locale);
    $geo = [];

    foreach ($data as $v) {
      $geo[] = [
        SchemaParams::iso => $v[SchemaParams::iso],
        GeoSchemaParams::rule => $v[SchemaParams::prefered][GeoSchemaParams::geo][GeoSchemaParams::country][GeoSchemaParams::rule],
        GeoSchemaParams::country => $v[SchemaParams::prefered][GeoSchemaParams::geo][GeoSchemaParams::country][SchemaParams::iso]
      ];
    }

    return $geo;
  }

  /**
   * @return boolean
   */
  private function hasLocaleConfig(): bool
  {
    $data = $this->getConfigData(Config::locale);

    if (count($data) === 0)
      return false;

    foreach ($data as $v) {
      $iso = $v[SchemaParams::iso];

      if (!$this->hasISO($iso, Config::kernel)) {
        throw new UnsupportedLanguage("The defined iso language {$iso} is not supported");
      }
    }

    return true;
  }

  /**
   * Exists ISO in configuration
   *
   * @param string $iso
   * @param string $config
   * @return boolean
   */
  private function hasISO(string $iso = '', string $config = ''): bool
  {
    return false !== $this->find($iso, SchemaParams::iso, $config);
  }

  /**
   * Find index
   *
   * @param string $val
   * @param string $key
   * @param string $config
   * @return mixed
   */
  private function find(string $val = '', string $key = '', string $config = ''): mixed
  {
    return array_search($val, array_column($this->getConfigData($config), $key));
  }

  /**
   * @param string $config
   * @return array
   */
  private function getConfigData(string $config = ''): array
  {
    if (Config::kernel === $config)
      return $this->confKernel;
    if (Config::translate === $config)
      return $this->confTranslate;
    if (Config::locale === $config && isset($this->confLocale[SchemaParams::resources]))
      return $this->confLocale[SchemaParams::resources];
    return [];
  }
}
