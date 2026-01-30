<?php
namespace gateway\application\ports\api;

interface RendezVousServiceInterface{
    public function getRendezVousByPraticienId($id):array;
}