# PHP MVC File Manager

Een lichtgewicht, veilige en multi-user bestandsbeheerder voor het web. 

Deze applicatie is een gemoderniseerde, modulaire herschrijving van de originele [Tiny File Manager](https://tinyfilemanager.github.io/). Het originele monolithische script is opgesplitst in een schone **MVC-architectuur** (Model-View-Controller) en uitgebreid met **MySQL-database integratie** voor robuust gebruikersbeheer en map-isolatie.

## ✨ Kenmerken

* **Multi-User Ondersteuning:** Beheer meerdere gebruikers via een MySQL-database.
* **Map Isolatie:** Elke gebruiker krijgt zijn eigen, afgeschermde map binnen de hoofd `/storage` directory.
* **MVC Architectuur:** Logica, configuratie en weergave (UI) zijn strikt gescheiden voor makkelijker onderhoud.
* **Beveiliging voorop:**
  * Beveiligd tegen SQL-injecties middels **PDO prepared statements**.
  * Beveiligd tegen **CSRF-aanvallen** met dynamische sessietokens.
  * Strenge pad-validatie (`realpath`) om **Directory Traversal** (`../`) te voorkomen.
  * Wachtwoorden worden veilig gehasht met `password_hash()` (Bcrypt).
* **Moderne UI:** Volledig responsive interface gebouwd met **Bootstrap 5** en FontAwesome iconen.
* **Bestandsbeheer:** Uploaden, mappen aanmaken, bestanden bewerken, verwijderen en downloaden.

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
