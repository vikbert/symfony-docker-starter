<div align="center">
    <h1 style="font-weight: bolder; margin-top: 0px" class="opacity-75">My  Docker Starter</h1>
    <h3 class="opacity-50">dockerized Symfony5, PHP 8, PostgreSQL 13</h3>
</div>

<div align="center">
  <p>
    <a href="#">
      <img src="https://img.shields.io/badge/PRs-Welcome-brightgreen.svg?style=flat-square" alt="PRs Welcome">
    </a>
    <a href="#">
      <img src="https://img.shields.io/badge/License-MIT-brightgreen.svg?style=flat-square" alt="MIT License">
    </a>
  </p>
</div>

---


| service | version |
| -- | -- |
| symfony | 5 |
| php | 8.0.1 |
| PostgreSQL | 13.0.1 |


## Starting started

```
git clone https://github.com/vikbert/symfony-docker-starter.git
cd symfony-docker-starter

docker-compose up -d
symfony new app
cp .env.local app/
```

Command **symfony** can be installed with this command
```bash
curl -sS https://get.symfony.com/cli/installer | bash
```

## Open App
```bash
http://localhost 
```


## licence

MIT [@vikbert](https://vikbert.github.io/)
