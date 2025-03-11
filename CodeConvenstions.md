# **Code Conventions - MakersMarkt**

## **1. General Guidelines**
- Alle **backend code** (variabelen, database, logica) moet in **Engels** worden geschreven.
- Code moet **leesbaar**, **consistent** en goed gedocumenteerd zijn.
- Volg de **PSR-12** coding standards voor PHP, **maar gebruik tabs in plaats van vier spaties**.
- Gebruik **Git & GitHub** voor versiebeheer:
  - Werk in **feature/**, **bugfix/** en **hotfix/** branches.
  - Gebruik **pull requests** en zorg voor code reviews vóór het mergen.
- Schrijf **informatieve commit messages**.

---

## **2. Folder Structure (Laravel Best Practices) (Most Used Folders)**
- app/Models/
- app/Http/Controllers/ 
- routes/web.php
- database/migrations/ 
- database/seeders/
- resources/views/

---

## **3. Naming Conventions**
| Type               | Convention                      | Example                  |
|--------------------|--------------------------------|--------------------------|
| Variables         | camelCase                       | `$productName`           |
| Classes          | PascalCase                      | `ProductController`      |
| Methods          | camelCase                       | `getUserProducts()`      |
| Tables           | snake_case (plural)            | `users`, `orders`        |
| Columns          | snake_case (singular)          | `user_id`, `order_date`  |
| Routes (RESTful) | kebab-case                      | `/user-products`         |

- **Alle variabelen en database kolommen moeten in het Engels zijn**.
- **Alle backend logica moet in het Engels zijn**.

---

## **4. Security**
### **Authentication & Authorization**
- **Role-Based Access Control (RBAC)** voor gebruikers (`maker`, `buyer`, `moderator`).
- Laravel's ingebouwde authenticatiesysteem wordt gebruikt voor veilige inlogverwerking.
- **Inlogpogingen loggen**, accounts worden tijdelijk geblokkeerd na meerdere mislukte pogingen.

### **Sensitive Data Handling**
- **Wachtwoorden hashen met bcrypt** (`Hash::make()`).
- Nooit **API keys** of **wachtwoorden hardcoden**.
- **Gevoelige gegevens alleen verzenden via HTTPS**.

### **Input Validation**
- **Altijd** Laravel Request Validation gebruiken.
- **Geen raw SQL queries uitvoeren**, altijd **Eloquent ORM** gebruiken.

### **Attack Prevention**
- **CSRF Protection**: Gebruik `@csrf` in Blade forms.
- **XSS Protection**: Escape output met `{{ $variable }}` in Blade.
- **SQL Injection Protection**: Gebruik **prepared statements** en **Eloquent ORM**.

---

## **5. Database Conventions**
- Gebruik **migrations** voor database wijzigingen.
- **Definieer relaties correct**:
  - `hasOne()`, `hasMany()`, `belongsTo()`, `belongsToMany()`
- **Foreign Keys Naming**:
  - Gebruik `user_id`, niet `userid` of `userID`.

---

## **6. Logging & Debugging**
- **Geen `var_dump()` of `dd()` in productie**.
- Gebruik **Laravel Logging**: `Log::info('message');`.
- **Error logs** worden opgeslagen in `storage/logs/laravel.log`.

---

## **7. API & Frontend Guidelines**
- API responses moeten **altijd JSON** retourneren.
- Goede error handling voorbeeld:
  ```php
  return response()->json(['error' => 'Not Found'], 404);
