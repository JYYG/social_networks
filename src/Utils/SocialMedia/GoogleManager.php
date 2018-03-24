<?php

namespace Drupal\social_networks\Utils\SocialMedia;

/**
 * Class GoogleManager.
 *
 * @category Service
 * @author Adfab <dev@adfab.fr>
 * @license All right reserved
 * @link Null
 * @package Drupal\social_networks
 */
class GoogleManager extends AbstractSocialMedia {

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
    $url = "https://www.googleapis.com/plus/v1/people/$this->id?key=$this->key";
    $data = json_decode(file_get_contents($url), TRUE);

    return $this->count = $data['circledByCount'];
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

    // Set max results.
    $maxResults = $params['start'] + $params['count'];

    // Get Feed.
    $url = "https://www.googleapis.com/plus/v1/people/$this->id/activities/public?key=$this->key&maxResults=$maxResults";
    $get = file_get_contents($url);
    $content = json_decode($get, TRUE);

    // Feed Array Constructor.
    $i = 0;

    foreach ($content['items'] as $item) {
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
    // Connection.
    $this->connection();

    // Get Feed.
    $term = $params['term'];
    $lang = $params['lang'];
    $url = "https://www.googleapis.com/plus/v1/people/$this->id/activities/public?key=$this->key&query=$term&language=$lang";
    $get = file_get_contents($url);
    $content = json_decode($get, TRUE);

    $this->content = $content['items'];
  }

  /**
   * Convert data structure to a standard array.
   *
   * @return array
   *    Return refactored news feed as array.
   */
  public function toArray(): array {
    // Get Avatar.
    $this->getAvatar();
    $newsFeed = [];
    foreach ($this->content as $item) {
      $newsFeed[] = [
        'type' => 'google',
        'id' => $item['actor']['displayName'],
        'avatar' => $this->avatar['image']['url'],
        'created' => $item['published'],
        'title' => $item['object']['attachments'][0]['displayName'],
        'content' => ($item['object']['content']) ? $item['object']['content']:$item['object']['attachments'][0]['content'],
        'image' => $item['object']['attachments'][0]['image']['url'],
        'link' => $item['url'],
      ];
    }

    return $newsFeed;
  }

  /**
   * Get account avatar.
   */
  private function getAvatar() {
    // Get Avatar.
    $url = "https://www.googleapis.com/plus/v1/people/$this->id?fields=image&key=$this->key";
    $get = file_get_contents($url);
    $this->avatar = json_decode($get, TRUE);
  }

  /**
   * Connection to the API.
   */
  private function connection() {
    // Config.
    $config = \Drupal::config('social_networks.settings');
    $this->id = $config->get('google_id');
    $this->key = $config->get('google_key');
  }

}
