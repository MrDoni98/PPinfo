<?php

namespace MrDoni98;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use _64FF00\PurePerms\event\PPGroupChangedEvent;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\Utils;

class Main extends PluginBase implements Listener{

   public function onEnable(){
       @mkdir($this->getDataFolder());
       $this->getServer()->getPluginManager()->registerEvents($this, $this);
       $this->getLogger()->info("PPinfo is loaded");
       $this->PP = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
       $this->config = new Config($this->getDataFolder() . "config.yml" , Config::YAML, Array(
	   'token' => '',
	   'group' => '',
	   'debug' => false));
       $this->token = $this->config->get("token");
       $this->vkGroup = $this->config->get("group");
	   $this->debug = $this->config->get("debug");
   }

   public function GroupChanged(PPGroupChangedEvent $event){
       $group = $event->getGroup();
       $player = $event->getPlayer();
       $name = $player->getName();
       $this->getLogger()->info("Игрок ". $name." сменил группу на ". $group);
	   $owner_id = 130963836;
       $this->wallPost("Игрок ". $name." сменил группу на ". $group);
   }

   public function wallPost($text) {
	    if($this->debug === true){
			$request = Utils::getURL("https://api.vk.com/method/wall.post?&owner_id=-".$this->vkGroup."&from_group=1&message=" .urlencode($text)."&access_token=".$this->token);
		    $this->getLogger()->info("Результат :" . var_dump(json_decode($request)));
		}
        Utils::getURL("https://api.vk.com/method/wall.post?&owner_id=-".$this->vkGroup."&from_group=1&message=" .urlencode($text)."&access_token=".$this->token);
	}

}