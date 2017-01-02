<?php

# Plugin by EmreTr1

  /////////////////////////////////////
 ///// I N  D E V E L O P M E N T/////
/////////////////////////////////////
namespace BedWars;

# 2016  Professional BedWars PocketMine-MP Plugin By: Emre Yavuzyiğit(EmreTr1)

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\inventory\BaseTransaction;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\plugin\Plugin;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\tile\Tile;
use pocketmine\tile\Sign;
use pocketmine\tile\Chest;
use pocketmine\inventory\ChestInventory;
use pocketmine\inventory\PlayerInventory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag; 
use pocketmine\nbt\tag\StringTag;
use pocketmine\entity\Villager;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\scheduler\PluginTask;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\level\sound\PopSound;
use pocketmine\level\sound\GenericSound;
use pocketmine\level\sound\FizzSound;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\level\particle\SmokeParticle;
use pocketmine\level\particle\LargeExplodeParticle;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as c;

class BedWars extends PluginBase implements Listener{
	
	public $prefix = "§8[§4Bed§fWars§8]";
	public $bedmode=0;
	public $bm=0;
	public $game = "";
	public $uc = 0;
	public $teams = array();
        public $inGames=array();
        public $Shopping=array();
        public $akk=array();

    public function onEnable(){
	  $this->getServer()->getPluginManager()->registerEvents($this, $this);
	  $this->getServer()->getLogger()->info("§8[==========================================]");
	  $this->getServer()->getLogger()->info("§8[§cBed§fWars§4TR§8]§a Plugin aktifleştirildi");
	  $this->getServer()->getLogger()->info("§8[§cBed§fWars§4TR§8]§e - plugin developer by §bEmreTr1");
	  $this->getServer()->getLogger()->info("§8[==========================================]");
          $this->saniye=0;
	  $this->mode=0;
	  $this->create=0;
	  @mkdir($this->getDataFolder());
          $this->config = new Config($this->getDataFolder() . "config.json", Config::JSON);
	  $this->stats = new Config($this->getDataFolder() . "stats.json", Config::JSON);
          $this->shop = new Config($this->getDataFolder() . "shop.yml", Config::YAML);
	  $this->config->save();
         $this->players=array();
         if(!$this->shop->get("BedWars-Shop")){
             $this->shop->set("BedWars-Shop", array(
                    Item::STONE_SWORD,
                    array(
                        array(
                        268, 0, 1, 30),
                        array(
                        272, 0, 1, 60),
                        array(
                        267, 0, 1, 100),
                            array(
                        283, 0, 1, 100),
                                array(
                        276, 0, 1, 300),
                                    array(
                        261, 0, 1, 100),
                                        array(
                        262, 0, 24, 20),
                    ),
                    Item::IRON_CHESTPLATE,
                    array(
                        array(
                        298, 0, 1, 25),
                            array(
                        299, 0, 1, 50),
                                array(
                        300, 0, 1, 40),
                                    array(
                        301, 0, 1, 25),
                                        array(
                        306, 0, 1, 50),
                                            array(
                        307, 0, 1, 80),
                                                array(
                        308, 0, 1, 60),
                                                    array(
                        309, 0, 1, 50),
                    ),
                    Item::SANDSTONE,
                    array(
                        array(
                        24, 0, 20, 20),
                            array(
                        30, 0, 10, 30),
                                array(
                        49, 0, 5, 50),
                    ),
                    Item::APPLE,
                    array(
                        array(
                        260, 0, 5, 10),
                            array(
                        297, 0, 5, 10),
                                array(
                        360, 0, 10, 30),
                                    array(
                        364, 0, 5, 50),
                                        array(
                        366, 0, 5, 30),
                                            array(
                        354, 0, 1, 70),
                    ),
                )
            );
             $this->shop->save();         
           }
    }
 
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
        switch ($args[0]){
            case "help":
            $sender->sendMessage("§6©=======§cBed§fWars§6(§ePAGE 1/1§6)=======@");
            $sender->sendMessage("§6Tüm Komutlar:");
            $sender->sendMessage("§d- /bw start :§e Oyunu Başlatır(Adminler İçin)");
            $sender->sendMessage("§b- /bw join :§e BedWarsa Katılınılır.");
            $sender->sendMessage(TextFormat::GOLD."- /bw lobby :" .TextFormat::YELLOW. "Lobbye İsinlar.");
            $sender->sendMessage("§b- /bw create :§eOyun Kurar.");
            $sender->sendMessage("§6©==================================©");
            break;   
            case "start":
            $this->saniye=10;
            $this->jj=1;
            $sender->sendMessage($this->prefix." §dOyun Başlatılıyor..");
			if($sender instanceof Player){
			}else{
			    $this->getServer()->getLogger()->warning("Sen Oyuncu değilsin.");
			}
            break;
      case "join":
	    if(!empty($args[1]) and ($this->config->getNested("BedWars.$args[1]")) and (!isset($this->players[$args[1]][$sender->getName()]))){
                if(!empty($args[2])){
                  $sender=$this->getServer()->getPlayer($args[2]);
                }
            $sender->setExpLevel(0);
            $sender->setNameTag($sender->getNameTag());
            $sender->setHealth(20);
            $sender->setFood(20);
            $game=$args[1];
	    $config=$this->config->getNested("BedWars.$game.Settings.lobbypos");
            $level=$this->getServer()->getLevelByName($config["level"]);;
            $lobby=new Position($config["lobbypos_x"], $config["lobbypos_y"], $config["lobbypos_z"], $level);
            $this->spawners[$game]=false;
            $map=$this->config->getNested("BedWars.$game.Settings.lobbypos.level");
            if($this->getServer()->loadLevel($map) != false){
			$this->getServer()->loadLevel($map);
		    }
	    $sender->teleport($lobby);
            $sender->sendMessage($this->prefix."§aOyun Katıldın!");
            $this->inGames[$sender->getName()]=$game;
	    $sender->getLevel()->addSound(new PopSound($sender));
	    $sender->getLevel()->addParticle(new LargeExplodeParticle(new Position($sender->x, $sender->y, $sender->z)));
           if(!isset($this->players[$game])){
               $this->createGameTask($game);
               $this->players[$game][$sender->getName()]=array("id"=>$sender->getName());
               $pn=$sender->getName();
               $this->akk[$game]=0;
           }else{
               $this->players[$game][$sender->getName()]=array("id"=>$sender->getName());
               $pn=$sender->getName();

           }
           $pn=$sender->getName();
           $teams=array(
                   "§9[MAVI]",
                   "§c[KIRMIZI]",
                   "§e[SARI]",
                   "§a[YESIL]"
               );
            $this->akk[$game]++;
            $r=$this->akk[$game];
            $sender->setNameTag($teams[$r]."$pn");
            if($this->akk[$game]==4){
                $this->akk[$game]=0;
            }
		}else{
		    $sender->sendMessage($this->prefix."§eKullanış: /bw join <oyunadı>");
	    }
            break;
            case "set":
            if($sender instanceof Player){
		$this->game=$args[2];
		$game=$this->game;
            	if($this->mode==0 && (!$this->config->getNested("BedWars.$game.Settings.spectatorpos")) and strtolower($args[1])=="game" and (!empty($args[2]))){
    $a4=[
    Block::BED_BLOCK, 
    24,
    30,
    49,
    121,
    54];
    $this->config->setNested("BedWars.$game.Settings.PlaceableAndBreakableBlocks", $a4);
    $this->config->save();
					$this->mode=1;
					$this->create=1;
					$sender->sendMessage($this->prefix."§aKurulum, Lobbyposunu seçmek için bir yere tıkla!");
				}
				 else if($this->bedmode==0 and (!$this->config->getNested("BedWars.$game.Settings.Beds")) and strtolower($args[1])=="bed" and (!empty($args[2]))){
				 	$this->bedmode=1;
				 	$this->bm=1;
				 	$sender->sendMessage("$this->prefix §b$game icin Yataklar kurulmaya Hazir!");
				 	$sender->sendMessage($this->prefix."§c Kirmizi Yatagi Sec!");
				 }
				elseif(strtolower($args[1])=="spawner"){
                                    $sender->getInventory()->setItemInHand(Item::get(Item::SIGN));
                                    $sender->sendMessage($this->prefix."}n§6[BILGI]\n§a Spawnerleri kurmak icin Spawner olacak bloklarin\n"
                                            . "altina tabela konulmalidir.\n"
                                            . "Spawner Bronz ise;\n"
                                            . "BedWars\n"
                                            . "Spawner\n"
                                            . "Bronz\n\n"
                                            . "Spawner Demir ise;\n"
                                            . "BedWars\n"
                                            . "Spawner\n"
                                            . "Iron\n\n"
                                            . "Spawner Altin ise;\n"
                                            . "BedWars\n"
                                            . "Spawner\n"
                                            . "Gold\n\n"
                                            . "         Yazilmalidir.");
				}
            }
            break;
			case "stats":
			#BedWars Stats System:
                            $name=$sender->getName();
                            if($this->stats->getNested("BedWars_Stats.$name")){
                                $score=$this->stats->getNested("BedWars_Stats.$name.Skor");
                                $deaths=$this->stats->getNested("BedWars_Stats.$name.Olumler");
                                $kills=$this->stats->getNested("BedWars_Stats.$name.Oldurmeler");
                                $wins=$this->stats->getNested("BedWars_Stats.$name.Kazanmalar");
                                $sender->sendMessage($this->prefix."\n"
                                        . "§6--- �statistikler ---\n"
                                        . "§dKazanmalar: $wins\n"
                                        . "§eSkor: $skor\n"
                                        . "§cOlumler: $deaths\n"
                                        . "§aOldurmeler: $kills\n"
                                        . "§6--- �statistikler ---\n");
			}
                        break;
            default:
                $sender->sendMessage($this->prefix."§Yardim icin: /bw help");
                break;
        }
    }
	
    public function createGameTask($game){
        $t = new GameTask($this, $game);
        $h = $this->getServer()->getScheduler()->scheduleRepeatingTask($t, 20);
        $t->setHandler($h);
        
    }
    
	public function getTeams($game){
		foreach($this->players[$game] as $pl){
		$p=$this->getServer()->getPlayer($pl["id"]);
		$this->redteampos=$this->config->getNested("BedWars.$game.Settings.redteampos");
		 $this->yellowteampos=$this->config->getNested("BedWars.$game.Settings.yellowteampos");
		 $this->greenteampos=$this->config->getNested("BedWars.$game.Settings.greenteampos");
		 $this->blueteampos=$this->config->getNested("BedWars.$game.Settings.blueteampos");
		 $this->spectatorpos=$this->config->getNested("BedWars.$game.Settings.spectatorpos");
		 $level=$this->getServer()->getLevelByName($this->redteampos["level"]);
		 $this->redteampos=new Vector3($this->redteampos["redteam_x"], $this->redteampos["redteam_y"], $this->redteampos["redteam_z"]);
		 $this->yellowteampos=new Vector3($this->yellowteampos["yellowteam_x"], $this->yellowteampos["yellowteam_y"], $this->yellowteampos["yellowteam_z"]);
		 $this->greenteampos=new Vector3($this->greenteampos["greenteampos_x"], $this->greenteampos["greenteampos_y"], $this->greenteampos["greenteampos_z"]);
		 $this->blueteampos=new Vector3($this->blueteampos["blueteampos_x"], $this->blueteampos["blueteampos_y"], $this->blueteampos["blueteampos_z"]);
		$this->spectatorpos=new Vector3($this->spectatorpos["spectatorpos_x"], $this->spectatorpos["spectatorpos_y"], $this->spectatorpos["spectatorpos_z"]);
			 		$dn=$p->getName();
					 	if($p->getNameTag()=="§c[KIRMIZI]".$dn){
							$p->teleport($this->redteampos);
						}
						if($p->getNameTag()=="§9[MAVI]".$dn){
							 $p->teleport($this->blueteampos);
						}
						if($p->getNameTag()=="§e[SARI]".$dn){
							 $p->teleport($this->yellowteampos);
						}
						if($p->getNameTag()=="§a[YESIL]".$dn){
							 $p->teleport($this->greenteampos);
						}
		}
	}
        
        public function OnChat(PlayerChatEvent $event){
		$p=$event->getPlayer();
		if($p->getNameTag()==c::BLUE."[MAVI]".$p->getName()){
			$event->setFormat("§8[� §9MAVI �8]§d %s");
		}
                if($p->getNameTag()==c::RED."[KIRMIZI]".$p->getName()){
			$event->setFormat("§8[� §9MAVI �8]§d %s");
		}
                if($p->getNameTag()==c::YELLOW."[SARI]".$p->getName()){
			$event->setFormat("§8[� §9MAVI �8]§d %s");
		}
                if($p->getNameTag()==c::GREEN."[YESIL]".$p->getName()){
			$event->setFormat("§8[� §9MAVI �8]§d %s");
		}
                if(isset($this->inGames[$p->getName()])){
                    $event->setRecipients($p->getLevel()->getPlayers());
                }
	}
        
        public function OnQuit(PlayerQuitEvent $event){
            if(isset($this->inGames[$event->getPlayer()->getName()])){
                $game=$this->inGames[$event->getPlayer()->getName()];
		if(isset($this->players[$game][$event->getPlayer()->getName()])){
			unset($this->players[$game][$event->getPlayer()->getName()]);
                        unset($this->inGames[$event->getPlayer()->getName()]);
                        $p=$event->getPlayer();
                        $p->setHealth(20);
                        $p->setGamemode(0);
                        $p->removeAllEffects();
		        $p->setNameTag($p->getName());
                        $p->getInventory()->clearAll();
                        if(count($this->players[$game])>0){
			foreach($this->players[$game] as $pl){
				$p=$this->getServer()->getPlayer($pl["id"]);
				$p->sendMessage($this->prefix."§7" .$event->getPlayer()->getName(). " §8oyundan ayrildi.");
			}
                    }
		}
            }
	}
        
	public function OnInteract(PlayerInteractEvent $event){
		$player=$event->getPlayer();
		$block=$event->getBlock();
		$item=$event->getItem();
		$lev=$player->getLevel()->getFolderName();
		if($this->bedmode==1){
			$game=$this->game;
			$pos=[
			"x" =>$block->getX(),
			"y" =>$block->getY() + 1,
			"z" =>$block->getZ(),
			"level"=>$lev];
			if($block->getId()==Block::BED_BLOCK){
			switch($this->bm){
				case 1:
				    $this->config->setNested("BedWars.$game.Settings.Beds.Red", $pos);
				    $this->config->save();
				    $this->bm++;
				    $player->sendMessage($this->prefix."§cKirmizi Yatak Secildi!");
				    $player->sendMessage($this->prefix."§aYesil Yatagi Sec!");
				    break;
                                case 2:
				    $this->config->setNested("BedWars.$game.Settings.Beds.Green", $pos);
				    $this->config->save();
				    $this->bm++;
				    $player->sendMessage($this->prefix."§aYesil Yatak Secildi!");
				    $player->sendMessage($this->prefix."§eSari Yatagi Sec!");
				    break;
			        case 3:
				    $this->config->setNested("BedWars.$game.Settings.Beds.Yellow", $pos);
				    $this->config->save();
				    $this->bm++;
				    $player->sendMessage($this->prefix."§eSari Yatak Secildi!");
				    $player->sendMessage($this->prefix."§9Mavi Yatagi Sec!");
				    break;
				case 4:
				    $this->config->setNested("BedWars.$game.Settings.Beds.Blue", $pos);
				    $this->config->save();
				    $this->bm=0;
				    $this->bedmode=0;
				    $player->sendMessage($this->prefix."§9Mavi Yatak Secildi!");
				    $player->sendMessage($this->prefix."§6KURULUM TAMAMLANDİ.İYİ EYLENCELER...");
				    break;
			}
		 }
		}
		if($this->create==1){
			switch($this->mode){
				case 1:
				    $pos=[
					"lobbypos_x" =>$block->getX(),
					"lobbypos_y" =>$block->getY()+ 1,
					"lobbypos_z" =>$block->getZ(),
					"level"=>$lev];
				    $this->config->setNested("BedWars.$this->game.Settings.lobbypos",$pos);
					$this->config->save();
					$this->mode++;
					$player->sendMessage($this->prefix."§6Lobbypos Seçildi!");
					$player->sendMessage($this->prefix."§a�?imdi KırmızıTAKIM alanını seç.");
					break;				
			        case 2:
				    $pos=[
					"redteam_x" =>$block->getX(),
					"redteam_y" =>$block->getY() + 1,
					"redteam_z" =>$block->getZ(),
					"level"=>$lev];
				    $this->config->setNested("BedWars.$this->game.Settings.redteampos",$pos);
					$this->config->save();
					$this->mode++;
					$player->sendMessage($this->prefix."§cKırmızıTakım Seçildi!");
					$player->sendMessage($this->prefix."§a�?imdi SarıTakım alanını seç.");
					break;
				case 3:
				    $pos=[
					"yellowteam_x" =>$block->getX(),
					"yellowteam_y" =>$block->getY() + 1,
					"yellowteam_z" =>$block->getZ(),
					"level"=>$lev];
				    $this->config->setNested("BedWars.$this->game.Settings.yellowteampos",$pos);
					$this->config->save();
					$this->mode++;
					$player->sendMessage($this->prefix."§eSarıTakım Seçildi!");
					$player->sendMessage($this->prefix."§a�?imdi MaviTAKIM alanını seç.");
					break;
				case 4:
				    $pos=[
					"blueteampos_x" =>$block->getX(),
					"blueteampos_y" =>$block->getY() + 1,
					"blueteampos_z" =>$block->getZ(),
					"level"=>$lev];
				    $this->config->setNested("BedWars.$this->game.Settings.blueteampos",$pos);
					$this->config->save();
					$this->mode++;
					$player->sendMessage($this->prefix."§9blueteampos Seçildi!");
					$player->sendMessage($this->prefix."§a�?imdi greenteampos alanını seç.");
					break;
				case 5:
				    $pos=[
					"greenteampos_x" =>$block->getX(),
					"greenteampos_y" =>$block->getY() + 1,
					"greenteampos_z" =>$block->getZ(),
					"level"=>$lev];
				    $this->config->setNested("BedWars.$this->game.Settings.greenteampos",$pos);
					$this->config->save();
					$this->mode++;
					$player->sendMessage($this->prefix."§agreenteampos Seçildi!");
					$player->sendMessage($this->prefix."§a�?imdi spectatorpos alanını seç.");
					break;
				case 6:
				    $pos=[
					"spectatorpos_x" =>$block->getX(),
					"spectatorpos_y" =>$block->getY() + 1,
					"spectatorpos_z" =>$block->getZ(),
					"level"=>$lev];
				    $this->config->setNested("BedWars.$this->game.Settings.spectatorpos",$pos);
					$this->config->save();
					$this->mode++;
					$player->sendMessage($this->prefix."§dİzleyiciPos Seçildi!");
					$player->sendMessage($this->prefix."§aKurulum tamamlandı! �?imdi Bedwarsın tadını çıkar : /bw katıl");
					$this->create=0;
					$this->mode=0;
					break;
			}
		}
	}
	
	public function OnDamage(EntityDamageEvent $event){
			if($event instanceof EntityDamageByEntityEvent){
				$player=$event->getDamager();
				$villager=$event->getEntity();
				if($villager instanceof  Villager and isset($this->inGames[$player->getName()])){
					$event->setCancelled(true);
					$x=round($villager->getX());
			                $y=round($villager->getY() - 3);
			                $z=round($villager->getZ());
					if ($player->getLevel()->getBlockIdAt($x, $y, $z) != 54) {
					$player->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(54));
                                        $chest = new Chest($player->getLevel()->getChunk($x >> 4, $z >> 4, true), new CompoundTag(false, array(new IntTag("x", $x), new IntTag("y", $y), new IntTag("z", $z), new StringTag("id", Tile::CHEST))));
					$chest->setName($this->prefix."§a Market");
			                $player->getLevel()->addTile($chest);
					}else{
				   $chest2=$player->getLevel()->getTile(new Vector3($x, $y, $z));
                                   $chest2=new ChestInventory($chest2, $player);
				   $chest2->clearAll();
                    $items=$this->shop->get("BedWars-Shop");
                    $c=count($items);
                    for($i=0; $i < $c; $i+=2){
                        $slot = $i / 2;
                        $chest2->setItem($slot, Item::get($items[$i], 0, 1));
                    }
                        $player->addWindow($chest2);
                        $villager->setNameTag("§aMarket");
                        }
			}
		}
	}
        
        public function BlockBreakEvent(BlockBreakEvent $event){
 	 $p=$event->getPlayer();
	 $block=$event->getBlock();
         $blockid=$block->getId();
	 if(isset($this->inGames[$p->getName()])){
            $game=$this->inGames[$p->getName()];
             $pn=$p->getName();
             $blocks=$this->config->getNested("BedWars.$game.Settings.PlaceableAndBreakableBlocks");
                if(!isset($blocks[$blockid])){
                    $event->setCancelled(true);
                    return;
             }
             if($block->getId()==Block::BED_BLOCK){
                 $tiles=$p->getLevel()->getTiles();
                 foreach($tiles as $tile){
                     if($tile instanceof Sign){
                         $x=$tile->x;
                         $y=$tile->y + 2;
                         $z=$tile->z;
                         if($x=$block->x and $y==$block->y and $z==$block->z){
                             $event->setDrops(array());
                             $text=$tile->getText();
                             if($text[0]=="BedWars"){
                                 if($text[1]=="Bed"){
                                     if($text[2]=="Red"){
                                         if($p->getNameTag()=="§c[RED]".$pn){
						return;
					 }
                                         $this->config->setNested("BedWars.$game.Settings.Alive.Red", "destroyed");
                                         foreach($this->players[$game] as $pl){
                                             $player=$this->getServer()->getPlayer($pl["id"]);
                                             $player->sendMessage($this->prefix."§cKirmizi takimin yatagi yok edildi!");
                                         }
                                         $p->sendMessage($this->prefix."§dTebrikler yatak yokettin! +200 Level");
                                         $p->setExpLevel($p->getExpLevel() + 200);
                                     }elseif($text[2]=="Blue"){
                                         if($p->getNameTag()=="§9[BLUE]".$pn){
						return true;
					 }
                                         $this->config->setNested("BedWars.$game.Settings.Alive.Blue", "destroyed");
                                         foreach($this->players[$game] as $pl){
                                             $player=$this->getServer()->getPlayer($pl["id"]);
                                             $player->sendMessage($this->prefix."§9Mavi takimin yatagi yok edildi!");
                                         }
                                         $p->sendMessage($this->prefix."§dTebrikler yatak yokettin! +200 Level");
                                         $p->setExpLevel($p->getExpLevel() + 200);
                                     }
                                     elseif($text[2]=="Green"){
                                         if($p->getNameTag()=="§a[GREEN]".$pn){
						return true;
					 }
                                         $this->config->setNested("BedWars.$game.Settings.Alive.Green", "destroyed");
                                         foreach($this->players[$game] as $pl){
                                             $player=$this->getServer()->getPlayer($pl["id"]);
                                             $player->sendMessage($this->prefix."§aYesil takimin yatagi yok edildi!");
                                         }
                                         $p->sendMessage($this->prefix."§dTebrikler yatak yokettin! +200 Level");
                                         $p->setExpLevel($p->getExpLevel() + 200);
                                     }
                                     elseif($text[2]=="Yellow"){
                                         if($p->getNameTag()=="§e[YELLOW]".$pn){
						return true;
					 }
                                         $this->config->setNested("BedWars.$game.Settings.Alive.Yellow", "destroyed");
                                         foreach($this->players[$game] as $pl){
                                             $player=$this->getServer()->getPlayer($pl["id"]);
                                             $player->sendMessage($this->prefix."§eSari takimin yatagi yok edildi!");
                                         }
                                         $p->sendMessage($this->prefix."§dTebrikler yatak yokettin! +200 Level");
                                         $p->setExpLevel($p->getExpLevel() + 200);
                                     }
                                 }
                             }
                     }
                 }
             }
         }
      }
    }
    
        public function OnPlace(BlockPlaceEvent $event){
            $block=$event->getBlock()->getId();
            $p=$event->getPlayer();
            if(isset($this->inGames[$p->getName()])){
                $game=$this->inGames[$p->getName()];
                $blocks=$this->config->getNested("BedWars.$game.Settings.PlaceableAndBreakableBlocks");
                if(!isset($blocks[$block])){
                    $event->setCancelled(true);
                }
            }
        }
        public function OnDeath(PlayerDeathEvent $event){
        	    $entity=$event->getPlayer();
        	    $tag=$entity->getNameTag();
            $cause=$event->getEntity()->getLastDamageCause();
            if(strpos("�9", $tag)){
            	
            }
            if($cause instanceof EntityDamageByEntityEvent){
                $p=$event->getEntity();
                $damager=$cause->getDamager();
                if(isset($this->inGames[$p->getName()])){
                    $levelplayers=$damager->getLevel()->getPlayers;
                    foreach($levelplayers as $player){
                        $pn=$p->getName();
                        $dn=$damager->getName();
                        $player->sendMessage($this->prefix."§a$dn §e$pn"."'yi §coldurdu!");
                    }
                }
            }
        }
        public function OnRespawn(PlayerRespawnEvent $event){
            $p=$event->getPlayer();
            if(isset($this->inGames[$p->getName()])){
                $game=$this->inGames[$p->getName()];
                $tag=$p->getNameTag();
                $name=$p->getName();
                $map=$this->config->getNested("BedWars.$game.Settings.blueteampos")["level"];
                $level=$this->getServer()->getLevelByName($map);
                if($tag=="�9[MAVI]$name"){
                    $cfg=$this->config->getNested("BedWars.$game.Settings.blueteampos");
                    $pos=new Position($cfg["blueteampos_x"], $cfg["blueteampos_y"], $cfg["blueteampos_z"], $level);
                    $event->setRespawnPosition($pos);
                }
                if($tag=="§c[KIRMIZI]$name"){
                    $cfg=$this->config->getNested("BedWars.$game.Settings.redteampos");
                    $pos=new Position($cfg["redteampos_x"], $cfg["redteampos_y"], $cfg["redteampos_z"], $level);
                    $event->setRespawnPosition($pos);
                }
                if($tag=="�e[SARI]$name"){
                    $cfg=$this->config->getNested("BedWars.$game.Settings.yellowteampos");
                    $pos=new Position($cfg["yellowteampos_x"], $cfg["yellowteampos_y"], $cfg["yellowteampos_z"], $level);
                    $event->setRespawnPosition($pos);
                }
                if($tag=="�a[YESIL]$name"){
                    $cfg=$this->config->getNested("BedWars.$game.Settings.greenteampos");
                    $pos=new Position($cfg["greenteampos_x"], $cfg["greenteampos_y"], $cfg["greenteampos_z"], $level);
                    $event->setRespawnPosition($pos);
                }
            }
        }

	public function InventoryTransactionEvent(InventoryTransactionEvent $event){
		$Transaction = $event->getTransaction();
		$Player = $Transaction->getPlayer();
		$BuyingInv = null;
		foreach ($Transaction->getTransactions() as $inv) {
			$inv=$inv->getInventory();
			if($inv instanceof ChestInventory){
				$BuyingInv = $inv->getHolder();
				}
		}
		foreach ($Transaction->getTransactions() as $t) {
			 $TargetItem=$t->getTargetItem();
			 $TargetItemId=$TargetItem->getId();
 	}
 	if($BuyingInv==null){
 		return true;
 	}
		$x=round($BuyingInv->getX());
		$y=round($BuyingInv->getY());
		$z=round($BuyingInv->getZ());
		$level=$Player->getLevel();
                if(isset($this->inGames[$Player->getName()])){
                    $event->setCancelled(true);
                    $esyalar=count($this->shop->get("BedWars-Shop"));
                    $shop=$this->shop->get("BedWars-Shop");
                    $slot=0;
                    for($i=0; $i<$esyalar; $i+=2){
                        if($TargetItemId==$shop[$i]){
                        $BuyingInv->getInventory()->clearAll();
                        $item=$shop[$i + 1];
                        for($t=0; $t<count($item); $t++){
                            $BuyingInv->getInventory()->setItem($slot, Item::get($item[$t][0], $item[$t][1], $item[$t][2]));
                            $slot++;
                            $BuyingInv->getInventory()->setItem($slot,Item::get(384, 0, $item[$t][3]));
                            $slot++;
                            $BuyingInv->getInventory()->setItem(26, Item::get(35, 14, 1));
                            $this->Shopping[$Player->getName()]=true;
                        }
                      }
                        $Player->addWindow($BuyingInv->getInventory());
                    }
                    if($TargetItemId==35 and $TargetItem->getDamage()==14){
                            $BuyingInv->getInventory()->clearAll();
                            $items=$this->shop->get("BedWars-Shop");
                            $c=count($items);
                            for($i=0; $i < $c; $i+=2){
                               $slot = $i / 2;
                               $BuyingInv->getInventory()->setItem($slot, Item::get($items[$i], 0, 1));
                               $this->Shopping[$Player->getName()]=false;
                            }
                            return;
                        }
                    if($this->Shopping[$Player->getName()]==true){
                        for($i=1; $i<count($shop); $i+=2){
                            $kumes=$shop[$i];
                            for($e=0; $e<count($kumes); $e++){
                                $item=$kumes[$e][0];
                                if($TargetItemId==$item){
                                    $para=$kumes[$e][3];
                                    $adet=$kumes[$e][2];
                                    $damage=$kumes[$e][1];
                                    $exp=$Player->getExpLevel();
                                    if($exp>=$para){
                                        $Player->getInventory()->addItem(Item::get($item, $damage, $adet));
                                        $Player->setExpLevel($exp - $para);
                                        $Player->sendPopup("�eEsya Satin alindi!");
                                    }
                                }
                            }
                        }
                    }
               }
	}
	
        public function OnCloseInv(InventoryCloseEvent $event){
            $inv=$event->getInventory();
            $p=$event->getPlayer();
            if(isset($this->inGames[$p->getName()])){
                if($this->Shopping[$p->getName()]==true){
                    $this->Shopping[$p->getName()]=false;
                }
            }
        }
        
	public function PickupItem(InventoryPickupItemEvent $event){
		$item=$event->getItem();
		$p=$event->getInventory()->getHolder();
               if($p instanceof Player and isset($this->inGames[$p->getName()])){
                    if($event->getItem()->getItem()->getID()==Item::IRON_INGOT){
                        $event->setCancelled(true);
                        $p->getLevel()->removeEntity($item);
			 $p->setExpLevel($p->getExpLevel() + 5);
                        $p->sendPopUp("§a+5 Level!\n\n");
		    }
		    if($event->getItem()->getItem()->getID()==Item::GOLD_INGOT){
                        $event->setCancelled(true);
                        $p->getLevel()->removeEntity($item);
			$p->setExpLevel($p->getExpLevel() + 10);
                        $p->sendPopUp("§a+10 Level!\n\n");
		    }
		    if($event->getItem()->getItem()->getID()==Item::BRICK){
                        $event->setCancelled(true);
                        $p->getLevel()->removeEntity($item);
			$p->setExpLevel($p->getExpLevel() + 1);
                        $p->sendPopup("§a+1 Level!\n\n");
		    }
      }
	}
        
    public function OnDisable(){
        $this->getServer()->getLogger()->info("§8[§cBed§fWars§8]§c Plugin Deaktifleştirildi");
        $this->getServer()->getLogger()->info("§7Y�kledi�in i�in Te�eskk�rler :) #EmreTr1");
    }
}

class GameTask extends PluginTask{
    
    private $game;
    private $plugin;
    public $Bnow=1;
    public $Inow=1;
    public $Gnow=1;
    public $saniye=30;
    public $prefix = "§8[§4Bed§fWars§8]";
    public $rbed="[+]";
    public $ybed="[+]";
    public $gbed="[+]";
    public $bbed="[+]";
    
    public function __construct(Plugin $plugin, $game){
        parent::__construct($plugin);
		$this->main = $plugin;
                $this->game = $game;
	}
    
    public function OnRun($currentTick){
        $game=$this->game;
        $this->players[$game]=$this->main->players[$game];
		if(count($this->players[$game])<=0){
			foreach($this->players[$game] as $pl){
				$p=$this->main->getServer()->getPlayer($pl["id"]);
				$p->sendTip($this->prefix);
				$p->sendPopUp("§6Oyuncular bekleniyor...");
				}
                        }
				if(count($this->players[$game])>=0){
					foreach($this->players[$game] as $pl){
					$p=$this->main->getServer()->getPlayer($pl["id"]);
					$this->saniye--;
					if($this->saniye>0){
					$p->sendPopUp("§f> §6Başlamasına " .$this->saniye."§f <\n      §8[§4Bed§fWars§8]");
					}
				if($this->saniye<=5 and $this->saniye>=-1){
					$x=$p->x;
					$y=$p->y;
					$z=$p->z;
					$p->getLevel()->addSound(new PopSound($p));
					$p->getLevel()->addParticle(new LargeExplodeParticle(new Vector3($x, $y, $z)));
					if($this->saniye==0){
						$p->sendMessage($this->prefix."§aOyun Başladı!!");
                                                $this->main->config->setNested("BedWars.$game.Settings.Alive.Red", "notdestroy");
                                                $this->main->config->setNested("BedWars.$game.Settings.Alive.Green", "notdestroy");
                                                $this->main->config->setNested("BedWars.$game.Settings.Alive.Yellow", "notdestroy");
                                                $this->main->config->setNested("BedWars.$game.Settings.Alive.Blue", "notdestroy");
                                                $this->main->config->save();
						$this->main->getTeams($game);
					}
				}
		        }
		  	if($this->saniye<=0){
		  		$teams=array();
		  		foreach($this->players[$game] as $pl){
				 $p=$this->main->getServer()->getPlayer($pl["id"]);
				 $tag=$p->getNameTag();
				 $name=$p->getName();
				 if($tag=="�9[MAVI]$name"){
				 	 $teams["Blue"]++;
				 }elseif($tag=="�a[YESIL]$name"){
				 	$teams["Green"]++;
				 }elseif($tag=="�e[SARI]$name"){
				 	$teams["Yellow"]++;
				 }elseif($tag=="�c[KIRMIZI]$name"){
				 	$teams["Red"]++;
				 }
                                if($this->main->config->getNested("BedWars.$game.Settings.Alive.Red")=="destroyed"){
                                    $this->rbed="-";
                                }
                                if($this->main->config->getNested("BedWars.$game.Settings.Alive.Green")=="destroyed"){
                                    $this->gbed="-";
                                }
                                if($this->main->config->getNested("BedWars.$game.Settings.Alive.Red.Yellow")=="destroyed"){
                                    $this->ybed="-";
                                }
                                if($this->main->config->getNested("BedWars.$game.Settings.Alive.Red.Blue")=="destroyed"){
                                    $this->bbed="-";
                                }
   /*$re=$teams["Red"];
   $gr=$teams["Green"];
   $ye=$teams["Yellow"];
   $bl=$teams["Blue"];*/
				$p->sendPopUp("§4Red ".$this->rbed." §aGreen ".$this->gbed." §eYellow ".$this->ybed." §9Blue ".$this->bbed);
	               	################
                ### SPAWNERS ###
                ################
                    $tiles=$p->getLevel()->getTiles();
                    foreach($tiles as $tile){
                        if($tile instanceof Sign){
                            $x=$tile->x + 0.5;
                            $y=$tile->y + 2;
                            $z=$tile->z + 0.5;
                            $text=$tile->getText();
                            if($text[0]=="[BedWars]"){
                                if($text[1]=="Spawner"){
                                    if($text[2]=="Iron"){
                                        if((Time() % 8)==0){
                                            $p->getLevel()->dropItem(new Vector3($x, $y, $z), Item::get(Item::IRON_INGOT, 0, 1));
                                            $particle=new FloatingTextParticle(new Vector3($x, $y + 0.5, $z), "", c::GRAY."Demir");
                                            if($this->Inow==1){
                                                $p->getLevel()->addParticle($particle);
                                                $this->Inow=0;
                                            }
                                        }
                                    }
                                    if($text[2]=="Gold"){
                                        if((Time() % 20)==0){
                                            $p->getLevel()->dropItem(new Vector3($x, $y, $z), Item::get(Item::GOLD_INGOT, 0, 1));
                                            $particle=new FloatingTextParticle(new Vector3($x, $y + 0.5, $z), "", c::YELLOW."Altin");
                                            if($this->Gnow==1){
                                                $p->getLevel()->addParticle($particle);
                                                $this->Inow=0;
                                            }
                                        }
                                    }
                                    if($text[2]=="Bronz"){
                                        $saat=new Vector3($x, $y, $z);
                                        $distance=$saat->distance($p);
                                        if((Time() % 1)==0 and $distance<=10){
                                            $p->getLevel()->dropItem(new Vector3($x, $y, $z), Item::get(Item::BRICK, 0, 1));
                                            $particle=new FloatingTextParticle(new Vector3($x, $y + 0.5, $z), "", c::RED."Bronz");
                                            {
                                            if($this->Bnow==1)
                                                $p->getLevel()->addParticle($particle);
                                                $this->Bnow=0;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                }
                if($this->bbed=="-" and $this->ybed=="-" and $this->gbed=="-"){
                	 $p->sendMessage($this->prefix."�f�l*****************************\n\n
                	 �a�l  Oyun Bitti!\n
                	 �c�l  Kirmizi Takim Kazandi!\n\n
                	 �f�l*****************************");
                	 $p->getInventory()->clearAll();
                	 $spawn=$this->main->getServer()->getDefaultLevel()->getSafeSpawn();
                	 $p->teleport($spawn);
                	 $p->sendPopup("�bTwitter: @MineDogsPE");
                	 $this->main->getServer()->getScheduler()->cancelTask($this->getTaskId());
                }elseif($this->rbed=="-" and $this->ybed=="-" and $this->gbed=="-"){
                	 $p->sendMessage($this->prefix."�f�l*****************************\n\n
                	 �a�l  Oyun Bitti!\n
                	 �9�l  Mavi Takim Kazandi!\n\n
                	 �f�l*****************************");
                	 $p->getInventory()->clearAll();
                	 $spawn=$this->main->getServer()->getDefaultLevel()->getSafeSpawn();
                	 $p->teleport($spawn);
                	 $p->sendPopup("�bTwitter: @MineDogsPE");
                	 $this->main->getServer()->getScheduler()->cancelTask($this->getTaskId());
                }elseif($this->bbed=="-" and $this->rbed=="-" and $this->gbed=="-"){
                	 $p->sendMessage($this->prefix."�f�l*****************************\n\n
                	 �a�l  Oyun Bitti!\n
                	 �e�l  Sari Takim Kazandi!\n\n
                	 �f�l*****************************");
                	 $p->getInventory()->clearAll();
                	 $spawn=$this->main->getServer()->getDefaultLevel()->getSafeSpawn();
                	 $p->teleport($spawn);
                	 $p->sendPopup("�bTwitter: @MineDogsPE");
                	 $this->main->getServer()->getScheduler()->cancelTask($this->getTaskId());
                }elseif($this->bbed=="-" and $this->ybed=="-" and $this->rbed=="-"){
                	 $p->sendMessage($this->prefix."�f�l*****************************\n\n
                	 �a�l  Oyun Bitti!\n
                	 �2�l  Yesil Takim Kazandi!\n\n
                	 �f�l*****************************");
                	 $p->getInventory()->clearAll();
                	 $spawn=$this->main->getServer()->getDefaultLevel()->getSafeSpawn();
                	 $p->teleport($spawn);
                	 $p->sendPopup("�bTwitter: @MineDogsPE");
                	 $this->main->getServer()->getScheduler()->cancelTask($this->getTaskId());
                }
     }
    }
   }
 }
}