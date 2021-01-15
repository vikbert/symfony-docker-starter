
## Composer Install

```bash
docker-compose exec php composer req doctrine
docker-compose exec php composer req security
docker-compose exec php composer req twig
docker-compose exec php composer req maker
docker-compose exec php composer req http-client
docker-compose exec php composer req ramsey/uuid
docker-compose exec php composer req knpuniversity/oauth2-client-bundle

# dev packages
docker-compose exec php composer req orm-fixtures --dev
```

## User Entity
```bash

make:user

make:entity User
make:fixtures 
```
