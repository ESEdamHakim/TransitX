# 🚍 TransitX – Plateforme de Mobilité Urbaine Durable

TransitX est une plateforme intelligente conçue pour améliorer la mobilité urbaine en favorisant les transports partagés, le covoiturage et la livraison éco-responsable. Elle vise à réduire les émissions de CO₂, fluidifier la circulation et rendre les villes plus durables.

## 🌐 Fonctionnalités Principales

- 🔐 Gestion des utilisateurs (clients, employés)
- 🚗 Covoiturage (création & consultation de trajets)
- 📦 Colis (gestion des colis et suivi en temps réel)
- 🚌 Visualisation des bus et des horaires
- 📊 Tableau de bord administratif (statistiques, gestion des modules)
- 🗺️ Intégration avec OpenStreetMap & OpenRouteService

## 🏗️ Technologies Utilisées

- Frontend : HTML5, CSS3, JavaScript
- Backend : PHP 8
- Base de données : MySQL

## 📦 Structure du Projet

📁 TransitX  
├── frontend/  
│   ├── assets/  
│   ├── templates/ (Client / Admin)  
│   └── index.html  
├── backend/  
│   ├── controllers/  
│   ├── models/  
├── database/  
│   └── schema.sql  
└── README.md

## 🧑‍🤝‍🧑 Modules et Répartition

| Module       | Entités principales                | Responsable      |
|--------------|------------------------------------|------------------|
| Client       | Client, Chauffeur (héritage)       | Membre 1         |
| Covoiturage  | Trajet, Réservation (lecture seule pour clients) | Membre 2         |
| Livraison    | Livraison, Historique livraison    | Membre 3         |
| Employé      | Employé, Rôle                      | Membre 4         |
| Bus          | Bus, Trajet                        | Membre 5         |

## 🎯 Objectifs de Développement Durable (ODD)

- ODD 9 : Innovation et infrastructure intelligente
- ODD 11 : Villes durables & mobilité écologique
- ODD 13 : Réduction des émissions grâce au transport partagé

🌱 TransitX optimise la mobilité avec des technologies intelligentes, des transports partagés et une empreinte écologique réduite.

## ⚙️ Installation Locale

1. Clonez le dépôt :
   git clone https://github.com/votre-utilisateur/transitx.git

2. Configurez votre base de données (voir /database/schema.sql)

3. Lancez le serveur local :
   php -S localhost:8000 -t frontend

4. Accédez à l’application :
   http://localhost:8000

## 📃 Licence

Ce projet est sous licence MIT.

—

Souhaitez-vous que je génère le fichier complet pour téléchargement ou le place dans un projet GitHub ?
