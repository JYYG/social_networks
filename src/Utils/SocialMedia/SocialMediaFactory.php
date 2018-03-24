<?php

namespace Drupal\social_networks\Utils\SocialMedia;

/**
 * Class SocialMediaFactory.
 *
 * @category Service
 * @author Adfab <dev@adfab.fr>
 * @license All right reserved
 * @link Null
 * @package Drupal\social_networks
 */
class SocialMediaFactory {

  /**
   * Get social media relative to the type.
   *
   * @param string $type
   *    Get type of social media to return datas.
   *
   * @return \Drupal\social_networks\Utils\SocialMedia\AbstractSocialMedia
   *    Return $socialMedia.
   */
  public function getSocialMedia($type) {
    $socialMedia = NULL;
    switch ($type) {
      case 'twitter2':
        $socialMedia = new TwitterManager();
        break;

      case 'youtube':
        $socialMedia = new YoutubeManager();
        break;

      case 'google':
        $socialMedia = new GoogleManager();
        break;

      case 'linkedin2':
        $socialMedia = new LinkedInManager();
        break;

      case 'pinterest':
        $socialMedia = new PinterestManager();
        break;

      case 'instagram':
        $socialMedia = new InstagramManager();
        break;

      case 'facebook2':
        $socialMedia = new FacebookManager();
        break;

    }

    return $socialMedia;
  }

  /**
   * Get hashtag feed.
   *
   * @param array $params
   *    An array of parameters :
   *    - start
   *    - count
   *    - term.
   *
   * @return array
   *    Return a JSON array of posts.
   */
  public function getHashtagFeed($params) {
    $defaultParams = [
      'start' => 0,
      'count' => 8,
      'term' => 'eliot',
      'lang' => 'en',
    ];

    $params = array_merge($defaultParams, $params);

    $twitter = new TwitterManager();
    $youtube = new YoutubeManager();
    $google = new GoogleManager();
    $pinterest = new PinterestManager();

    $content = [];
    $twitter->hashtag($params);
    $youtube->hashtag($params);
    $google->hashtag($params);
    $pinterest->hashtag($params);

    $content = array_merge(
      $content,
      $twitter->toArray(),
      $youtube->toArray(),
      $pinterest->toArray(),
      $google->toArray()
    );

    // Feed Array Constructor.
    $i = 0;
    $array = [];

    foreach ($content as $item) {
      if ($i < $params['start'] || $i >= ($params['start'] + $params['count'])) {
        $i++;
        continue;
      }
      $array[] = $item;
      $i++;
    }

    return $array;
  }

  /**
   * Get Token.
   *
   * @param string $type
   * @param array $params
   *
   * @return string
   */
  public function getToken($type, $params) {

    switch ($type) {

      case 'linkedin':
        $social = new LinkedInManager();
        return $social->getToken($params['code']);

    }

  }

  /**
   * Return count to string.
   *
   * @param int $count
   *    Count number.
   *
   * @return string
   *    Return count as a string.
   */
  public function countToString($count) {
    $length = strlen($count);
    $count = ($length > 3) ?
      substr($count, 0, ($length - 3)) . ',' . substr(
        $count,
        ($length - 3),
        1
      ) . 'k'
      :
      $count;

    return $count;
  }

}
