<?php
namespace App\Kernel\Translator;

use Symfony\Component\Yaml\Yaml;
use App\Kernel\Helpers\Pathkeys;
use App\Kernel\Filesystem\Folder;
use App\Kernel\Exceptions\CannotReadFileFromFileSource;
use App\Kernel\Translator\Enum\Source;

final class Translator
{
  /** @var array */
  public $locale = [];

  /**
   * Constructor
   *
   * @param string $iso
   */
  public function __construct(string $iso = '')
  {
    // @localePath
    $localePath = Folder::getLocalesPath().'/'.$iso.'.yml';

    // @valid
    if (!is_file($localePath))
      throw new CannotReadFileFromFileSource("Cannot read {$iso} locale configuration file due to insufficient permissions");

    // @locale
		$this->locale = Yaml::parseFile($localePath)??[];
  }

  /**
   * @param string $key
   * @param string $source
   * @return string
   */
  public function translate(string $key = '', string $source = ''): string
  {
    if ($source === Source::locale)
      return Pathkeys::get_prop_by_path($this->locale, $key)??$key;

    return $key;
  }
}
