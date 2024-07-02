## Install project
git clone https://github.com/TuanPivo/timecard.git

copy file env.example to env

run composer install

run php artisan generate:key

config db
db name : time-card

run php artisan migrate

run php artisan db:seed --class=HolidaysTableSeeder
run php artisan db:seed --class=UserSeeder


