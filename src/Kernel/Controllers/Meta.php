<?php
namespace App\Kernel\Controllers;

use App\Kernel\Router\EventHttpRequest;
use App\Kernel\Controllers\Enum\MetaSchema;
use App\Kernel\Language\Enum\SchemaParams;
use App\Kernel\Language\Language;

final class Meta
{
  /** @var string */
  private $baseURL = '';

  /** @var string */
  private $url = '';

  /** @var string */
  private $langISO = '';

  /** @var array */
  private $conf = [];

  /** @var array */
  private $schema = [];

  /** @var array */
  private $metadata = [];

  /** @var string */
  private $defaultGenerator = 'UNIVERSAL.lite';

  /** @var string */
  private $defaultApplicationName = 'UNIVERSAL.lite';

  /** @var string */
  private $defaultRobots = 'index, follow';

  /** @var string */
  private $defaultGooglebot = 'all';

  /**
   * Constructor
   *
   * @param array $conf
   * @param array $schema
   * @param string $url
   */
  public function __construct(array $conf = [], array $schema = [], string $url = '')
  {
    $this->baseURL = EventHttpRequest::getBaseURL();
    $this->langISO = (new Language())->getWeb()[SchemaParams::iso];
    $this->conf    = $conf;
    $this->schema  = $schema;
    $this->url     = $url;
  }

  /**
   * @return array
   */
  public function build(): array
  {
    // @creator
    $this->creator();

    // @publisher
    $this->publisher();

    // @generator
    $this->generator();

    // @applicationName
    $this->applicationName();

    // @robots
    $this->robots();

    // @googlebot
    $this->googlebot();

    // @baseMeta
    $this->baseMeta();

    // @schemaOrg
    $this->schemaOrg();

    // @openGraph
    $this->openGraph();

    // @twitter
    $this->twitter();

    // @return;
    return [MetaSchema::ROOT_KEY => $this->metadata];
  }

  /**
   * Creator
   *
   * @return void
   */
  private function creator(): void
  {
    if (isset($this->schema[MetaSchema::META_CREATOR])) {
      $this->metadata[MetaSchema::META_CREATOR] = $this->schema[MetaSchema::META_CREATOR];
    } elseif (isset($this->conf[MetaSchema::META_CREATOR])) {
      $this->metadata[MetaSchema::META_CREATOR] = $this->conf[MetaSchema::META_CREATOR];
    } else {
      $this->metadata[MetaSchema::META_CREATOR] = '';
    }
  }

  /**
   * Publisher
   *
   * @return void
   */
  private function publisher(): void
  {
    if (isset($this->schema[MetaSchema::META_PUBLISHER])) {
      $this->metadata[MetaSchema::META_PUBLISHER] = $this->schema[MetaSchema::META_PUBLISHER];
    } elseif (isset($this->conf[MetaSchema::META_PUBLISHER])) {
      $this->metadata[MetaSchema::META_PUBLISHER] = $this->conf[MetaSchema::META_PUBLISHER];
    } else {
      $this->metadata[MetaSchema::META_PUBLISHER] = '';
    }
  }

  /**
   * Generator
   *
   * @return void
   */
  private function generator(): void
  {
    if (isset($this->schema[MetaSchema::META_GENERATOR])) {
      $this->metadata[MetaSchema::META_GENERATOR] = $this->schema[MetaSchema::META_GENERATOR];
    } elseif (isset($this->conf[MetaSchema::META_GENERATOR])) {
      $this->metadata[MetaSchema::META_GENERATOR] = $this->conf[MetaSchema::META_GENERATOR];
    } else {
      $this->metadata[MetaSchema::META_GENERATOR] = $this->defaultGenerator;
    }
  }

  /**
   * ApplicationName
   *
   * @return void
   */
  private function applicationName(): void
  {
    if (isset($this->schema[MetaSchema::META_APPLICATION_NAME])) {
      $this->metadata[MetaSchema::META_APPLICATION_NAME] = $this->schema[MetaSchema::META_APPLICATION_NAME];
    } elseif (isset($this->conf[MetaSchema::META_APPLICATION_NAME])) {
      $this->metadata[MetaSchema::META_APPLICATION_NAME] = $this->conf[MetaSchema::META_APPLICATION_NAME];
    } else {
      $this->metadata[MetaSchema::META_APPLICATION_NAME] = $this->defaultApplicationName;
    }
  }

  /**
   * Robots
   *
   * @return void
   */
  private function robots(): void
  {
    if (isset($this->schema[MetaSchema::META_ROBOTS])) {
      $this->metadata[MetaSchema::META_ROBOTS] = $this->schema[MetaSchema::META_ROBOTS];
    } elseif (isset($this->conf[MetaSchema::META_ROBOTS])) {
      $this->metadata[MetaSchema::META_ROBOTS] = $this->conf[MetaSchema::META_ROBOTS];
    } else {
      $this->metadata[MetaSchema::META_ROBOTS] = $this->defaultRobots;
    }
  }

  /**
   * Googlebot
   *
   * @return void
   */
  private function googlebot(): void
  {
    if (isset($this->schema[MetaSchema::META_GOOGLEBOT])) {
      $this->metadata[MetaSchema::META_GOOGLEBOT] = $this->schema[MetaSchema::META_GOOGLEBOT];
    } elseif (isset($this->conf[MetaSchema::META_GOOGLEBOT])) {
      $this->metadata[MetaSchema::META_GOOGLEBOT] = $this->conf[MetaSchema::META_GOOGLEBOT];
    } else {
      $this->metadata[MetaSchema::META_GOOGLEBOT] = $this->defaultGooglebot;
    }
  }

  /**
   * Base Meta
   *
   * @return void
   */
  private function baseMeta(): void
  {
    $this->baseTitle();
    $this->baseKeywords();
    $this->baseDescription();
  }

  /**
   * Base Title
   *
   * @return void
   */
  private function baseTitle(): void
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TITLE])) {
      $this->metadata[MetaSchema::META_TITLE] = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TITLE];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TITLE])) {
      $this->metadata[MetaSchema::META_TITLE] = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TITLE];
    } else {
      $this->metadata[MetaSchema::META_TITLE] = '';
    }
  }

  /**
   * Base Keywords
   *
   * @return void
   */
  private function baseKeywords(): void
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_KEYWORDS])) {
      $this->metadata[MetaSchema::META_KEYWORDS] = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_KEYWORDS];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_KEYWORDS])) {
      $this->metadata[MetaSchema::META_KEYWORDS] = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_KEYWORDS];
    } else {
      $this->metadata[MetaSchema::META_KEYWORDS] = '';
    }
  }

  /**
   * Base Description
   *
   * @return void
   */
  private function baseDescription(): void
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_DESCRIPTION])) {
      $this->metadata[MetaSchema::META_DESCRIPTION] = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_DESCRIPTION];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_DESCRIPTION])) {
      $this->metadata[MetaSchema::META_DESCRIPTION] = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_DESCRIPTION];
    } else {
      $this->metadata[MetaSchema::META_DESCRIPTION] = '';
    }
  }

  /**
   * SchemaOrg
   *
   * @return void
   */
  private function schemaOrg(): void
  {
    $this->schemaOrgName();
    $this->schemaOrgTitle();
    $this->schemaOrgKeywords();
    $this->schemaOrgDescription();
    $this->schemaOrgImage();
  }

  /**
   * SchemaOrg Name
   *
   * @return void
   */
  private function schemaOrgName(): void
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_NAME])) {
      $this->metadata[MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_NAME] = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_NAME];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_NAME])) {
      $this->metadata[MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_NAME] = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_NAME];
    } else {
      $this->metadata[MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_NAME] = '';
    }
  }

  /**
   * SchemaOrg Title
   *
   * @return void
   */
  private function schemaOrgTitle(): void 
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_TITLE])) {
      $this->metadata[MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_TITLE] = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_TITLE];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_TITLE])) {
      $this->metadata[MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_TITLE] = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_TITLE];
    } else {
      $this->metadata[MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_TITLE] = '';
    }
  }

  /**
   * SchemaOrg Keywords
   *
   * @return void
   */
  private function schemaOrgKeywords(): void 
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_KEYWORDS])) {
      $this->metadata[MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_KEYWORDS] = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_KEYWORDS];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_KEYWORDS])) {
      $this->metadata[MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_KEYWORDS] = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_KEYWORDS];
    } else {
      $this->metadata[MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_KEYWORDS] = '';
    }
  }

  /**
   * SchemaOrg Description
   *
   * @return void
   */
  private function schemaOrgDescription(): void 
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_DESCRIPTION])) {
      $this->metadata[MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_DESCRIPTION] = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_DESCRIPTION];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_DESCRIPTION])) {
      $this->metadata[MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_DESCRIPTION] = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_DESCRIPTION];
    } else {
      $this->metadata[MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_DESCRIPTION] = '';
    }
  }

  /**
   * SchemaOrg Image
   *
   * @return void
   */
  private function schemaOrgImage(): void 
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_IMAGE])) {
      $this->metadata[MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_IMAGE] = $this->baseURL.$this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_IMAGE];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_IMAGE])) {
      $this->metadata[MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_IMAGE] = $this->baseURL.$this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_IMAGE];
    } else {
      $this->metadata[MetaSchema::META_SCHEMA_ORG][MetaSchema::META_SCHEMA_ORG_IMAGE] = '';
    }
  }

  /**
   * OpenGraph
   *
   * @return void
   */
  private function openGraph(): void
  {
    $this->openGraphLocale();
    $this->openGraphType();
    $this->openGraphURL();
    $this->openGraphSiteName();
    $this->openGraphArticlePublisher();
    $this->openGraphTitle();
    $this->openGraphDescription();
    $this->openGraphImages();
  }

  /**
   * OpenGraph Locale
   *
   * @return void
   */
  private function openGraphLocale(): void 
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_LOCALE])) {
      $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_LOCALE] = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_LOCALE];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_LOCALE])) {
      $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_LOCALE] = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_LOCALE];
    } else {
      $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_LOCALE] = '';
    }
  }

  /**
   * OpenGraph Type
   *
   * @return void
   */
  private function openGraphType(): void 
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_TYPE])) {
      $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_TYPE] = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_TYPE];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_TYPE])) {
      $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_TYPE] = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_TYPE];
    } else {
      $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_TYPE] = '';
    }
  }

  /**
   * OpenGraph URL
   *
   * @return void
   */
  private function openGraphURL(): void 
  {
    $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_URL] = $this->baseURL.$this->url;
  }

  /**
   * OpenGraph Site Name
   *
   * @return void
   */
  private function openGraphSiteName(): void 
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_SITE_NAME])) {
      $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_SITE_NAME] = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_SITE_NAME];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_SITE_NAME])) {
      $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_SITE_NAME] = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_SITE_NAME];
    } else {
      $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_SITE_NAME] = '';
    }
  }

  /**
   * OpenGraph Article Publisher
   *
   * @return void
   */
  private function openGraphArticlePublisher(): void 
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_ARTICLE_PUBLISHER])) {
      $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_ARTICLE_PUBLISHER] = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_ARTICLE_PUBLISHER];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_ARTICLE_PUBLISHER])) {
      $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_ARTICLE_PUBLISHER] = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_ARTICLE_PUBLISHER];
    } else {
      $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_ARTICLE_PUBLISHER] = '';
    }
  }

  /**
   * OpenGraph Title
   *
   * @return void
   */
  private function openGraphTitle(): void 
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_TITLE])) {
      $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_TITLE] = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_TITLE];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_TITLE])) {
      $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_TITLE] = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_TITLE];
    } else {
      $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_TITLE] = '';
    }
  }

  /**
   * OpenGraph Description
   *
   * @return void
   */
  private function openGraphDescription(): void 
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_DESCRIPTION])) {
      $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_DESCRIPTION] = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_DESCRIPTION];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_DESCRIPTION])) {
      $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_DESCRIPTION] = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_DESCRIPTION];
    } else {
      $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_DESCRIPTION] = '';
    }
  }

  /**
   * OpenGraph Images
   *
   * @return void
   */
  private function openGraphImages(): void 
  {
    $images = [];

    // @when
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_IMAGES])) {
      $images = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_IMAGES];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_IMAGES])) {
      $images = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_IMAGES];
    } else {
      $images = [];
    }

    // @each
    if (count($images) > 0) {
      foreach ($images as $image) {
        $this->metadata[MetaSchema::META_OPEN_GRAPH][MetaSchema::META_OPEN_GRAPH_IMAGES][] = [
          MetaSchema::META_OPEN_GRAPH_URL    => $this->baseURL.$image[MetaSchema::META_OPEN_GRAPH_URL],
          MetaSchema::META_OPEN_GRAPH_WIDTH  => $image[MetaSchema::META_OPEN_GRAPH_WIDTH],
          MetaSchema::META_OPEN_GRAPH_HEIGHT => $image[MetaSchema::META_OPEN_GRAPH_HEIGHT],
          MetaSchema::META_OPEN_GRAPH_ALT    => $image[MetaSchema::META_OPEN_GRAPH_ALT],
        ];
      }
    }
  }

  /**
   * Twitter
   *
   * @return void
   */
  private function twitter(): void
  {
    $this->twitterCard();
    $this->twitterSite();
    $this->twitterTitle();
    $this->twitterDescription();
    $this->twitterCreator();
    $this->twitterImage();
  }

  /**
   * Twitter Card
   *
   * @return void
   */
  private function twitterCard(): void 
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_CARD])) {
      $this->metadata[MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_CARD] = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_CARD];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_CARD])) {
      $this->metadata[MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_CARD] = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_CARD];
    } else {
      $this->metadata[MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_CARD] = '';
    }
  }

  /**
   * Twitter Site
   *
   * @return void
   */
  private function twitterSite(): void 
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_SITE])) {
      $this->metadata[MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_SITE] = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_SITE];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_SITE])) {
      $this->metadata[MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_SITE] = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_SITE];
    } else {
      $this->metadata[MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_SITE] = '';
    }
  }

  /**
   * Twitter Title
   *
   * @return void
   */
  private function twitterTitle(): void 
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_TITLE])) {
      $this->metadata[MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_TITLE] = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_TITLE];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_TITLE])) {
      $this->metadata[MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_TITLE] = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_TITLE];
    } else {
      $this->metadata[MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_TITLE] = '';
    }
  }

  /**
   * Twitter Description
   *
   * @return void
   */
  private function twitterDescription(): void 
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_DESCRIPTION])) {
      $this->metadata[MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_DESCRIPTION] = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_DESCRIPTION];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_DESCRIPTION])) {
      $this->metadata[MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_DESCRIPTION] = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_DESCRIPTION];
    } else {
      $this->metadata[MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_DESCRIPTION] = '';
    }
  }

  /**
   * Twitter Creator
   *
   * @return void
   */
  private function twitterCreator(): void 
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_CREATOR])) {
      $this->metadata[MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_CREATOR] = $this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_CREATOR];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_CREATOR])) {
      $this->metadata[MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_CREATOR] = $this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_CREATOR];
    } else {
      $this->metadata[MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_CREATOR] = '';
    }
  }

  /**
   * Twitter Image
   *
   * @return void
   */
  private function twitterImage(): void 
  {
    if (isset($this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_IMAGE])) {
      $this->metadata[MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_IMAGE] = $this->baseURL.$this->schema[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_IMAGE];
    } elseif (isset($this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_IMAGE])) {
      $this->metadata[MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_IMAGE] = $this->baseURL.$this->conf[MetaSchema::ROOT_LANG][$this->langISO][MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_IMAGE];
    } else {
      $this->metadata[MetaSchema::META_TWITTER][MetaSchema::META_TWITTER_IMAGE] = '';
    }
  }
}