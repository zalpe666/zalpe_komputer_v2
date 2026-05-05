# Project Overview
This is a Laravel-based e-commerce application using Blade templating (no React).

The system supports multi-role:
- master
- admin
- cashier
- customer

Main business: Zalpe Komputer (online store)

---

# Tech Stack

## Backend & Frontend
- Laravel (MVC)
- Blade (templating engine)
- Bootstrap (UI)
- jQuery (optional for interactivity)

## Payment
- Midtrans (Snap, QRIS, VA, E-Wallet)

---

# Architecture

- MVC pattern (Model, View, Controller)
- Blade used for all frontend rendering
- No SPA, no React, no API-based frontend

---

# Authentication & Role

- Laravel Auth
- Middleware:
  - auth
  - role: master, admin, cashier, customer

---

# Route Structure

## Admin (/dashboard)
- Manage categories
- Manage brands
- Manage products (CRUD)
- Manage transactions
- Update transaction status

## Customer (/home)

### Product
- View product list
- View product detail by slug

### Cart
- Add to cart
- Update cart
- Remove cart

### Checkout
- Checkout from cart
- Checkout now (direct buy)
- Shipping cost via district_id

### Address
- CRUD address
- Dynamic dropdown:
  - province → city → district

### Courier
- Get courier by district

### Transaction
- View transactions
- View detail
- Payment page
- Complete / cancel

### Rating
- Give rating after transaction complete

### Profile
- Edit profile
- Delete account

### Notification
- Mark all as read

---

# Business Logic

## Cart
- Stored per user
- Session or database-based

## Checkout
- Calculate:
  subtotal - discount + shipping = total
- Shipping based on district

## Payment
- Midtrans integration
- Support:
  - QRIS
  - Virtual Account
  - E-Wallet
  - Snap

## Transaction Flow
- pending → paid → shipped → completed
- can cancel before paid

## Stock
- Cannot be edited manually
- Reduced automatically after checkout

## Rating
- Only allowed if transaction completed

---

# Blade Rules

- Use Blade syntax:
  - @extends
  - @section
  - @foreach
  - @if
- Avoid inline PHP if possible
- Separate layout:
  - layouts.app
  - layouts.customer-layout

---

# UI Rules

- Use Bootstrap (className standard)
- No React / SPA
- Use server-rendered pages

---

# Form Handling

- Use POST, PUT, DELETE (method spoofing)
- Always include:
  @csrf
- Validate on backend (Laravel validation)

---

# Security

- Protect all routes with middleware
- Validate all input
- Prevent role access abuse

---

# Payment Integration Notes

- Midtrans handled via controller
- Payment page shows:
  - VA number / QRIS / redirect
- Must support payment status checking

---

# Important Notes

- Referral system (last click, 7 days)
- Wallet system:
  - income
  - expense
- Transaction affects wallet

---

# AI Instructions

When generating code:
- ALWAYS use Blade (not React)
- Follow Laravel route/controller structure
- Use existing controllers (CustomerCartController, etc.)
- Do NOT introduce frontend frameworks
- Keep logic in controller, not in Blade
