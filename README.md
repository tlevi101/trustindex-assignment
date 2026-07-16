# Trustindex – Vélemény kezelő

Egyszerű cég értékelő felület. Frontend JS kód egyszerűsítéséhez Alpine.js-t használtam. 

## Live demo
Projekt megtekinthető ezen az oldalon: [trustindex-demo.leventetorma.dev](https://trustindex-demo.leventetorma.dev)
## Technológiák

- PHP 8 / **Symfony 7.4**
- **Doctrine ORM 3** + Doctrine Migrations
- **PostgreSQL 16**
- Twig, Alpine.js (natív ES modul, build lépés nélkül)
- PHPUnit
- Docker / Docker Compose

## Előfeltételek

- Docker + Docker Compose
- Ammennyiben docker nem árr rednelkezés lehet 

## Telepítés és futtatás

```bash
# 1. Konténerek indítása (php + postgres)
docker compose up -d --build

# 2. Függőségek telepítése (nem kell a dockeren belül futtatni)
composer install
```

Az alkalmazás ezután elérhető: **http://localhost:8000**

> Megjegyzés: a projekt Dockerrel fut azon belül pedig a 8000-es porton fut, így nem a symfony:serve parancsot kell használni

## Adatbázis létrehozás és migrációk

```bash
# Adatbázis létrehozása (ha még nem létezik)
docker compose exec php bin/console doctrine:database:create

# Migrációk futtatása
docker compose exec php bin/console doctrine:migrations:migrate

# Teszt/minta adatok betöltése (opcionális, a táblát üríti!)
docker compose exec php bin/console doctrine:fixtures:load
```

## Tesztek futtatása

```bash
# Teszt adatbázis létrehozása
docker compose exec php bin/console --env=test doctrine:database:create

# Tesztek futtatása
docker compose exec php php bin/phpunit
```

- **Unit teszt** (`tests/Unit/ReviewTest.php`): az entitás timestamp-logikája.
- **Funkcionális teszt** (`tests/Functional/CompanyStatsRepositoryTest.php`):
  a cégstatisztika átlag- és rendezési logikája valós adatbázison.

## Útvonalak

| Útvonal | Név | Leírás |
|---|---|---|
| `/` | `review_index` | Vélemények listája (lapozással) |
| `/new` | `review_new` | Új vélemény rögzítése |
| `/{id}` | `review_show` | Vélemény részletei |
| `/companies` | `company_stats` | Cégenkénti statisztika |

## Munkaidő napló


| Feladat | Leírás | Idő   |
|---|---|-------|
| 1. | Projekt setup (Symfony, Docker, Postgres) | 1 h   |
| 2. | Entitás + migráció (`Review`) | 30 p  |
| 3. | Vélemény űrlap (validáció, csillagos értékelés) | 1 h   |
| 4. | Listaoldal + lapozás | 30 p  |
| 5. | Részletező oldal | 30 p  |
| 6. | Cégenkénti statisztika (2.4) | 30 p  |
| 7. | Tesztelés (unit + funkcionális) | 30 p  |
| | **Összesen** | 4,5 h |
