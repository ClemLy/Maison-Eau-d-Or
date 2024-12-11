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


            // Envoi de la confirmation à l'utilisateur
            $email = \Config\Services::email();
            $email->setFrom($fromEmail, 'Maison Eau d\'Or');
            $email->setTo($userEmail);
            $email->setSubject('Confirmation de votre message');
            $email->setMessage($this->messageClient($userName));
            $email->send();

            $accountModel = new AccountModel();
            $users = $accountModel->where('is_admin', true)->findAll();

            foreach ($users as $user)
			{
                $email = \Config\Services::email();
                $email->setFrom($fromEmail, 'Maison Eau d\'Or');
                $email->setTo($user['email']);
                $email->setSubject('Nouveau message de contact');
                $email->setMessage($this->messageAdmin($userName, $userEmail, $userMessage));

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

        function messageClient($userName)
        {
            return "
            
            <!DOCTYPE html>
            <html lang=\"fr\">
            <body style=\"margin: 0; padding: 0; background-color: #FFFFFF; font-family: Arial, sans-serif;\">
            <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"background-color: #FFFFFF; margin: 0; padding: 0;\">
                <tr>
                <td align=\"center\">
                    <!-- Container -->
                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border: 1px solid #ccc; background-color: #fff; padding: 20px;\">
                    <!-- Header -->
                    <tr>
                        <td align=\"center\" style=\"padding: 10px 0;\">
                        <a href=\"".site_url('/')."\">
                            <img src=\"https://i.imgur.com/2Objlik.png\" alt=\"Logo\" width=\"200\" style=\"display: block;\">
                        </a>
                        </td>
                    </tr>
                    <!-- Navigation Menu -->
                    <tr>
                        <td align=\"center\" style=\"padding: 20px 0;\">
                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"60%;\">
							<tr>
							  <td align=\"center\" style=\"padding: 5px;\">
								<a href=\"".site_url('/')."\" style=\"display: inline-block; text-decoration: none; color: #d4af37; font-size: 14px; font-weight: bold; border: 2px solid #d4af37; padding: 10px 20px; border-radius: 20px;\">Accueil</a>
							  </td>
							  <td align=\"center\" style=\"padding: 5px;\">
								<a href=\"".site_url('/boutique')."\" style=\"display: inline-block; text-decoration: none; color: #d4af37; font-size: 14px; font-weight: bold; border: 2px solid #d4af37; padding: 10px 20px; border-radius: 20px;\">Boutique</a>
							  </td>
							  <td align=\"center\" style=\"padding: 5px;\">
								<a href=\"". site_url('/blog') ."\" style=\"display: inline-block; text-decoration: none; color: #d4af37; font-size: 14px; font-weight: bold; border: 2px solid #d4af37; padding: 10px 20px; border-radius: 20px;\">Blog</a>
							  </td>
							</tr>
                        </table>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td align=\"center\" style=\"padding: 20px;\">
                        <h2 style=\"color: #000; font-size: 24px; margin: 0; text-align: left; \"> Nous avons bien reçu votre message.</h2>
                        <p style=\"color: #333; font-size: 14px; line-height: 1.5; text-align: left; \"> Cher ".$userName.",</p>
                        <p style=\"color: #333; font-size: 14px; line-height: 1.5; text-align: left; \"> Votre message a bien été pris en compte et sera traité dans les prochains jours.</p>
                        <p style=\"color: #333; font-size: 14px; line-height: 1.5; text-align: left; \"> <b> L'équipe de Maison Eau d'Or</b>.</p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td align=\"center\" style=\"padding: 20px; background-color: #f7f7f7; margin-top: 50px;\">
                        <p style=\"color: #333; font-size: 14px; margin: 0;\">Retrouvez-nous sur nos réseaux</p>
                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top: 10px;\">
                            <tr>
                            <td style=\"padding: 0 5px;\">
                                <a href=\"https://www.facebook.com/maisoneaudor76/\">
                                <img src=\"https://eryasvi.stripocdn.email/content/assets/img/social-icons/logo-black/facebook-logo-black.png\" alt=\"Facebook\" width=\"32\" style=\"display: block;\">
                                </a>
                            </td>
                            <td style=\"padding: 0 5px;\">
                                <a href=\"https://www.instagram.com/maisoneaudor/\">
                                <img src=\"https://eryasvi.stripocdn.email/content/assets/img/social-icons/logo-black/instagram-logo-black.png\" alt=\"Instagram\" width=\"32\" style=\"display: block;\">
                                </a>
                            </td>
                            </tr>
                        </table>
                        </td>
                    </tr>
                    </table>
                </td>
                </tr>
            </table>
            </body>
            </html>

            ";
        }

        function messageAdmin($userName, $userEmail, $userMessage)
        {
            return "
            
            <!DOCTYPE html>
            <html lang=\"fr\">
            <body style=\"margin: 0; padding: 0; background-color: #FFFFFF; font-family: Arial, sans-serif;\">
            <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"background-color: #FFFFFF; margin: 0; padding: 0;\">
                <tr>
                <td align=\"center\">
                    <!-- Container -->
                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border: 1px solid #ccc; background-color: #fff; padding: 20px;\">
                    <!-- Header -->
                    <tr>
                        <td align=\"center\" style=\"padding: 10px 0;\">
                        <a href=\"".site_url('/')."\">
                            <img src=\"https://i.imgur.com/2Objlik.png\" alt=\"Logo\" width=\"200\" style=\"display: block;\">
                        </a>
                        </td>
                    </tr>
                    <!-- Navigation Menu -->
                    <tr>
                        <td align=\"center\" style=\"padding: 20px 0;\">
                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"60%;\">
							<tr>
							  <td align=\"center\" style=\"padding: 5px;\">
								<a href=\"".site_url('/')."\" style=\"display: inline-block; text-decoration: none; color: #d4af37; font-size: 14px; font-weight: bold; border: 2px solid #d4af37; padding: 10px 20px; border-radius: 20px;\">Accueil</a>
							  </td>
							  <td align=\"center\" style=\"padding: 5px;\">
								<a href=\"".site_url('/boutique')."\" style=\"display: inline-block; text-decoration: none; color: #d4af37; font-size: 14px; font-weight: bold; border: 2px solid #d4af37; padding: 10px 20px; border-radius: 20px;\">Boutique</a>
							  </td>
							  <td align=\"center\" style=\"padding: 5px;\">
								<a href=\"". site_url('/blog') ."\" style=\"display: inline-block; text-decoration: none; color: #d4af37; font-size: 14px; font-weight: bold; border: 2px solid #d4af37; padding: 10px 20px; border-radius: 20px;\">Blog</a>
							  </td>
							</tr>
                        </table>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td align=\"center\" style=\"padding: 20px;\">
                        <h2 style=\"color: #000; font-size: 24px; margin: 0; text-align: left; \"> Cher Administrateur,</h2>
                        <p style=\"color: #333; font-size: 14px; line-height: 1.5; text-align: left; \"> Monsieur/Madame ".$userName." (".$userEmail.") a envoyé le message suivant : </p>
                        <p style=\"color: #333; font-size: 14px; line-height: 1.5; text-align: left; \"> ".$userMessage."</p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td align=\"center\" style=\"padding: 20px; background-color: #f7f7f7; margin-top: 50px;\">
                        <p style=\"color: #333; font-size: 14px; margin: 0;\">Retrouvez-nous sur nos réseaux</p>
                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top: 10px;\">
                            <tr>
                            <td style=\"padding: 0 5px;\">
                                <a href=\"https://www.facebook.com/maisoneaudor76/\">
                                <img src=\"https://eryasvi.stripocdn.email/content/assets/img/social-icons/logo-black/facebook-logo-black.png\" alt=\"Facebook\" width=\"32\" style=\"display: block;\">
                                </a>
                            </td>
                            <td style=\"padding: 0 5px;\">
                                <a href=\"https://www.instagram.com/maisoneaudor/\">
                                <img src=\"https://eryasvi.stripocdn.email/content/assets/img/social-icons/logo-black/instagram-logo-black.png\" alt=\"Instagram\" width=\"32\" style=\"display: block;\">
                                </a>
                            </td>
                            </tr>
                        </table>
                        </td>
                    </tr>
                    </table>
                </td>
                </tr>
            </table>
            </body>
            </html>

            ";
        }
    }
?>