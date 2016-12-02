<?php

namespace MrDoni98;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use _64FF00\PurePerms\event\PPGroupChangedEvent;
use pocketmine\Player;
use pocketmine\utils\TextFormat as F;
use pocketmine\utils\Config;
use pocketmine\utils\Utils;

class Main extends PluginBase implements Listener{

   public function onEnable(){
	   if(!$this->getServer()->getPluginManager()->getPlugin("PurePerms")){
		   $this->getLogger()->error("Установите PurePerms!");
		   $this->getServer ()->getPluginManager ()->disablePlugin ( $this );
		   return;
	   }
       @mkdir($this->getDataFolder());
       $this->getServer()->getPluginManager()->registerEvents($this, $this);
       $this->getLogger()->info(F::GREEN . "PPinfo is loaded");
       $this->config = new Config($this->getDataFolder() . "config.yml" , Config::YAML, Array(
	   'token' => 'abcd',
	   'group' => '1234',
	   'debug' => false,
	   'text' => 'Игрок %name% получил привилегию %group%'));
       $this->token = $this->config->get("token");
       $this->vkGroup = $this->config->get("group");
	   $this->debug = $this->config->get("debug");
	   $this->text = $this->config->get("text");
   }

   public function GroupChanged(PPGroupChangedEvent $event){
       $group = $event->getGroup();
       $player = $event->getPlayer();
       $name = $player->getName();
	   $text = str_ireplace(["%name%", "%group%"], [$name, $group], $this->text);
	   $this->getLogger()->info($text);
       $this->wallPost($text);
   }

   public function wallPost($text) {
	    if($this->debug === true){
			$request = Utils::getURL("https://api.vk.com/method/wall.post?&owner_id=-".$this->vkGroup."&from_group=1&message=" .urlencode($text)."&access_token=".$this->token);
		    $this->getLogger()->info(F::YELLOW."Результат :" . var_dump(json_decode($request)));
		}
        Utils::getURL("https://api.vk.com/method/wall.post?&owner_id=-".$this->vkGroup."&from_group=1&message=" .urlencode($text)."&access_token=".$this->token);
	}

}
