<?php

namespace Drupal\social_networks\Utils\SocialMedia;

/**
 * Class PinterestManager.
 *
 * @category Service
 * @author Adfab <dev@adfab.fr>
 * @license All right reserved
 * @link Null
 * @package Drupal\social_networks
 */
class PinterestManager extends AbstractSocialMedia {

  protected $id;
  protected $key;
  protected $context;

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
    $url = "https://api.pinterest.com/v1/me?fields=counts";
    $get = file_get_contents($url, FALSE, $this->context);
    $data = json_decode($get, TRUE);

    $count = $data['data']['counts']['followers'];
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
    $url = 'https://api.pinterest.com/v1/me/boards/?fields=id%2Cname%2Curl%2Cdescription%2Cimage';
    $get = file_get_contents($url, FALSE, $this->context);
    $content = json_decode($get, TRUE);

    // Get User.
    $this->getUser();

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
    // Connection.
    $this->connection();

    // Get Feed.
    $term = $params['term'];
    $url = "https://api.pinterest.com/v1/me/search/boards/?query=$term&fields=id%2Cname%2Curl%2Cdescription%2Cimage";
    $get = file_get_contents($url, FALSE, $this->context);
    $content = json_decode($get, TRUE);

    // Get User.
    $this->getUser();

    $this->content = $content['data'];
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
        'type' => 'pinterest',
        'id' => $this->user['data']['username'],
        'avatar' => $this->user['data']['image']['60x60']['url'],
        'title' => $item['name'],
        'content' => $item['description'],
        'image' => $item['image']['60x60']['url'],
        'link' => $item['url'],
      ];
    }

    return $newsFeed;
  }

  /**
   * Get account information.
   */
  private function getUser() {
    // Get User Information.
    $url = 'https://api.pinterest.com/v1/me/?fields=username%2Cimage';
    $get = file_get_contents($url, FALSE, $this->context);
    $this->user = json_decode($get, TRUE);
  }

  /**
   * Connection to the API.
   */
  private function connection() {
    // Config.
    $config = \Drupal::config('social_networks.settings');
    $this->id = $config->get('pinterest_id');
    $this->key = $config->get('pinterest_token');
    $authorization = "Authorization: Bearer $this->key";
    $this->context = stream_context_create(
      array(
        'http' => array(
          'method' => 'GET',
          'header' => $authorization,
        ),
      )
    );
  }

}
