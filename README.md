# wildumni-back

- run those commands:

```bash
composer update
composer install
```

- then copy `.env` to `.env.local` and update your configuration
- then run:

```bash
php bin/console lexik:jwt:generate-keypair
symfony console d:d:c
symfony console d:m:m
symfony console d:f:l
symfony serve
```

You're up to go
