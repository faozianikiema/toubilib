<?php
namespace gateway\usecase;

use gateway\application\ports\api\RendezVousServiceInterface;
use gateway\ports\spi\PraticienInterface;

class RendezVousService implements RendezVousServiceInterface{

private PraticienInterface $praticienService;

    public function __construct(PraticienInterface $praticienService)
    {
        $this->praticienService = $praticienService;
    }
    public function getRendezVousByPraticienId($id): array
    {
        return $this->praticienService->getPraticiensId($id);
    }
}