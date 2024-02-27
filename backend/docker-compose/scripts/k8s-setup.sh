#!/usr/bin/env bash
chmod -R 755 /code/app
chmod -R o+w /code/app/storage
composer install
cat .env.docker.example >> .env
php artisan key:generate
php artisan passport:install

# php artisan migrate
# php artisan passport:install >> passport.txt

# Add the passport client ID and secret in .env file
# clientIdLookup=$(grep -w "Client ID: 2" passport.txt)
# clientIdValue=$(echo -e "${clientIdLookup//Client ID: /}" | sed 's/^[ \t]*//;s/[ \t]*$//')

# clientSecretLookup=$(grep -w "Client secret:" passport.txt)
# echo -e "${clientSecretLookup//Client secret: /}" | sed 's/^[ \t]*//;s/[ \t]*$//' >> output.txt
# clientSecretvalue=$(awk 'NR==2{ print; exit }' output.txt)

# echo -e "\nPASSWORD_CLIENT_ID=$clientIdValue" >> .env
# echo -e "PASSWORD_CLIENT_SECRET=$clientSecretvalue" >> .env

# Remove the senstive files
# rm output.txt
# rm passport.txt

# Run this command if the storage directory has a restricted permissions
# chmod -R 755 /code/app
# chmod -R o+w /code/app/storage

# mysql:
# Resolving the 'Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (2)' Error:
# Create a symlink from the location of mysqld.sock to the /var/run/mysqld directory:
# 1. sudo find / -type s => [path to mysqld.sock]
# 2. ln -s [path to mysqld.sock] /var/run/mysqld/mysqld.sock

# kubectl --kubeconfig=$HOME/.kube/do-nyc1-k8s-1-25-4-do-0-nyc1-1679458124795-kubeconfig.yaml get nodes
# doctl kubernetes cluster kubeconfig save k8s-1-25-4-do-0-nyc1-1679458124795

# steps in digitalocean
# deploy php
# copy the source code from /var/www to /code/app
# run composer install
# create the .env files
# deploy nginx

# Personal access client created successfully.
# Client ID: 1
# Client secret: HWUiWMm7UycDxhzErg8N3OLeDZJeYl1Ge78mPzUH
# Password grant client created successfully.
# Client ID: 2
# Client secret: 4cyktQIgc6b2rx1J1UZqbikZ6oy21i1aCaJZsovW