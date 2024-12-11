<?php

    namespace App\Controllers\Legal;

    use App\Controllers\BaseController;

    use App\Models\AccountModel;

    class LegalController extends BaseController
    {
        public function conditionsGenerales()
        {
            $data = [
                'pageTitle' => 'Conditions Générales',
                'content'   => view('Legal/conditions_generales') // Contenu principal
            ];

            return View('Layout/main', $data);
        }

        public function politiqueConfidentialite()
        {
            $data = [
                'pageTitle' => 'Politique de Confidentialité',
                'content'   => view('Legal/politique_confidentialite') // Contenu principal
            ];

            return View('Layout/main', $data);
        }

        public function contact()
        {
            $data = [
                'pageTitle' => 'Contact',
                'content'   => view('Legal/contact') // Contenu principal
            ];

            return View('Layout/main', $data);
        }

        public function sendContact()
        {
            helper(['form']);
            $rules = [
                'first_name' => [
                    'rules'  => 'required|max_length[50]',
                    'errors' => [
                        'required'   => 'Le prénom est obligatoire.',
                        'max_length' => 'Le prénom ne peut dépasser 50 caractères.',
                    ],
                ],
                'last_name' => [
                    'rules'  => 'required|max_length[50]',
                    'errors' => [
                        'required'   => 'Le nom est obligatoire.',
                        'max_length' => 'Le nom ne peut dépasser 50 caractères.',
                    ],
                ],
                'email' => [
                    'rules'  => 'required|valid_email',
                    'errors' => [
                        'required'    => 'L\'email est obligatoire.',
                        'valid_email' => 'L\'email doit être valide.',
                    ],
                ],
                'message' => [
                    'rules'  => 'required|max_length[1000]',
                    'errors' => [
                        'required'   => 'Le message est obligatoire.',
                        'max_length' => 'Le message ne peut dépasser 1000 caractères.',
                    ],
                ],
            ];

            if (!$this->validate($rules))
            {
                return redirect()->back()->withInput()->with('validation', $this->validator);
            }

            $fromEmail = env('email_user', ''); // Adresse email de l'envoyeur
            $userEmail = $this->request->getPost('email'); // Adresse email de l'utilisateur
            $userName  = $this->request->getPost('first_name') . ' ' . $this->request->getPost('last_name');
            $userMessage = $this->request->getPost('message');

            // Format du message envoyé
            $formattedMessage = "
                Monsieur/Madame {$userName} ({$userEmail}) a envoyé le message suivant : 

                {$userMessage}
            ";

            $accountModel = new AccountModel();
            $users = $accountModel->where('is_admin', true)->findAll();

            foreach ($users as $user)
			{
                $email = \Config\Services::email();
                $email->setFrom($fromEmail, 'Maison Eau d\'Or');
                $email->setTo($user['email']);
                $email->setSubject('Nouveau message de contact');
                $email->setMessage($formattedMessage);

                if ($email->send())
                {
                    return redirect()->to('/contact')->with('success', 'Votre message a été envoyé avec succès.');
                }
                else
                {
                    return redirect()->to('/contact')->with('error', 'Une erreur est survenue lors de l\'envoi de votre message.');
                }
            }
        }

        function messageClient()
        {
            return ""
        }
    }
?>