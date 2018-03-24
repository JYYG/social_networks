<?php

namespace Drupal\social_networks\Utils\SocialMedia;

/**
 * Class InstagramManager.
 *
 * @category Service
 * @author Adfab <dev@adfab.fr>
 * @license All right reserved
 * @link Null
 * @package Drupal\social_networks
 */
class InstagramManager extends AbstractSocialMedia {

  protected $id;
  protected $key;

  protected $count;
  protected $user;
  protected $content;
  protected $avatar;

  /**
   * Get count.
   *
   * @return mixed
   *    Return number of likes as a string.
   */
  public function getCount() {
    // Connection.
    $this->connection();

    // Get Count.
    $url = 'https://api.instagram.com/v1/users/self/?access_token=' . $this->key;
    $get = file_get_contents($url);
    $data = json_decode($get, TRUE);
    $count = $data['data']['counts']['followed_by'];

    return $count;
  }

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
  public function import($params) {
    // Connection.
    $this->connection();

    // Get Feed.
    $url = "https://api.instagram.com/v1/users/self/media/recent/?access_token=$this->key&count=" .
      ($params['start'] + $params['count']);
    $get = file_get_contents($url);
    $content = json_decode($get, TRUE);

    // Feed Array Constructor.
    $i = 0;

    foreach ($content['data'] as $item) {
      if ($i < $params['start'] || $i >= ($params['start'] + $params['count'])) {
        $i++;
        continue;
      }
      $this->content[] = $item;
      $i++;
    }
  }

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
  public function hashtag($params) {
    // TODO: Implement hashtag() method.
  }

  /**
   * Convert data structure to a standard array.
   *
   * @return array
   *    Return refactored news feed as array.
   */
  public function toArray(): array {
    $newsFeed = [];
    foreach ($this->content as $item) {
      $newsFeed[] = [
        'type' => 'instagram',
        'id' => $item['user']['full_name'],
        'avatar' => $item['user']['profile_picture'],
        'title' => $item['name'],
        'content' => $item['description'],
        'image' => $item['images']['thumbnail']['url'],
        'video' => $item['videos']['standard_resolution']['url'],
        'link' => $item['link'],
      ];
    }

    return $newsFeed;
  }

  /**
   * Connection to the API.
   */
  private function connection() {
    // Config.
    $config = \Drupal::config('social_networks.settings');
    $this->key = $config->get('instagram_token');
  }

}
