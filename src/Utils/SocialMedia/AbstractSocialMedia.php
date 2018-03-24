<?php

namespace Drupal\social_networks\Utils\SocialMedia;

/**
 * Class AbstractSocialMedia.
 *
 * @category Service
 * @author Adfab <dev@adfab.fr>
 * @license All right reserved
 * @link Null
 * @package Drupal\social_networks
 */
abstract class AbstractSocialMedia {

  /**
   * Get count.
   *
   * @return mixed
   *    Return number of likes as a string.
   */
  abstract public function getCount();

  /**
   * Import posts feed.
   *
   * @param array $params
   *    Array of parameters :
   *    - start
   *    - count.
   *
   * @return mixed
   *    Array of posts.
   */
  abstract public function import($params);

  /**
   * Import posts feed relative to the term.
   *
   * @param array $params
   *    Array of parameters :
   *    - start
   *    - count.
   *
   * @return mixed
   *    Array of posts as JSON.
   */
  abstract public function hashtag($params);

  /**
   * Convert data structure to a standard array.
   *
   * @return array
   *    Array of posts as JSON.
   */
  abstract public function toArray(): array;

}
