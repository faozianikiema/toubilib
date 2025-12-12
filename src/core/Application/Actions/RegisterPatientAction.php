<?php
namespace App\Core\Application\Actions;

use App\core\Application\usecase\PatientService;
use App\core\Domain\User\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Core\Domain\Patient\Patient;
use Ramsey\Uuid\Uuid;

class RegisterPatientAction {

    private PatientService $patientRepository;

    public function __construct(PatientService $patientRepository) {
        $this->patientRepository = $patientRepository;
    }

public function __invoke(Request $request, Response $response, array $args): Response
{
    $data =$request->getParsedBody();

    // Validation minimale
    $required = ['nom', 'prenom', 'email', 'password', 'date_naissance'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            $response->getBody()->write(json_encode(['error' => "$field est requis."]));
            return $response->withHeader('Content-Type','application/json')
                            ->withStatus(400);
        }
    }

    // Création de l'utilisateur
    $userId = Uuid::uuid4()->toString();
    $user = new User(
        $userId,
        $data['email'],
        1,
        password_hash($data['password'], PASSWORD_BCRYPT)
    );

    // Création du patient
    $patient = new Patient(
        Uuid::uuid4()->toString(),
        $data['nom'],
        $data['prenom'],
        new \DateTime($data['date_naissance']),
        $data['adresse'] ?? '',
        $data['ville'] ?? '',
        $data['code_postal'] ?? '',
        $data['telephone'] ?? '',
        $user
    );

    // Sauvegarde via le repository
    $this->patientRepository->registerPatient($patient);

    $response->getBody()->write(json_encode([
        'message' => 'Patient créé avec succès',
        'patientId' => $patient->getId()
    ]));

    return $response->withHeader('Content-Type','application/json')
                    ->withStatus(201);
}}
