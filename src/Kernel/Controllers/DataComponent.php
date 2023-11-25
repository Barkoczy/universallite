<?php
namespace App\Kernel\Controllers;

use Symfony\Component\Yaml\Yaml;
use App\Kernel\Filesystem\Folder;
use App\Kernel\HttpDL\HttpDLClient;
use App\Kernel\Controllers\Meta;
use App\Kernel\Controllers\DataCommands;
use App\Kernel\Controllers\DataFunctions;
use App\Kernel\Exceptions\CannotReadFileFromFileSource;
use App\Kernel\HttpDL\Enum\SchemaParams as HttpDLSchemaParams;

final class DataComponent
{
  /** @var DataFunctions */
  private $f;

  /** @var DataCommands */
  private $cmd;

  /** @var array **/
  private $meta = [];

  /** @var array **/
  private $global = [];

  /** @var array **/
  private $schema = [];

  /** @var array **/
  private $data = [];

  /**
   * Constructor
   *
   * @param string|array $schema
   * @param string|array $meta
   * @param string $url
   */
  public function __construct(string|array $schema = null, string|array $meta = null, string $url = '')
  {
    // @confFiles
    $metaConf = Folder::getConfigPath().'/meta.yml';
    $templateConf = Folder::getConfigPath().'/template.yml';

    // @validate
    if (!is_file($metaConf))
      throw new CannotReadFileFromFileSource('Cannot read meta/base.yml configuration file due to insufficient permissions');
    if (!is_file($templateConf))
      throw new CannotReadFileFromFileSource('Cannot read template.yml configuration file due to insufficient permissions');

    // @functions
    $this->f = new DataFunctions();

    // @cmd
    $this->cmd = new DataCommands();

    // @global
		$this->global = Yaml::parseFile($templateConf)??[];

    // @schema
    $this->buildSchema($schema);

    // @meta
    $this->buildMeta(
      Yaml::parseFile($metaConf)??[], $meta, $url
    );
  }

  /**
   * Schema
   *
   * @param string $data
   * @return void
   */
  private function buildSchema(string|array $data = ''): void
  {
    if (is_array($data)) {
      $this->schema = $data;
    } else {
      if ($this->cmd->has($data)) {
        $res = $this->cmd->exec($data);

        if (is_array($res)) {
          $this->schema = $res;
        }
      }
    }
  }

  /**
   * Meta
   *
   * @param array $conf
   * @param string|array $meta
   * @param string $url
   * @return void
   */
  private function buildMeta(array $conf = [], string|array $meta = '', string $url = ''): void
  {
    // @schema
    $schema = [];

    if (is_array($meta)) {
      $schema = $meta;
    } else {
      if ($this->cmd->has($meta)) {
        $res = $this->cmd->exec($meta);

        if (is_array($res)) {
          $schema = $res;
        }
      }
    }

    // @build
    $this->meta = (new Meta($conf, $schema, $url))->build();
  }

  /**
   * Builder
   *
   * @return array
   */
  public function build(): array
  {
    // @global
    if (!$this->hasEmpty($this->global))
      $this->global();

    // @schema
    if (!$this->hasEmpty($this->schema))
      $this->schema();

    // @return
    return array_merge($this->meta, $this->data);
  }

  /**
   * Global
   *
   * @return void
   */
  private function global(): void
  {
    foreach ($this->global as $index => $v) {
      if (is_string($v)) {
        $this->data[$index] = $this->val($v);
      }
      if (is_array($v)){
        if ($this->hasQuery($v)) {
          $this->data[$index] = (new HttpDLClient($v))->fetch();
        } else {
          $this->data[$index] = $this->recursive($v);
        }
      }
    }
  }

  /**
   * Schema
   *
   * @return void
   */
  public function schema(): void
  {
    if (count($this->schema) == count($this->schema, COUNT_RECURSIVE)) {
      foreach ($this->schema as $index => $v) {
        $this->schemaParamParser($index, $v);
      }
    } else {
      foreach ($this->schema as $line) {
        foreach ($line as $index => $v) {
          $this->schemaParamParser($index, $v);
        }
      }
    }
  }

  /**
   * Schema param parser
   *
   * @param string $index
   * @param mixed $v
   * @return void
   */
  private function schemaParamParser(string $index = '', mixed $v = null): void
  {
    if (is_string($v)) {
      $this->data[$index] = $this->val($v);
    }
    if (is_array($v)) {
      if ($this->hasQuery($v)) {
        $this->data[$index] = (new HttpDLClient($v))->fetch();
      } else {
        $this->data[$index] = $v;
      }
    }
  }

  /**
   * Value or Function
   *
   * @param string $v
   * @return mixed
   */
  private function val(string $v = ''): mixed
  {
    // @func
    if ($this->f->has($v))
      return $this->f->get($v);

    // @cmd
    if ($this->cmd->has($v))
      return $this->cmd->exec(trim($v));

    // @default
    return trim($v);
  }

  /**
   * Recursive Array
   *
   * @param array $data
   * @return array
   */
  private function recursive(array $data = []): array
  {
    array_walk_recursive(
      $data,
      function (&$v) {
        $v = is_string($v) ? $this->val($v) : $v;
      }
    );
    return $data;
  }

  /**
   * Has query HttpDL schema
   *
   * @param array $v
   * @return boolean
   */
  private function hasQuery(array $v = []): bool
  {
    return HttpDLSchemaParams::query === array_key_first($v);
  }

  /**
   * Empty Array
   *
   * @param array $arr
   * @return bool
   */
  private function hasEmpty(array $arr = []): bool
  {
    return !is_array($arr) || 0 === count($arr);
  }
}
