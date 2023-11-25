<?php
namespace App\Kernel\Controllers;

use Symfony\Component\Yaml\Yaml;
use App\Kernel\Filesystem\Folder;
use App\Kernel\Controllers\Enum\Commands;

final class DataCommands
{
  /**
   * @param string $v
   * @return boolean
   */
  public function has(string $v = ''): bool
  {
    if (str_contains($v, Commands::loadFile))
      return true;

    return false;
  }

  /**
   * Find and Execute Command
   *
   * @param string $v
   * @return mixed
   */
  public function exec(string $v = ''): mixed
  {
    // @loadFile
    if (str_contains($v, Commands::loadFile))
      return $this->loadFile($v);

    // @default
    return $v;
  }

  /**
   * Load File
   *
   * @param string $v
   * @return mixed
   */
  private function loadFile(string $v = ''): mixed
  {
    preg_match('/\$loadFile\s*([^\;]+)/', $v, $match);

    if (!isset($match[1]))
      return $v;

    $filePath = substr($match[1], 1, -1);

    if (!in_array(explode('.', $filePath)[1], ['yml', 'yaml']))
      return $v;

    $path = Folder::getConfigPath().$filePath;

    if (!is_file($path))
      return $v;

    return Yaml::parseFile($path)??[];
  }
}
