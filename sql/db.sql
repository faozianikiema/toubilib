INSERT INTO specialite (id, libelle, description) VALUES
(1,	'médecine générale',	'Médecine Générale'),
(2,	'pédiatrie',	'Maladies des enfants'),
(3,	'ophtalmologie',	'Maladies des yeux'),
(4,	'Dentiste',	'Bouche et dents'),
(5,	'Imagerie médicale',	'radiologie, échographie, IRM');
INSERT INTO structure (id, nom, adresse, ville, code_postal, telephone) VALUES
('3444bdd2-8783-3aed-9a5e-4d298d2a2d7c',	'Cabinet Bigot',	'63, rue de Mercier',	'Paris',	'75 002',	'01 02 03 04 05'),
('372c1de7-4d31-3d01-9c92-77e69467d4e3',	'Cabinet Toussaint Marechal',	'290, rue Rossi',	'Joubert',	'54 543',	'+33 03 83 56 78 98'),
('255ecef6-14b9-3b5c-a40e-901665f4ed28',	'Pichon Santé',	'93, impasse Alain Baron',	'Diaz-sur-Boulanger',	'48 987',	'01 02 04 50 37'),
('80756589-93f3-3ab7-92dd-377dbea5ea4d',	'Maison Médiacle Parent - Lucas',	'4, impasse de Denis',	'Lucas',	'63 220',	'03 87 56 54 34'),
('5f7d3d80-3c4b-386a-a23e-74de4a1e0b19',	'Maison de Santé de Care-Sur_Turpin',	'35, impasse Lebon',	'Carre-sur-Turpin',	'57 345',	'03 87 45 65 34'),
('c302d11b-d5b8-310a-bbc5-9dd4400eb2c9',	'Babinet Antoine',	'97, avenue Raymond Gerard',	'Moreno',	'34 657',	'06 76 56 34 32'),
('b636c1fd-c61c-306b-9d4f-25cf3bfd9f66',	'SOS Médecins Lefevre',	'23, avenue Mercier',	'Lefevre',	'56 780',	'01 03 900 000'),
('7db214d9-f415-3110-991b-ec5f9774b685',	'Urgences Marin',	'96, rue Louis',	'Bourdon-les-Bains',	'71 344',	'09 78 67 54 43'),
('a51a0543-ee80-3515-81ab-2504ec194c09',	'Maison Santé Lombard',	'17, chemin de Bazin',	'Paris-la-Forêt',	'76 800',	'01 328 328 00'),
('fe6e7644-9e58-3b57-9734-f201766d4d75',	'Urgences médicales Bouchet',	'53, rue Toussaint',	'Bouchet',	'89 200',	'01 02 03 15 15'),
('9117850b-3116-3d4f-b346-1dfc23c26787',	'Cabinet Médical Vaillant',	'68, avenue Élise Dupre',	'LemonnierBourg',	'67 200',	'03 82 34 54 67'),
('bee7989a-c204-3cf7-bbfc-42a9ce8456ea',	'Cabinet Médical Martineau',	'31, boulevard Éric Pierre',	'Royer',	'21 330',	'03 87 34 55 55'),
('55ee31cb-9fb6-3513-b209-e2804d02c11e',	'Fischer Médecins, Ruiz',	'83, place de Lefort',	'Ruiz',	'64 320',	'04 82 25 25 25'),
('e65145bb-ce57-4320-b0a8-6c0ba06def6d',	'Radio Plus',	'1 rue de la santé',	'Nancy',	'54 000',	'03 43 56 65 54');

-- 2025-05-14 09:32:32.870589+00

INSERT INTO motif_visite (id,specialite_id, libelle) VALUES
(	1,	1,	'Consultation'),
(	2,	1,	'Renouvellement de traitement'),
(3,	1,	'Médecine du sport'),
(4,	1,	'Enfants'),
(5,	2,	'radiologie'),
(6,	2,	'échographie'),
(7,	2,	'scanner'),
(8,	2,	'IRM'),
(9,	3,	'nourrisson'),
(10,	3,	'urgence'),
(11,	3,	'suivi pédiatrique'),
(12,	3,	'Première consultation'),
(13,	4,	'Adulte - 1ère consultation'),
(14,	4,	'Adulte - Suivi'),
(15,	4,	'Enfant - 1ère consultation'),
(16,	4,	'Enfant - suivi '),
(17,	4,	'Cataracte'),
(18,	4,	'chirurgie myopie'),
(19,	4,	'Soins dentaires'),
(20,	4,	'Urgence dentaire'),
(21,	4,	'Bilan dentaire'),
(22,	4,	'prothèses dentaires'),
(23,	4,	'pose implants'),
(24,	4,	'Enfants');
INSERT INTO moyen_paiement (id, libelle) VALUES
(1,	'espèces'),
(2,	'chèque'),
(3,	'carte bancaire'),
(4,	'paypal'),
(5,	'virement'),
(6,	'transfert paylib'),
(7,	'troc');

INSERT INTO praticien (
    id, nom, prenom, ville, user_id, telephone, specialite_id, structure_id,
    rpps_id, organisation, nouveau_patient, titre
) VALUES
('f47ac10b-58cc-4372-a567-0e02b2c3d479', 'Martin', 'Alice', 'Paris', '550e8400-e29b-41d4-a716-446655440001', '0102030001', 1, "3444bdd2-8783-3aed-9a5e-4d298d2a2d7c", '12345678901', 1, 1, 'Dr.'),
('550e8400-e29b-41d4-a716-446655440000', 'Durand', 'Bob', 'Lyon', '550e8400-e29b-41d4-a716-446655440003', '0102030002', 2, '5f7d3d80-3c4b-386a-a23e-74de4a1e0b19', '12345678902', 0, 1, 'Dr.'),
('123e4567-e89b-12d3-a456-426614174000', 'Bernard', 'Carol', 'Marseille', '550e8400-e29b-41d4-a716-446655440001', '0102030003', 1, '5f7d3d80-3c4b-386a-a23e-74de4a1e0b19', '22345678901', 1, 0, 'Dr.'),
('p0004', 'Petit', 'David', 'Nice', '550e8400-e29b-41d4-a716-446655440001', '0102030004', 3, '5f7d3d80-3c4b-386a-a23e-74de4a1e0b19', '22345678902', 0, 1, 'Dr.'),
('p0005', 'Lemoine', 'Eve', 'Bordeaux', '550e8400-e29b-41d4-a716-446655440005', '0102030005', 2, '80756589-93f3-3ab7-92dd-377dbea5ea4d', '32345678901', 1, 1, 'Dr.'),
('p0006', 'Roux', 'Frank', 'Nantes', '550e8400-e29b-41d4-a716-446655440005', '0102030006', 1, '80756589-93f3-3ab7-92dd-377dbea5ea4d', '32345678902', 0, 1, 'Dr.'),
('p0007', 'Fabre', 'Grace', 'Lille', '550e8400-e29b-41d4-a716-446655440017', '0102030007', 1, '80756589-93f3-3ab7-92dd-377dbea5ea4d', '42345678901', 1, 0, 'Dr.'),
('p0008', 'Moreau', 'Heidi', 'Rouen', '550e8400-e29b-41d4-a716-446655440017', '0102030008', 2, 'a51a0543-ee80-3515-81ab-2504ec194c09', '42345678902', 0, 1, 'Dr.'),
('p0009', 'Giraud', 'Ivan', 'Toulouse', '550e8400-e29b-41d4-a716-446655440017', '0102030009', 3, 'a51a0543-ee80-3515-81ab-2504ec194c09', '52345678901', 1, 1, 'Dr.'),
('p0010', 'Dupuis', 'Judy', 'Strasbourg', '550e8400-e29b-41d4-a716-446655440017', '0102030010', 2,'a51a0543-ee80-3515-81ab-2504ec194c09', '52345678902', 0, 1, 'Dr.'),
('p0011', 'Simon', 'Alice', 'Dijon', '550e8400-e29b-41d4-a716-446655440019', '0102030011', 1, 'a51a0543-ee80-3515-81ab-2504ec194c09', '62345678901', 1, 0, 'Dr.'),
('p0012', 'Noel', 'Bob', 'Reims', '550e8400-e29b-41d4-a716-446655440019', '0102030012', 2, 'a51a0543-ee80-3515-81ab-2504ec194c09', '62345678902', 0, 1, 'Dr.'),
('p0013', 'Roy', 'Carol', 'Amiens', '550e8400-e29b-41d4-a716-446655440019', '0102030013', 3, 'e65145bb-ce57-4320-b0a8-6c0ba06def6d', '72345678901', 1, 1, 'Dr.'),
('p0014', 'Blanc', 'David', 'Clermont', '550e8400-e29b-41d4-a716-446655440019', '0102030014', 1, 'e65145bb-ce57-4320-b0a8-6c0ba06def6d', '72345678902', 0, 1, 'Dr.'),
('p0015', 'Gautier', 'Eve', 'Le Havre', '550e8400-e29b-41d4-a716-446655440019', '0102030015', 2,'e65145bb-ce57-4320-b0a8-6c0ba06def6d', '82345678901', 1, 1, 'Dr.'),
('p0016', 'Legrand', 'Frank', 'Saint-Étienne', '550e8400-e29b-41d4-a716-446655440019', '0102030016', 3,'e65145bb-ce57-4320-b0a8-6c0ba06def6d', '82345678902', 0, 1, 'Dr.'),
('p0017', 'Dupont', 'Grace', 'Toulon', '550e8400-e29b-41d4-a716-446655440007', '0102030017', 1,'e65145bb-ce57-4320-b0a8-6c0ba06def6d', '92345678901', 1, 0, 'Dr.'),
('p0018', 'Morel', 'Heidi', 'Grenoble', '550e8400-e29b-41d4-a716-446655440007', '0102030018', 2,'e65145bb-ce57-4320-b0a8-6c0ba06def6d', '92345678902', 0, 1, 'Dr.'),
('p0019', 'Pelletier', 'Ivan', 'Boutin-les-Bains', '550e8400-e29b-41d4-a716-446655440007', '0102030019', 3,'e65145bb-ce57-4320-b0a8-6c0ba06def6d', 'a2345678901', 1, 1, 'Dr.'),
('p0020', 'Lucas', 'Judy', 'Leconte-la-Forêt', '550e8400-e29b-41d4-a716-446655440007', '0102030020', 1, 'e65145bb-ce57-4320-b0a8-6c0ba06def6d', 'a2345678902', 0, 1, 'Dr.')
;

