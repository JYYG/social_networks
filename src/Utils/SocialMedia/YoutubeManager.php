<?php

namespace Drupal\social_networks\Utils\SocialMedia;

/**
 * Class YoutubeManager.
 *
 * @category Service
 * @author Adfab <dev@adfab.fr>
 * @license All right reserved
 * @link Null
 * @package Drupal\social_networks
 */
class YoutubeManager extends AbstractSocialMedia {

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
    $url = "https://www.googleapis.com/youtube/v3/channels?part=statistics&id=$this->id&key=$this->key";
    $data = json_decode(file_get_contents($url), TRUE);

    return $this->count = $data['items'][0]['statistics']['subscriberCount'];
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
    $url = "https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&maxResults=$maxResults&channelId=$this->id&key=$this->key";
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

    // Get Search Feed.
    $term = $params['term'];
    $lang = $params['lang'];
    $url = "https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&maxResults=50&channelId=$this->id&key=$this->key&q=$term&relevanceLanguage=$lang";
    $get = file_get_contents($url);
    $content = json_decode($get, TRUE);

    $this->content = $content['items'];
  }

  /**
   * Convert data structure to a standard array.
   *
   * @return array
   *   Return refactored news feed as array.
   */
  public function toArray(): array {
    // Get Avatar.
    $this->getAvatar();
    $newsFeed = [];
    foreach ($this->content as $item) {
      $newsFeed[] = [
        'type' => 'youtube',
        'id' => $item['snippet']['channelTitle'],
        'avatar' => $this->avatar['items'][0]['snippet']['thumbnails']['default']['url'],
        'created' => $item['snippet']['publishedAt'],
        'title' => $item['snippet']['title'],
        'content' => $item['snippet']['description'],
        'image' => $item['snippet']['thumbnails']['medium']['url'],
        'media' => $item['id']['videoId'],
        'link' => 'https://www.youtube.com/embed/' . $item['id']['videoId'],
      ];
    }

    return $newsFeed;
  }

  /**
   * Get account avatar.
   */
  private function getAvatar() {
    // Get Avatar.
    $url = "https://www.googleapis.com/youtube/v3/channels?part=snippet&id=$this->id&fields=items%2Fsnippet%2Fthumbnails&key=$this->key";
    $get = file_get_contents($url);
    $this->avatar = json_decode($get, TRUE);
  }

  /**
   * Connection to the API.
   */
  private function connection() {
    // Config.
    $config = \Drupal::config('social_networks.settings');
    $this->id = $config->get('youtube_id');
    $this->key = $config->get('youtube_key');
  }

}
