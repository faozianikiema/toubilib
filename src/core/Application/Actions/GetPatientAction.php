<?php
namespace App\core\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\core\Application\useCase\PatientService;

class GetPatientAction {
    private PatientService $service;

    public function __construct(PatientService $service) {
        $this->service = $service;
    }

    public function __invoke(Request $request, Response $response): Response {
        $patients = $this->service->getAllPatient();

        $data = array_map(fn($patient) => [
            'id' => $patient->getId(),
            'nom' => $patient->getNom(),
            'prenom' => $patient->getPrenom(),
            'date_naissance' => $patient->getDateNaissance()->format('Y-m-d'),
            'adresse'=>$patient->getAdresse(),
            'ville'=>$patient->getVille(),
            'code_postal'=>$patient->getCode_postal(),
            'email' => $patient->getEmail(),
            'telephone' => $patient->getTelephone()
        ], $patients);

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
