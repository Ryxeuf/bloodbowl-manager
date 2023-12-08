<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Team;

class TeamStateProcessor implements ProcessorInterface
{
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Team
    {
//        /** @var Team $data */
//        if ($operation instanceof Put) {
//            foreach ($data->getPlayers() as $player) {
//                $player->setTeam($data);
//            }
//        }

        return $data;
    }
}
