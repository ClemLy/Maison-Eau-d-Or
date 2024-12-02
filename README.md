# **Maison Eau d'Or**

---

## **Pré-requis**

Avant d'installer l'application, assurez-vous d'avoir les éléments suivants :

- **PHP 8.1 ou supérieur**
- **Composer** (Gestionnaire de dépendances PHP)
- **PostgreSQL** (Base de données)
- **Serveur Web** (Apache, Nginx ou PHP intégré)

---

## **Installation**

1. **Cloner le dépôt**

```bash
   git clone git@github.com:ClemLy/Maison-Eau-d-Or.git
   cd Maison-Eau-d-Or

2. **Installer les dépendances**

   ```bash
    composer install

3. **Configuration de l'environnement**

   Créer un fichier `.env` directement dans le dossier `SGT`.
   Ce fichier contiendra ceci :
   
   ```env
   #--------------------------------------------------------------------
   # DATABASE
   #--------------------------------------------------------------------
   
   CI_ENVIRONMENT=production             # Mode de l'application : 'development' ou 'production'
   db_hostname=localhost                 # Hôte de la base de données (souvent 'localhost')
   db_username=..                        # Nom d'utilisateur pour la connexion à la base de données
   db_password=..                        # Mot de passe de l'utilisateur de la base de données
   db_DBDriver=Postgre                   # Type de base de données (PostgreSQL dans ce cas)
   db_name=..                            # Nom de la base de données
   db_port=..                            # Port de connexion à la base de données (souvent 5432 pour PostgreSQL)
   
   #--------------------------------------------------------------------
   # Email
   #--------------------------------------------------------------------
   
   email_host=.....                      # Hôte du serveur SMTP pour l'envoi d'emails (ex : smtp.gmail.com)
   email_user=.....                      # Adresse email utilisée pour envoyer les emails (ex : votre-email@gmail.com)
   email_password=...                    # Mot de passe de l'email pour l'authentification SMTP
   email_port=587                        # Port pour l'envoi d'emails (587 est généralement utilisé pour TLS)
   ```

4. **Exécuter l'environnement**

   Afin d'exécuter votre fichier `.env`, installez la bibliothèque vlucas/phpdotenv en exécutant cette commande dans le terminal :
   ```cmd
    cd Maison-Eau-d-Or
    composer require vlucas/phpdotenv
   ```

5. **Démarrer le serveur local**
  
   ```bash
    cd Maison-Eau-d-Or
    php spark serve
   ```
   
   L'application sera disponible à l'adresse : http://localhost:8080
