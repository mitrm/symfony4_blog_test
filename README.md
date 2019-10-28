## Тестовый блог на Symfony 4

`php7.3, postgresql 11, nginx`

##### ЗАВИСИМОСТИ

* [Docker](https://docs.docker.com/release-notes/docker-ce/) >= v17.12.0
* [Docker Compose](https://docs.docker.com/release-notes/docker-compose/) >= v1.19.0

#### УСТАНОВКА

Клонируем проект
```
git clone https://github.com/mitrm/symfony4_blog_test.git ./symfony4_blog_test
cd symfony4_blog_test
```
Копируем файл с настройками
```
cp .env-dist .env
```
Сборка и запуск контейнеров
```
docker-compose up -d
```
Устрановка зависимостей Composer
```
docker exec -t $(docker-compose ps -q php) composer install
```
Применение миграций
```
docker exec -t $(docker-compose ps -q php) php bin/console doctrine:migrations:migrate -n
```
Сайт работает по адресу
```
http://localhost:8035
```