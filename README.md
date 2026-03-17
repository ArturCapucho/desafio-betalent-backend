# Desafio Técnico - API de Pagamentos

Esta é uma API desenvolvida em **Laravel 11** para processar vendas com um sistema de **failover** (tentativa automática) entre gateways de pagamento.

## 🚀 Tecnologias
- PHP 8.2+
- Laravel 11
- MySQL

## 🛠️ Como rodar o projeto
1. Clone o repositório.
2. Rode `composer install`.
3. Configure seu `.env` com as credenciais do banco.
4. Rode `php artisan migrate:fresh --seed`.
5. Rode `php artisan serve`.

## 📌 Endpoint Principal
- **POST** `/api/purchase`
- **Payload:** `{"amount": 100, "card_number": "1234..."}`