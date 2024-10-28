<?php

namespace StealSkin;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class Main extends PluginBase {

    public function onEnable(): void {
        $this->getLogger()->info("§d[INFO] §aStealSkin activé !");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "Cette commande ne peut être exécutée que par un joueur.");
            return true;
        }

        if (!$sender->hasPermission("stealskin.use")) {
            $sender->sendMessage(TextFormat::RED . "Vous n'avez pas la permission d'utiliser cette commande.");
            return true;
        }

        if (count($args) === 0) {
            $onlinePlayers = $this->getServer()->getOnlinePlayers();
            if (count($onlinePlayers) === 0) {
                $sender->sendMessage(TextFormat::RED . "Aucun joueur en ligne.");
            } else {
                $playerNames = implode(", ", array_map(fn(Player $player) => $player->getName(), $onlinePlayers));
                $sender->sendMessage(TextFormat::GREEN . "Joueurs en ligne : " . $playerNames);
            }
            return true;
        }

        $targetNameStart = array_shift($args);
        $target = null;

        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            if (stripos($player->getName(), $targetNameStart) === 0) {
                $target = $player;
                break;
            }
        }

        if ($target === null) {
            $sender->sendMessage(TextFormat::RED . "Aucun joueur trouvé : " . $targetNameStart);
            return true;
        }

        $sender->setSkin($target->getSkin());
        $sender->sendMessage(TextFormat::GREEN . "Vous avez volé le skin de " . $target->getName() . "!");

        return true;
    }
}
