<?php
namespace App\Kernel\Helpers;

/**
 * @source https://github.com/exoboy/pathkeys
 */
final class Pathkeys
{
  /**
   * MULTIDIMENSIONAL ARRAY PROPERTY VALUE GETTER
   *
   * @param	array	$source	the array to search in 
   * @param	string	$path = a string path, separated by ".", "prop1.prop2.prop3"
   * 
   * @return	mixed	$source	returns the value at that location, string/array/integer
   * 
   **/
  public static function get_prop_by_path(array $source = [], string $path = ''): mixed 
  {
    // break out path into an array to traverse
    $keys = explode('.', $path);

    // searches only the array elements in our path, does not iterate through ALL properties!
    foreach ($keys as $key) {
      // if the next element in our path does not exist, then it is a bad path, return null
      if (!array_key_exists($key, $source)) {
        // not found
        return NULL; 
      }
      // capture the next array element value and keep checking until the end of path
      $source = $source[$key];
    }

    // returns the last value found, not the original array
    return $source;
  }

  /**
   * MULTIDIMENSIONAL ARRAY PROPERTY VALUE SETTER
   * Uses a string path, separated by "." to locate a matching property and SET its value
   * 
   * @param	array	$source	the array to search in, is passed as a pointer, so we can alter the value of our target property
   * @param	string	$path = a string path, separated by ".", "prop1.prop2.prop3"
   * @param	mixed	$value = what you want to write to this property
   * @param	boolean	$append = true to push value into array (if an indexed or associative array), false = replace entire property value with new value
   * 
   * @return boolean	this function uses a pointer to the array, so no result is required to be returned, but true is for success, false/null is for issues
   * 
   **/
  public static function set_prop_by_path(array &$source = [], string $path = '', mixed $value = null, bool $append = false): bool
  {
    // break out path into an array to traverse
    $keys = explode('.', $path);

    // searches only the array elements in our path, does not iterate through ALL properties!
    foreach($keys as $key) {
      // if the next element in our path does not exist, then it is a bad path, return null
      if (!array_key_exists($key, $source)) {
        // not found
        return NULL; 
      }
      // capture the next array element value and keep checking until the end of path
      $source = &$source[$key];
    }

    if (is_array($source)) {
      switch (true) {
        case $append == "append" || $append == "push":
          array_push($source, $value);
          break;

        case $append == "prepend" || $append == "unshift":
          array_unshift($source, $value);
          break;

        default:
          array_push($source, $value);
      }

    } else {
      $source = $value;
    }
    // returns the last value found, not the original array
    return true;
  }
}