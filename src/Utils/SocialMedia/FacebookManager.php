<?php

namespace Drupal\social_networks\Utils\SocialMedia;

/**
 * Class FacebookManager.
 *
 * @category Service
 * @author Adfab <dev@adfab.fr>
 * @license All right reserved
 * @link Null
 * @package Drupal\social_networks
 */
class FacebookManager extends AbstractSocialMedia {

  protected $id;
  protected $token;

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
    $data = json_decode(
      file_get_contents(
        'https://graph.facebook.com/v2.9/'
        . $this->id . '/?fields=fan_count&access_token='
        . $this->token
      ),
      TRUE
    );

    return $this->count = $data['fan_count'];
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
    $content = json_decode(
      file_get_contents(
        'https://graph.facebook.com/v2.9/'
        . $this->id . '/posts?access_token='
        . $this->token
      ),
      TRUE
    );

    // Get Avatar.
    $this->avatar = 'http://graph.facebook.com/'
      . $this->id . '/picture?type=square';

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
        'type' => 'facebook2',
        'id' => $this->id,
        'avatar' => $this->avatar,
        'created' => $item['created_time'],
        'content' => $item['message'],
        'image' => $item['picture'],
        'link' => $item['id'],
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
    $this->id = $config->get('facebook_id');
    $this->token = $config->get('facebook_token');
  }

}
