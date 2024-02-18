<?php


class CurlReq{
  // set post fields
  private $url = '';
  private $options = [];
  public $response = '';
  private $post = [
    // 'username' => 'user1',
    // 'password' => 'passuser1',
    // 'gender'   => 1,
  ];

  public function __construct($url,array $options = [])
  {
      $this->url = $url;
      $this->options = $options;
      
  }

  public function _post(){
    $ch = curl_init($this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    // execute!
    $this->response = curl_exec($ch);
    curl_close($ch);
  }

  public function _get(){
    $ch = curl_init($this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    // execute!
    $this->response = curl_exec($ch);
    curl_close($ch);
  }
  
}

$curl = new CurlReq('http://localhost/ntrends/api/mail');
$curl->_post();
echo $curl->response;