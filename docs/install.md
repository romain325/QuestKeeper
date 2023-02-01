# Installation

backend: raw php  
frontend: vue3 with npm  
database: postgresql  

## Backend

install php  
install php-pgsql  
phpenmod pdo-pgsql
phpenmod pgsql

in cmd line run `php -S localhost:42069 Main.php`  
version 7.4 

## Frontend

cd QuestKeeperFront  
npm install  
npm run dev

for front-compoenents we use tailwindcss with daisyUI
`npx tailwindcss -i ./src/assets/main.css -o ./dist/output.css --watch`


## Database

Install posgresql and start it  
accessible now at port 5432  
Use dbeaver  
