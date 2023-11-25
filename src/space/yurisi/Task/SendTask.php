<?php
declare(strict_types=1);

namespace space\yurisi\Task;

use pocketmine\network\mcpe\protocol\{RemoveObjectivePacket, SetDisplayObjectivePacket, SetScorePacket};
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class SendTask extends Task {

  private Player $player;

  public function __construct(Player $player) {
    $this->player = $player;
  }

  public function onRun(): void {
    $player = $this->player;
    $this->RemoveData($player);
    $this->setupData($player);
    $this->sendData($player, "§b座標: " . $player->getPosition()->getFloorX() . "," . $player->getPosition()->getFloorY() . "," . $player->getPosition()->getFloorZ(), 1);
    $this->sendData($player, "§bワールド: " . $player->getWorld()->getFolderName(), 2);
    $this->sendData($player, "§b現在時刻: " . date("G時i分"), 3);
    $this->sendData($player, "§c持ってるid: " . $player->getInventory()->getItemInHand()->getId() . ":" . $player->getInventory()->getItemInHand()->getMeta(), 4);
    $this->sendData($player, "§7基本コマンド説明", 5);
    $this->sendData($player, "§7/m 自分のワールド", 6);
    $this->sendData($player, "§7/t {名前} 人のワールド", 7);

  }

  public function onCancel(): void {
    $this->RemoveData($this->player);
  }

  private function setupData(Player $player) {
    $pk = new SetDisplayObjectivePacket();
    $pk->displaySlot = "sidebar";
    $pk->objectiveName = "sidebar";
    $pk->displayName = "§6KomugiBuild";
    $pk->criteriaName = "dummy";
    $pk->sortOrder = 0;
    $player->getNetworkSession()->sendDataPacket($pk);
  }

  private function sendData(Player $player, string $data, int $id) {
    $entry = new ScorePacketEntry();
    $entry->objectiveName = "sidebar";
    $entry->type = $entry::TYPE_FAKE_PLAYER;
    $entry->customName = $data;
    $entry->score = $id;
    $entry->scoreboardId = $id + 11;
    $pk = new SetScorePacket();
    $pk->type = $pk::TYPE_CHANGE;
    $pk->entries[] = $entry;
    $player->getNetworkSession()->sendDataPacket($pk);
  }

  private function RemoveData(Player $player) {
    $pk = new RemoveObjectivePacket();
    $pk->objectiveName = "sidebar";
    $player->getNetworkSession()->sendDataPacket($pk);
  }
}
