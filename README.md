# Atividade dionatan

## consumo-rickandmorty
Consome a api do Rick and Morty (https://rickandmortyapi.com) e mostra os personagem junto com paginação

```
cd consumo-rickandmorty
php -S localhost:8081
```

## api-series (com laravel)
API REST de series

```
cd api-series
php artisan migrate
php artisan serve
```

Rotas:
GET /api/series
POST /api/series
GET /api/series/{id}
PUT /api/series/{id}
DELETE /api/series/{id}

o json dos teste com o postman ta aq `api-series/postman_collection.json `

## desafio
Projeto em PHP puro que consome a api-series (lista, cadastra, edita e exclui). A API precisa estar rodando na porta 8000.

```
cd consumidor-series
php -S localhost:8082
```
