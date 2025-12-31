npm run dev# GESTECO - Système de Gestion des Absences Étudiantes

## 📋 Vue d'ensemble

GESTECO est une application web de gestion des absences étudiantes développée avec Laravel. Le système permet de suivre les absences des étudiants, de calculer automatiquement leurs notes d'absence, et de gérer l'ensemble des informations académiques liées à l'assiduité.

## ✨ Fonctionnalités Principales

### Gestion des Étudiants
- ✅ Création, modification, suppression et consultation des étudiants
- ✅ Affichage de la note d'absence calculée automatiquement
- ✅ Visualisation de l'historique complet des absences par étudiant
- ✅ Pagination et recherche facilitée

### Gestion des Absences
- ✅ Enregistrement des absences avec date et séance
- ✅ Marquage des absences comme justifiées ou non justifiées
- ✅ Modification et suppression des enregistrements d'absence
- ✅ Visualisation chronologique des absences

### Système de Notation Automatique
- ✅ Calcul automatique des notes d'absence (20 - 0.25 × nombre d'absences non justifiées)
- ✅ Mise à jour en temps réel lors de l'ajout/modification/suppression d'absences
- ✅ Système de remarques personnalisables
- ✅ Affichage des étudiants en difficulté (note < 10)

### Interface Utilisateur
- ✅ Dashboard avec statistiques en temps réel
- ✅ Navigation intuitive avec barre de menu responsive
- ✅ Support complet du mode sombre (Dark Mode)
- ✅ Design moderne avec Tailwind CSS
- ✅ Alertes et notifications de succès/erreur

## 🛠️ Stack Technique

### Backend
- **Framework**: Laravel 11.x
- **Langage**: PHP 8.2+
- **ORM**: Eloquent
- **Validation**: Laravel Form Requests
- **Architecture**: MVC (Model-View-Controller)

### Frontend
- **Template Engine**: Blade (Laravel)
- **CSS Framework**: Tailwind CSS 3.x
- **Build Tool**: Vite
- **JavaScript**: Alpine.js (pour les composants interactifs)
- **Icons**: Heroicons (SVG)

### Base de Données
- **SGBD**: MySQL / MariaDB / SQLite / PostgreSQL
- **Migrations**: Laravel Migrations
- **Seeders**: Laravel Seeders avec Factories

### Authentification
- **Package**: Laravel Breeze
- **Features**: Login, Register, Password Reset, Email Verification

## 📊 Structure de la Base de Données

### Table `etudiants`
| Colonne       | Type          | Description                    |
|---------------|---------------|--------------------------------|
| id_etudiant   | VARCHAR(15)   | Clé primaire (ID étudiant)     |
| nom           | VARCHAR(255)  | Nom de l'étudiant              |
| prenom        | VARCHAR(255)  | Prénom de l'étudiant           |
| filiere       | VARCHAR(255)  | Filière d'études               |
| created_at    | TIMESTAMP     | Date de création               |
| updated_at    | TIMESTAMP     | Date de modification           |

### Table `absences`
| Colonne        | Type          | Description                    |
|----------------|---------------|--------------------------------|
| Id_Absence     | VARCHAR(15)   | Clé primaire (ID absence)      |
| date_absence   | DATE          | Date de l'absence              |
| seance         | INTEGER       | Numéro de séance (1-4)         |
| justifie       | BOOLEAN       | Absence justifiée (oui/non)    |
| id_etudiant    | VARCHAR(15)   | Clé étrangère → etudiants      |
| created_at     | TIMESTAMP     | Date de création               |
| updated_at     | TIMESTAMP     | Date de modification           |

### Table `noteabs`
| Colonne       | Type          | Description                    |
|---------------|---------------|--------------------------------|
| Id_note       | VARCHAR(15)   | Clé primaire (ID note)         |
| note          | DECIMAL(4,2)  | Note calculée (0.00 - 20.00)   |
| remarque      | TEXT          | Remarque optionnelle           |
| id_etudiant   | VARCHAR(15)   | Clé étrangère → etudiants      |
| created_at    | TIMESTAMP     | Date de création               |
| updated_at    | TIMESTAMP     | Date de modification           |

### Relations
- **Étudiant → Absences**: One-to-Many (Un étudiant peut avoir plusieurs absences)
- **Étudiant → Note**: One-to-One (Un étudiant a une seule note d'absence)
- **Cascade Delete**: La suppression d'un étudiant supprime ses absences et sa note

## 🧮 Logique Métier

### Calcul Automatique des Notes

Le système utilise un mécanisme de **calcul automatique** basé sur les événements Eloquent:

#### Formule de Calcul
```php
Note = 20 - (Nombre d'absences non justifiées × 0.25)
Note minimale = 0
```

#### Exemples
- 0 absence non justifiée → Note: 20/20
- 4 absences non justifiées → Note: 19/20
- 40 absences non justifiées → Note: 10/20
- 80+ absences non justifiées → Note: 0/20

#### Mécanisme Automatique

Le calcul est déclenché automatiquement via les **Model Events** dans `app/Models/absence.php`:

```php
protected static function booted(): void
{
    static::created(function ($absence) {
        $absence->mettreAJourNoteAbsence();
    });

    static::updated(function ($absence) {
        $absence->mettreAJourNoteAbsence();
    });

    static::deleted(function ($absence) {
        $absence->mettreAJourNoteAbsence();
    });
}
```

**Quand la note est-elle mise à jour ?**
- ✅ Lors de la création d'une nouvelle absence
- ✅ Lors de la modification d'une absence (changement de statut justifié/non justifié)
- ✅ Lors de la suppression d'une absence

### Workflow de Calcul

1. **Événement déclenché** (création/modification/suppression d'absence)
2. **Récupération de l'étudiant** associé à l'absence
3. **Comptage des absences non justifiées** via `calculerNoteAbsence()`
4. **Calcul de la note** avec la formule
5. **Création ou mise à jour** de l'enregistrement dans `noteabs` via `updateOrCreate()`

### Règles de Validation

#### Étudiants
- `id_etudiant`: requis, max 15 caractères, unique
- `nom`: requis, max 255 caractères
- `prenom`: requis, max 255 caractères
- `filiere`: requis, max 255 caractères

#### Absences
- `Id_Absence`: requis, max 15 caractères, unique
- `date_absence`: requis, format date valide
- `seance`: requis, integer entre 1 et 4
- `justifie`: boolean (checkbox)
- `id_etudiant`: requis, doit exister dans la table etudiants

#### Notes
- `remarque`: optionnel, texte libre
- `note`: calculée automatiquement, non modifiable manuellement

## 🚀 Installation

### Prérequis
- PHP >= 8.2
- Composer
- Node.js >= 18.x
- NPM ou Yarn
- MySQL/MariaDB/PostgreSQL/SQLite

### Étapes d'Installation

1. **Cloner le projet**
```bash
git clone <repository-url>
cd gesteco
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Installer les dépendances JavaScript**
```bash
npm install
```

4. **Configurer l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configurer la base de données**

Éditer le fichier `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gesteco
DB_USERNAME=root
DB_PASSWORD=
```

6. **Exécuter les migrations**
```bash
php artisan migrate
```

7. **Générer des données de test (optionnel)**
```bash
php artisan db:seed
```

8. **Compiler les assets**
```bash
npm run dev
# ou pour la production
npm run build
```

9. **Lancer le serveur de développement**
```bash
php artisan serve
```

L'application sera accessible sur `http://localhost:8000`

## 📱 Utilisation

### Accès au Dashboard
1. Créer un compte ou se connecter
2. Accéder au dashboard pour voir les statistiques globales:
   - Nombre total d'étudiants
   - Nombre total d'absences
   - Nombre d'absences non justifiées
   - Nombre d'étudiants en difficulté (note < 10)
   - Liste des absences récentes

### Gestion des Étudiants
- **Ajouter**: Cliquer sur "Nouvel Étudiant" et remplir le formulaire
- **Consulter**: Cliquer sur "Voir" pour afficher les détails et l'historique
- **Modifier**: Cliquer sur "Modifier" pour éditer les informations
- **Supprimer**: Cliquer sur "Supprimer" (supprime aussi les absences et notes)

### Enregistrement des Absences
1. Aller dans "Absences" → "Nouvelle Absence"
2. Saisir l'ID de l'absence
3. Sélectionner l'étudiant
4. Choisir la date et la séance
5. Cocher "Absence justifiée" si applicable
6. Valider

**Important**: La note de l'étudiant est automatiquement mise à jour !

### Consultation des Notes
- Aller dans "Notes" pour voir toutes les notes calculées
- Les notes < 10 sont affichées en rouge
- Les notes ≥ 10 sont affichées en vert
- Possibilité d'ajouter des remarques personnalisées

## 🎨 Fonctionnalités UI/UX

### Mode Sombre
- Activation automatique selon les préférences système
- Tous les tableaux et formulaires supportent le dark mode
- Contraste optimisé pour une meilleure lisibilité

### Design Responsive
- Adaptation automatique mobile/tablette/desktop
- Menu hamburger sur mobile
- Tableaux avec scroll horizontal si nécessaire

### Feedback Utilisateur
- Messages de succès en vert
- Messages d'erreur en rouge
- Messages informatifs en bleu
- Validation en temps réel des formulaires

## 📂 Structure du Projet

```
gesteco/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AbsenceController.php      # Gestion CRUD absences
│   │   │   ├── EtudiantController.php     # Gestion CRUD étudiants
│   │   │   └── NoteabsController.php      # Gestion notes
│   │   └── Requests/
│   └── Models/
│       ├── absence.php                     # Modèle Absence avec logique métier
│       ├── etudiant.php                    # Modèle Étudiant
│       ├── noteabs.php                     # Modèle Note
│       └── User.php                        # Modèle Utilisateur
├── database/
│   ├── factories/                          # Factories pour tests
│   ├── migrations/                         # Migrations de BDD
│   └── seeders/                            # Seeders
├── resources/
│   ├── css/
│   │   └── app.css                         # Styles Tailwind
│   ├── js/
│   │   └── app.js                          # JavaScript principal
│   └── views/
│       ├── absences/                       # Vues absences
│       ├── etudiants/                      # Vues étudiants
│       ├── noteabs/                        # Vues notes
│       ├── layouts/                        # Layouts (navigation, app)
│       └── dashboard.blade.php             # Page dashboard
├── routes/
│   ├── web.php                             # Routes web
│   └── auth.php                            # Routes authentification
├── .env.example                            # Configuration exemple
├── composer.json                           # Dépendances PHP
├── package.json                            # Dépendances JavaScript
├── tailwind.config.js                      # Configuration Tailwind
└── vite.config.js                          # Configuration Vite
```

## 🔒 Sécurité

- ✅ Protection CSRF sur tous les formulaires
- ✅ Validation des données côté serveur
- ✅ Authentification requise pour accéder aux fonctionnalités
- ✅ Hash des mots de passe avec bcrypt
- ✅ Protection contre les injections SQL via Eloquent
- ✅ Validation des clés étrangères

## 🧪 Tests

Pour exécuter les tests:
```bash
php artisan test
```

## 📝 Notes Techniques

### Performance
- Utilisation de `with()` pour éviter le problème N+1 queries
- Pagination automatique des résultats
- Index sur les clés étrangères

### Conventions de Code
- PSR-12 pour le code PHP
- Camel case pour les méthodes
- Snake case pour les colonnes de base de données
- Noms de routes RESTful

### Compatibilité
- Compatible avec PHP 8.2+
- Testé sur MySQL 8.0, MariaDB 10.x
- Compatible avec tous les navigateurs modernes

## 🤝 Contribution

Les contributions sont les bienvenues ! Pour contribuer:
1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT.

## 👤 Auteur

Développé pour la gestion des absences étudiantes.

## 🆘 Support

Pour toute question ou problème:
- Ouvrir une issue sur GitHub
- Consulter la documentation Laravel: https://laravel.com/docs

---

**Version**: 1.0.0  
**Date**: 31 Décembre 2025  
**Framework**: Laravel 11.x

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
