<?php
// use Sitemap;

class SitemapCommand extends CConsoleCommand
{
	
	
	private $domain = 'http://foodtalk.in/';
	
	
	public function actionIndex() {		
		
		$site_map =  new Sitemap('sitemaps.xml', $this->domain);
		
		$loc = new url();
		$loc->loc($this->domain . 'sitemap.xml');
		$site_map->AppendUrl($loc->getUrl(), array(),date("Y-m-d"));
		
		$loc = new url();
		$loc->loc($this->domain . 'user.xml');
		$site_map->AppendUrl($loc->getUrl(), array(),date("Y-m-d"));
		
		$loc = new url();
		$loc->loc($this->domain . 'resturant.xml');
		$site_map->AppendUrl($loc->getUrl(), array(),date("Y-m-d"));
		
		$loc = new url();
		$loc->loc($this->domain . 'dish.xml');
		$site_map->AppendUrl($loc->getUrl(), array(),date("Y-m-d"));
		$site_map->Save();
		
		
	}
	
	public function actionUser() {
		
		$file_path='user.xml';
		
		$site_map =  new Sitemap($file_path, $this->domain);
		
		$criteria = new CDbCriteria;
		$criteria->select = 't.userName'; // select fields which you want in output
		$criteria->condition = 't.isActivated = 1 and isDisabled= 0 and t.userName is not null';
		$users = User::model()->findAll($criteria);

		foreach ($users as $usr){
			$loc = new url();
			$loc->loc($this->domain . $usr->userName);
// 			$loc->changefreq('weekly');
// 			$loc->priority('1')
// 			$loc->lastmod($lastmod)
			$site_map->AppendUrl($loc->getUrl(), array(),date("Y-m-d"));
			
		}
		$site_map->Save();
		
	}
	
	public function actionResturant() {
		
		$file_path='resturant.xml';
		
		$site_map =  new Sitemap($file_path, $this->domain);
		
		$criteria = new CDbCriteria;
		$criteria->select = 't.id'; // select fields which you want in output
		$criteria->condition = 't.isActivated = 1 and isDisabled= 0';
		$objects = Restaurant::model()->findAll($criteria);
		
		foreach ($objects as $obj){
			$loc = new url();
			$loc->loc($this->domain . $obj->id);
			$site_map->AppendUrl($loc->getUrl(), array(),date("Y-m-d"));
				
		}
		$site_map->Save();
		
	}
	
// 	public function actionDish() {
		
// 		$file_path='dish.xml';
		
// 		$site_map =  new Sitemap($file_path, $this->domain);
		
// 		$objects = Dish::listAll();
		
// 		foreach ($objects as $obj){
// 			$loc = new url();
			
// 			$loc->loc($this->domain .'dish/'. str_replace(' ', '-', $obj['name']));
			
// 			$site_map->AppendUrl($loc->getUrl(), array(),date("Y-m-d"));
				
// 		}
// 		$site_map->Save();
		
// 	}
	public function actionDish() {
	
		$file_path='dish.xml';
	
		$site_map =  new Sitemap($file_path, $this->domain);
	
		$objects = Dish::listAll(array('with'=>'checkin'));
	
		foreach ($objects as $obj){
			$loc = new url();
				
			$loc->loc($this->domain .'dish/'. $obj['url']);
// 			$loc->loc($this->domain .'dish/'. str_replace(' ', '-', $obj['name']));
			
			$site_map->AppendUrl($loc->getUrl(), array(),date("Y-m-d"));
	
		}
		$site_map->Save();
	
	}
	
	
}


class url {
	private $url = array();
	public function loc($loc) {
		$this->url['loc'] = strtolower($loc);
	}
	public function lastmod($lastmod) {
		$this->url['lastmod'] = $lastmod;
	}
	public function changefreq($changefreq) {
		$this->url['changefreq'] = $changefreq;
	}
	public function priority($priority) {
		$this->url['priority'] = $priority;
	}
	public function setAppIndex($href) {
		$this->url['app'] = strtolower($href);
	}
	public function getUrl() {
		return $this->url;
	}

}


class Sitemap {
	private $dom=null;
// 	private $root = null;
	private $file_path = null;
	public $open = true;

	function Sitemap($file_path,$domain){
		$this->open=true;
		$this->file_path='/var/www/webapp/public/'.$file_path;

		$this->dom = new DOMDocument();
		$this->dom->encoding = 'utf-8';
		$this->dom->xmlVersion = '1.0';
		$this->dom->formatOutput = true;

		$this->root = $this->dom->createElement('urlset');

		$attr_user_id = new DOMAttr('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
		$this->root->setAttributeNode($attr_user_id);

// 		$attr_user_id = new DOMAttr('xmlns:image','http://www.google.com/schemas/sitemap-image/1.1');
// 		$this->root->setAttributeNode($attr_user_id);

// 		$attr_user_id = new DOMAttr('xmlns:video','http://www.google.com/schemas/sitemap-video/1.1');
// 		$this->root->setAttributeNode($attr_user_id);

		$attr_user_id = new DOMAttr('xmlns:xhtml','http://www.w3.org/1999/xhtml');
		$this->root->setAttributeNode($attr_user_id);

		$url_node = $this->dom->createElement('url');
		$loc = $this->dom->createElement('loc',$domain);
		$url_node->appendChild($loc);

		$this->root->appendChild($url_node);

	}

	public function Save(){
		$this->dom->appendChild($this->root);
		$this->dom->save($this->file_path);
		$this->open = false;
	}

	private function xml_escape($s)
	{
		// 		$s = html_entity_decode($s, ENT_QUOTES, 'UTF-8');
		$s = htmlspecialchars($s, ENT_QUOTES);
		$s = htmlentities($s, ENT_QUOTES | ENT_IGNORE, "UTF-8");
		return $s;
	}


	public function AppendUrl(Array $loc, Array $images = null,$updated_time=null){

		if(!empty($loc)){
				
			$url_node=$this->dom->createElement('url');

			foreach ($loc as $key=>$value){


				if($key=='app'){

					$value = $this->xml_escape($value);
					$node = $this->dom->createElement( 'xhtml:link');
					$rel = new DOMAttr('rel','alternate');
					$node->setAttributeNode($rel);
					$href = new DOMAttr('href',$value);
					$node->setAttributeNode($href);


					$url_node->appendChild($node);

				}else{

					$value = $this->xml_escape($value);
					$node = $this->dom->createElement( $key, $value);
						
					$url_node->appendChild($node);
					if(!empty($updated_time)){
						$node = $this->dom->createElement('lastmod',$updated_time);
						$url_node->appendChild($node);
					}
				}
					
			}
				
			if(!empty($images)){

				foreach ($images as $image ){
						
					$image_node=$this->dom->createElement('image:image');
					// 					var_dump($image);
					foreach ($image as $key=>$value ){
						$value = $this->xml_escape($value);
						$image_tag = $this->dom->createElement('image:'.$key , $value);
						$image_node->appendChild($image_tag);
					}
					$url_node->appendChild($image_node);
				}
			}
			$this->root->appendChild($url_node);
		}

	}

}