## 📁 PHP MVC File Manager (BenGeenBij/file-manager)
Een robuuste, razendsnelle en objectgeoriënteerde bestandsbeheerder gebouwd in PHP. Dit project maakt gebruik van het MVC-ontwerppatroon (Model-View-Controller) en biedt gebruikers zowel een veilige, persoonlijke opslagruimte als gedeelde groepsmappen voor vlotte samenwerking.

## 🚀 Hoe het werkt (Features & Architectuur)
De applicatie is gebouwd om grote hoeveelheden bestanden efficiënt te verwerken zonder de server of de browser te overbelasten.

Persoonlijke & Gedeelde Ruimtes: Elke gebruiker heeft een geïsoleerde privémap (/storage/users/user_[id]). Daarnaast kunnen beheerders gebruikers toevoegen aan groepen, waardoor ze toegang krijgen tot gedeelde mappen (/storage/groups/group_[id]).

Geavanceerde Uploads (Chunking): Bestanden worden geüpload via Dropzone.js. Grote bestanden worden in stukjes ("chunks") geknipt en naar de API gestuurd. Hierdoor kun je de PHP upload_max_filesize limieten omzeilen en bestanden van gigabytes groot veilig overzetten.

Slimme Sortering & Weergave: Gebruikers kunnen naadloos schakelen tussen een lijst- en tegelweergave (voorkeur wordt opgeslagen in localStorage). De lijstweergave is volledig client-side sorteerbaar (op naam, bestandstype, grootte en datum) zonder de pagina opnieuw te laden. Mappen blijven hierbij altijd slim bovenaan staan.

Performance & Caching: Thumbnails voor afbeeldingen worden server-side gegenereerd via de GD-bibliotheek en opgeslagen in een tijdelijke cache. Er wordt gebruikgemaakt van HTTP 304 Not Modified browser caching, waardoor fotogalerijen razendsnel inladen. Video's en documenten krijgen dynamische FontAwesome-iconen.

Bulk Acties & Shortcuts: Selecteer razendsnel alles met Ctrl+A. Je kunt in bulk bestanden verwijderen of mappen in hun geheel downloaden als een on-the-fly gegenereerd ZIP-archief.




## 🗄️ Hoe de SQL in elkaar zit (Database Structuur)
Het systeem werkt volledig op basis van database-ID's om paden en rechten te bepalen. Hierdoor is het toewijzen van mappen dynamisch en uiterst veilig. We gebruiken drie tabellen:

users: Bevat de accounts. In plaats van een hardcoded pad in de database te zetten, berekent de app de persoonlijke map automatisch aan de hand van het id (bijv. user_1).

groups: Bevat de gedeelde ruimtes.

user_groups: Een relationele koppeltabel. Deze vertelt de applicatie welke gebruiker lid is van welke groep(en).

Database Installatie Script
Voer de onderstaande SQL-query's uit in je database (bijvoorbeeld via phpMyAdmin of de terminal) om de structuur op te zetten:

## SQL
-- 1. Gebruikers Tabel
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` VARCHAR(20) DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Groepen Tabel (Voor gedeelde ruimtes)
CREATE TABLE IF NOT EXISTS `groups` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Koppeltabel (Veel-op-veel relatie tussen users en groups)
CREATE TABLE IF NOT EXISTS `user_groups` (
    `user_id` INT NOT NULL,
    `group_id` INT NOT NULL,
    PRIMARY KEY (`user_id`, `group_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`group_id`) REFERENCES `groups`(`id`) ON DELETE CASCADE
);


## 📂 Mappenstructuur

```text
/
├── index.php                 # Hoofdroutering
├── config/
│   ├── config.php            # Algemene instellingen & sessiebeheer
│   └── database.php          # PDO MySQL connectie
├── core/                     
│   ├── Auth.php              # Login, validatie en sessie logica
│   ├── FileOperations.php    # Functies voor kopiëren, verplaatsen, ZIP, etc.
│   └── Helpers.php           # Bestandsgrootte en iconen formatteren
├── controllers/
│   ├── ApiController.php     # Verwerkt POST acties (opslaan, verwijderen, uploaden)
│   └── ViewController.php    # Bepaalt welke weergave geladen wordt
└── views/                    # HTML/Bootstrap templates (header, footer, login, tabel, editor)
