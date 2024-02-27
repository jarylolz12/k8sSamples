#!/usr/bin/env bash

# use the example envs to create a .env file
rm -rf .env
cat .env.docker.example >> .env

# Run the docker compose commands:
docker compose build app
docker compose up -d

# Pause the deployment in order for the SQL to completely run
sleep 30s

# Execute docker compose/php commands like db migration key and passport registration
docker compose exec app composer install
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
# php artisan vendor:publish --provider="BeyondCode\Mailbox\MailboxServiceProvider" --tag="migrations"
docker compose exec app php artisan migrate # --seed
docker compose exec app php artisan vendor:publish --provider="BeyondCode\Mailbox\MailboxServiceProvider" --tag="config"
docker compose exec app php artisan key:generate
docker compose exec app php artisan passport:install >> passport.txt

# Add the passport client ID and secret in .env file
clientIdLookup=$(grep -w "Client ID: 2" passport.txt)
clientIdValue=$(echo -e "${clientIdLookup//Client ID: /}" | sed 's/^[ \t]*//;s/[ \t]*$//')

clientSecretLookup=$(grep -w "Client secret:" passport.txt)
echo -e "${clientSecretLookup//Client secret: /}" | sed 's/^[ \t]*//;s/[ \t]*$//' >> output.txt
clientSecretvalue=$(awk 'NR==2{ print; exit }' output.txt)

echo -e "\nPASSWORD_CLIENT_ID=$clientIdValue" >> .env
echo -e "PASSWORD_CLIENT_SECRET=$clientSecretvalue" >> .env

# Remove the senstive files
rm output.txt
rm passport.txt