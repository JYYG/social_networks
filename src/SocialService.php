<?php

namespace Drupal\social_networks;

use \Drupal\social_networks\Utils\SocialMedia\SocialMediaFactory;
use Drupal\Core\Cache\CacheBackendInterface;

/**
 * Class SocialService.
 *
 * Social services used in Social Media Wall content type.
 *
 * @category Service
 * @author Adfab <dev@adfab.fr>
 * @license All right reserved
 * @link Null
 * @package Drupal\social_networks
 */
class SocialService {

  const TWITTER = 'twitter2';
  const FACEBOOK = 'facebook2';
  const YOUTUBE = 'youtube';
  const GOOGLE = 'google';
  const LINKEDIN = 'linkedin2';
  const PINTEREST = 'pinterest';
  const INSTAGRAM = 'instagram';

  /**
   * Get hashtag feed.
   *
   * @param array $params
   *    Array of parameters :
   *    - start
   *    - count
   *    - term.
   *
   * @return array
   *    Return an array of posts.
   */
  public function getHashtagFeed($params) {
    $factory = new SocialMediaFactory();
    $array = $factory->getHashtagFeed($params);

    return $array;
  }

  /**
   * Get Social Media last news relative to the type.
   *
   * @param array $params
   *    Array of parameters :
   *    - start
   *    - count
   *    - term.
   *
   * @return array
   *    Return an array of posts.
   */
  public function getSocialPosts($params) {
    $factory = new SocialMediaFactory();
    $media = $factory->getSocialMedia($params['type']);
    $media->import($params);
    $array = $media->toArray();

    return $array;
  }

  /**
   * Get Social Media page total of likes.
   *
   * @param string $type
   *    Social network type.
   *
   * @return string
   *    Return count as a string.
   */
  public function getSocialLikes($type) {
    $factory = new SocialMediaFactory();
    $media = $factory->getSocialMedia($type);
    $count = $media->getCount();
    $counter = $factory->countToString($count);

    // Set $result in Drupal cache.
    \Drupal::cache()->set(
      $type."_count",
      $counter,
      CacheBackendInterface::CACHE_PERMANENT
    );

    return $counter;
  }

  /**
   * Save social token.
   *
   * @param $type
   * @param $params
   */
  public function saveSocialToken($type, $params) {
    $factory = new SocialMediaFactory();
    $token = $factory->getToken($type, $params);

    switch ($type) {

      case 'linkedin':
        \Drupal::configFactory()->getEditable('social_networks.settings')
          ->set('linkedin_token', $token)
          ->save(TRUE);
        break;

    }

  }

}
