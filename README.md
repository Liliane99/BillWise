BillWise App


 BillWise est une plateforme en ligne complète conçue pour simplifier la gestion administrative des centres de formation. Elle permet de gérer efficacement les factures, les devis, les clients, les produits, ainsi que les paiements, offrant ainsi un ensemble d'outils indispensables pour les professionnels de l'éducation.

 
Username Git :

Liliane Mezani => Liliane99
Ines Hadidi => InesH2001
Huy Hoang Nguyen => nghuyhoang0204


Github :

https://github.com/InesH2001/facTech/tree/main
Lien de notre site en production sur le ReadMe du Git

Site : 
 
 Fonctionnalités
 
Authentification :

Inscription: Les utilisateurs peuvent s'inscrire en créant un compte sur la plateforme. Connexion: Les utilisateurs enregistrés peuvent se connecter à leur compte.

Facturation et Devis :

-> Génération de facture en PDF: Les utilisateurs peuvent générer des factures au format PDF.

-> Envoi de facture par mail: Possibilité d'envoyer les factures générées par mail directement depuis la plateforme.

-> Génération de devis en PDF: Les utilisateurs peuvent créer des devis au format PDF.

-> Envoi de devis par mail: Les devis peuvent être envoyés par mail directement depuis l'application.

Gestion des Clients et Produits

-> Ajout de clients: Les utilisateurs peuvent ajouter de nouveaux clients à leur base de données.

-> Ajout de produits: Possibilité d'ajouter de nouveaux produits avec leurs détails (nom, prix, description, etc.).

-> Édition de clients et produits: Les informations des clients et des produits peuvent être modifiées à tout moment.

Gestion des Paiements -> Suivi des paiements: Les utilisateurs peuvent suivre les paiements effectués pour chaque facture.

-> Ajout de paiements: Possibilité d'ajouter des paiements manuellement pour les factures.

-> Relance des paiements en retard: La plateforme permet de relancer automatiquement les paiements en retard, aidant ainsi à maintenir une trésorerie saine.

Datavisualisation : -> Dashboard et génération de rapport financier en xlsx.

 Partage des tâches :
 
=> Création de la base de données et CRUD : Liliane Mezani (Liliane99), Ines Hadidi (InesH2001) et Huy Hoang Nguyen (nghuyhoang0204).

=> Front Home Page, Nos services, Apropos : Liliane Mezani (Liliane99) et Huy Hoang Nguyen (nghuyhoang0204).

=> Inscription : Liliane Mezani (Liliane99). => Connexion : Ines Hadidi (InesH2001).

=> Gestion des utilisateurs, des établissements (centre de formations) : Ines Hadidi (InesH2001).

=> Création de la facture, édition (modification ou/et suppression) , visualisation , génération du PDF et envoie par mail : Liliane Mezani (Liliane99).

=> Création du devis , édition (modification ou/et suppression) , visualisation , génération du PDF et envoie par mail : Ines Hadidi (InesH2001).

=> Gestion des clients par établissement ; Ajout des clients, édition (modification ou/et suppression), visualisation. Possibilité de lancer une recherche par nom, prénom, établissement : Liliane Mezani (Liliane99)

=> Gestion des produits (cours) par établissement ; Ajout des produits, édition (modification ou/et suppression), visualisation. Possibilité de lancer une recherche par produit, établissement : Ines Hadidi (InesH2001).

=> Paiement : Ajout d'un paiement, gestion des paiements par factures, calcul de montant payé, montant restant à payer et mise à jour du statut de la facture. Suivis du paiement, édition (modification et/ou suppression), visualisation et "Relance du paiement" en cas de retard (fonctionnalité bonus) : Liliane Mezani (Liliane99)

=> Dashboard : Visualisation du chiffre d'affaires, Top des 5 meilleurs clients, graphique et report et génération de rapport financier en xlsx : Ines Hadidi (InesH2001).

=> Maquettage UX/UI : Huy Hoang Nguyen (nghuyhoang0204).

 Installation et Exécution
 
Clonez ce dépôt sur votre machine locale en utilisant la commande : git clone
cd BillWise
Installez les dépendances en utilisant :
npm install
Technologies Utilisées Symfony Tailwind CSS JavaScript ORM : Doctrine

 UX/UI
Interview avec un Gestionnaire de Facturation de l’université Paris8 (F.R) :
Interviewer (Moi): Bonjour, merci de prendre le temps de discuter avec moi aujourd'hui. Pour commencer, pourriez-vous vous présenter et nous en dire un peu plus sur votre rôle en tant que Gestionnaire de facturation dans l’université de Paris 8?
Gestionnaire de facturation (F.R): Bonjour, je suis ravi de participer à cette entrevue. En tant que Gestionnaire de Facturation ici, je suis responsable de superviser tout le processus de facturation, depuis la création des factures jusqu'au suivi des paiements et des soldes des clients.
Moi: C'est compréhensible. Pouvez-vous me parler des principaux défis auxquels vous êtes confronté dans le processus actuel de gestion des factures?
F.R: Bien sûr. Actuellement, nous faisons face à plusieurs défis, tels que la saisie manuelle des données, le suivi des paiements en retard, et la gestion des différentes catégories de tarification pour nos divers programmes de formation. De plus, la communication avec nos clients pour les rappels de paiement peut parfois être laborieuse.
Moi: Ces défis sont clairs. Dans l'optique de développer un site de facturation sur mesure pour répondre à vos besoins spécifiques, pourriez-vous préciser quelles sont les fonctionnalités essentielles que vous aimeriez voir intégrées?
F.R: Absolument. Tout d'abord, une fonction de génération automatique des factures en fonction des inscriptions aux formations serait très utile. Une option de suivi automatisé des paiements en retard et une interface conviviale pour les clients afin qu'ils puissent consulter et payer leurs factures en ligne seraient également des atouts importants. Enfin, une manière simple de gérer les différentes catégories de tarification et d'ajuster les prix au besoin serait grandement appréciée.
Moi: Merci pour ces détails. Concernant la sécurité des données, avez-vous des préoccupations particulières que nous devrions prendre en compte lors du développement du site?
F.R: La sécurité des données est une priorité pour nous. Il est crucial que toutes les informations financières et personnelles des clients soient protégées de manière fiable. Un système de sécurité robuste et des protocoles de cryptage efficaces seraient des aspects clés.
Moi: Compris. Enfin, en ce qui concerne l'intégration avec d'autres systèmes que vous pourriez utiliser ici, y a-t-il des logiciels ou des plateformes spécifiques auxquels le nouveau système de facturation devrait se connecter?
F.R: Nous utilisons actuellement un système de gestion des inscriptions et des données des étudiants. Une intégration fluide entre le site de facturation et ce système serait extrêmement bénéfique pour éviter toute duplication de travail.
 Interview 01 :
  
 Moi: Cela semble être une information cruciale. Merci beaucoup de partager vos besoins avec nous. Nous allons prendre en compte toutes ces informations lors du développement du site de facturation sur mesure pour le centre de formation.
F.R: Merci à vous. J'apprécie l'opportunité de contribuer à la création d'un outil qui facilitera grandement notre processus de facturation.
Persona - Gestionnaire de Facturation dans un centre de formation :
Nom: Ne veut pas que ça soit indiqué Âge: Entre 35 et 45 ans
Fonction: Gestionnaire de Facturation
Expérience professionnelle: Plus de 8 ans d'expérience dans le domaine de la gestion financière et de la facturation, avec une expérience spécifique dans le secteur de l'éducation et de la formation.
Responsabilités principales:
● Supervise l'ensemble du processus de facturation, depuis la création des factures jusqu'au suivi des paiements et des soldes des clients.
● Gère les différentes catégories de tarification pour les programmes de formation variés.
● Assure la communication avec les clients pour les rappels de paiement et résout les problèmes liés aux factures.
Défis professionnels:
● Saisie manuelle des données dans le processus de facturation actuel.
● Suivi des paiements en retard et gestion des différentes catégories de
tarification.
● Communication laborieuse avec les clients pour les rappels de paiement.
Besoins et attentes pour le site de facturation:
● Génération automatique des factures en fonction des inscriptions aux formations.
● Suivi automatisé des paiements en retard.
● Interface conviviale pour les clients permettant la consultation et le paiement
en ligne des factures.
● Gestion simplifiée des différentes catégories de tarification avec la possibilité
d'ajuster les prix au besoin.

● Sécurité des données optimale avec des protocoles de cryptage efficaces.
● Intégration fluide avec le système de gestion des inscriptions et des données
des étudiants déjà utilisé.
Attitude envers la technologie:
● Apprécie les solutions technologiques qui simplifient les processus et réduisent les tâches manuelles.
● Souhaite utiliser des outils conviviaux qui ne nécessitent pas une expertise technique approfondie.
● Attaché à la sécurité des données et à la confidentialité.
Objectifs professionnels:
● Améliorer l'efficacité du processus de facturation pour optimiser le temps et les ressources.
● Mettre en place un système qui permet une communication transparente avec les clients et réduit les retards de paiement.
● Intégrer des solutions technologiques pour rester à jour avec les meilleures pratiques du secteur.
