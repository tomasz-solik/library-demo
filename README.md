BUILD: `docker compose up --build`
<br/>
MIGRATIONS: `docker compose exec php php bin/console doctrine:migrations:migrate`
<br/>
API CHECK: `curl http://localhost:8080/api/book`
