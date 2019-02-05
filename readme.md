### Deploy
 - git clone https://github.com/nonamez/messages.git Messages && cd Messages
 - mysql -u user -p messages < Messages/dump.sql
 - cp .env.example .env
 - nano .env (edit config)
 - composer install
 - npm i && npm run dev
 - composer start