<?php
class InstagramMedia {
  private $token;
  private $db;

  public function __construct(){
    $this->db = new DataBaseModel;
  }

  public function get_media_url() {
    $this->refresh_token();
    return $this->get_instagram_media_url();
  }

  private function get_instagram_media_url() {
    if (!$this->token) {
       return false;
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://graph.instagram.com/me/media?fields=id,media_type,media_url,thumbnail_url&access_token=' . $this->token);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($curl);
    $errno = curl_errno($curl);
    curl_close($curl);

    if ($errno !== CURLE_OK) {
       return false;
    }

    $result = json_decode($response, TRUE);
    return $result['data'][0]['media_url'];
  }

  private function refresh_token() {
    $token = $this->get_token();

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=' . $token);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($curl);
    $errno = curl_errno($curl);
    curl_close($curl);

    if ($errno !== CURLE_OK) {
       return false;
    }

    $result = json_decode($response, true);
    print_r($result);
    $token = $result['access_token'];

    $this->token = $token;
    $this->update_token($token);
  }

  private function get_token() {
    $result = $this->db->find('access_token','token', "app = 'instagram'");
    return $result['token'];
  }

  private function update_token($token) {
    $this->db->upd('access_token', array('token'=>$token), "app = 'instagram'");
  }
}
?>
