# WR506 - Back - Movies API

This is an Movies API for a university project.
It was develop with Symfony.

![Logo](https://cdn.worldvectorlogo.com/logos/symfony.svg)


# **Initial setup**

**Step 0. Composer install**
```bash
  composer i
```

**Step 1. Run server**
- Run MAMP

**Step2. Copy .env**
- Copy .env for .env.local and fill the DATABASE part
```bash
  cp .env .env.local
```

**Step 3. Migration execution**
```bash
php bin/console doctrine:migrations:migrate
```

**Step4. Fixtures execution**
```bash
php bin/console doctrine:fixtures.load
```

**That's it! You are ready to work ! 🎉**

