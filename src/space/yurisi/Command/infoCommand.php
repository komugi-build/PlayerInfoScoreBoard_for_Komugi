<?php
declare(strict_types=1);

namespace space\yurisi\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

use space\yurisi\PlayerInfoScoreBoard;

class johoCommand extends Command {

  private PlayerInfoScoreBoard $main;

  public function __construct(PlayerInfoScoreBoard $main) {
    $this->main = $main;
    parent::__construct("info", "ステータスのon/off", "/info");
  }

  public function execute(CommandSender $sender, string $label, array $args) {
    if (!$sender instanceof Player) return false;
    $msg = ["無効", "有効"];
    $this->main->changeParam($sender);
    $this->main->isOn($sender) ? $flag = 1 : $flag = 0;
    $sender->sendMessage("§b >> ステータス表示を{$msg[$flag]}にしました。");
    return true;
  }

}
