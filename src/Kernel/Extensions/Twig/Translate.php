<?php
namespace App\Kernel\Extensions\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;
use App\Kernel\Translator\Enum\Source;
use App\Kernel\Translator\Translator;
use App\Kernel\Language\Enum\SchemaParams;

class Translate extends AbstractExtension
{
  /** @var Translator */
  private $translator;

  /**
   * Constructor
   *
   * @param array $lang
   */
  public function __construct(array $lang = [])
	{
    $this->translator = new Translator($lang[SchemaParams::iso]);
  }

	/**
	* @return array
	*/
	public function getFilters(): array
	{
		return [
			new TwigFilter('translate', [$this, 'translate']),
		];
	}

	/**
	* @param string $key
	* @return string
	*/
	public function translate(string $key = ''): string
	{
		return $this->translator->translate(
      $key, Source::locale
    );
	}
}