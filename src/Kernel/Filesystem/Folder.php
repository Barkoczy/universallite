<?php
namespace App\Kernel\Filesystem;

use App\Kernel\Filesystem\Enum\Directory;

final class Folder
{
  /**
   * Root path
   *
   * @return string
   */
  public static function getRootPath(): string
  {
    return realpath(dirname(__DIR__).'/../../');
  }

  /**
   * SRC path
   *
   * @return string
   */
  public static function getSRCPath(): string
  {
    return realpath(self::getRootPath().'/'.Directory::SRC);
  }

  /**
   * App theme path
   *
   * @return string
   */
  public static function getAppThemesPath(): string
  {
    return realpath(self::getSRCPath().'/'.Directory::THEMES);
  }

  /**
   * Cert path
   *
   * @return string
   */
  public static function getCertPath(): string
  {
    return realpath(self::getRootPath().'/'.Directory::SRC.'/'.Directory::CERT);
  }

  /**
   * Config path
   *
   * @return string
   */
  public static function getConfigPath(): string
  {
    return realpath(self::getRootPath().'/'.Directory::SRC.'/'.Directory::CONF);
  }

  /**
   * Web path
   *
   * @return string
   */
  public static function getWebPath(): string
  {
    return realpath(self::getRootPath().'/web');
  }

  /**
   * Config path
   *
   * @return string
   */
  public static function getAssetsPath(): string
  {
    return realpath(self::getWebPath().'/'.Directory::ASSETS);
  }

  /**
   * Utils path
   *
   * @return string
   */
  public static function getUtilsPath(): string
  {
    return realpath(self::getRootPath().'/'.Directory::SRC.'/'.Directory::UTILS);
  }

  /**
   * Locales path
   *
   * @return string
   */
  public static function getLocalesPath(): string
  {
    return realpath(self::getRootPath().'/'.Directory::SRC.'/'.Directory::LOCALES);
  }

  /**
   * Kernel path
   *
   * @return string
   */
  public static function getKernelPath(): string
  {
    return realpath(self::getRootPath().'/'.Directory::SRC.'/'.Directory::KERNEL);
  }

  /**
   * Language path
   *
   * @return string
   */
  public static function getLanguageConfPath(): string
  {
    return realpath(self::getKernelPath().'/'.Directory::LANGUAGE.'/'.Directory::CONF);
  }

  /**
   * Dashboard path
   *
   * @return string
   */
  public static function getDashboardPath(): string
  {
    return realpath(self::getKernelPath().'/'.Directory::DASHBOARD);
  }

  /**
   * Universal templates path
   *
   * @return string
   */
  public static function getUniversalTemplatesPath(): string
  {
    return realpath(self::getKernelPath().'/'.Directory::TEMPLATES);
  }

  /**
   * Console templates path
   *
   * @return string
   */
  public static function getDashboardTemplatesPath(): string
  {
    return realpath(self::getDashboardPath().'/'.Directory::TEMPLATES);
  }

  /**
   * Router path
   *
   * @return string
   */
  public static function getHttpDLConfPath(): string
  {
    return realpath(self::getKernelPath().'/'.Directory::HTTP_DL.'/'.Directory::CONF);
  }


  /**
   * Router path
   *
   * @return string
   */
  public static function getRouterPath(): string
  {
    return realpath(self::getKernelPath().'/'.Directory::ROUTER);
  }

  /**
   * Router config path
   *
   * @return string
   */
  public static function getRouterConfPath(): string
  {
    return realpath(self::getRouterPath().'/'.Directory::CONF);
  }

  /**
   * Controllers path
   *
   * @return string
   */
  public static function getControllersPath(): string
  {
    return realpath(self::getKernelPath().'/'.Directory::CONTROLLERS);
  }

  /**
   * Controllers config path
   *
   * @return string
   */
  public static function getControllersConfigPath(): string
  {
    return realpath(self::getControllersPath().'/'.Directory::CONF);
  }
}