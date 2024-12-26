<?php

namespace App\Message;

final class UpdateDisponibiliteMessage
{
    private int $salleId;

    public function __construct(int $salleId)
    {
        $this->salleId = $salleId;
    }

    public function getSalleId(): int
    {
        return $this->salleId;
    }
}
