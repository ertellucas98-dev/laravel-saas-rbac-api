# 🚀 Laravel SaaS RBAC API

A professional multi-tenant SaaS REST API built with Laravel.

This project demonstrates enterprise backend architecture using role-based access control (RBAC), secure API authentication and multi-company data isolation.

---

## 📌 Overview

Laravel SaaS RBAC API is a backend system designed to simulate real-world enterprise applications such as CRM, ERP and SaaS platforms.

Each company operates independently while sharing the same infrastructure.

---

## ⚙️ Tech Stack

* PHP 8+
* Laravel
* Laravel Sanctum (API Authentication)
* Spatie Laravel Permission (RBAC)
* MySQL / PostgreSQL
* REST API Architecture

---

## 🏢 Multi-Tenant Architecture

Each user belongs to a company and can only access data related to their organization.

Entities:

* Company
* User
* Client
* Sale

Relationship structure:

Company
├── Users
├── Clients
└── Sales

---

## 🔐 Authentication & Authorization

Authentication:

* Token-based authentication using Laravel Sanctum

Authorization:

* Role Based Access Control (RBAC) using Spatie Permission

### Roles

* owner
* admin
* seller
* viewer

### Permissions

* clients.view
* clients.create
* clients.update
* sales.create
* sales.approve
* reports.view

---

## 📡 API Endpoints

### Auth

POST /api/register
POST /api/login
POST /api/logout

### Clients

GET /api/clients
POST /api/clients
PUT /api/clients/{id}
DELETE /api/clients/{id}

### Sales

GET /api/sales
POST /api/sales
POST /api/sales/{id}/approve

---

## 🧱 Architecture

The project follows a clean and scalable architecture:

Controller → Service → Repository → Model

Responsibilities:

* Controllers: request handling
* Services: business logic
* Repositories: database access
* Policies: tenant security

---

## 🛡 Multi-Tenancy Protection

Policies ensure users cannot access resources from other companies.

Example rule:
Users may only access Clients and Sales belonging to their company.

---

## ⚡ Background Jobs

Queue system processes asynchronous tasks such as:

* Sale approval processing
* Action logging
* Notifications

---

## 🚀 Installation

Clone repository:

```bash
git clone https://github.com/your-username/laravel-saas-rbac-api.git
```

Install dependencies:

```bash
composer install
```

Configure environment:

```bash
cp .env.example .env
php artisan key:generate
```

Run migrations:

```bash
php artisan migrate --seed
```

Start server:

```bash
php artisan serve
```

---

## 🧪 Running Tests

```bash
php artisan test
```

---

## 📂 Project Structure

```
app/
 ├── Http/Controllers/API
 ├── Models
 ├── Services
 ├── Repositories
 ├── Policies
 ├── Jobs
```

---

## 🎯 Purpose

This project was created for study and portfolio purposes, demonstrating backend best practices used in enterprise Laravel applications.

---

## 👨‍💻 Author

Backend Developer focused on PHP and Laravel ecosystem.

---

## 📄 License

MIT License
