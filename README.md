# 📦 Inventory Reservation System

A web-based inventory reservation system for stores that allows customers to reserve products without integrated online payments.
Customers can select products and submit a reservation request, which is later confirmed by the store.

This project simulates a small e-commerce workflow where products are reserved instead of purchased directly.

---

# 🌐 Live Demo

The project is deployed and available online:

Demo: https://level-up-store-games.gamer.gd

This demo store is a fictional example used to demonstrate how the system works.

Visitors can browse the store, explore the product catalog and create reservation requests.
The admin panel is not accessible from the public interface and requires direct access via URL and valid credentials.

---

# ✨ Features

* Product catalog
* Product detail pages
* Shopping cart system
* Product reservation workflow
* Order confirmation system
* Admin panel for managing reservations and products
* Automatic cleanup of expired reservations
* Contact page
* Responsive UI

---

# 🛠 Technologies

* **PHP**
* **MySQL / MariaDB**
* **HTML5**
* **CSS3**
* **Tailwind CSS**
* **JavaScript**

---

# 🧠 How the system works

1. Customers browse the product catalog.
2. Products can be added to a cart.
3. Instead of paying online, the user submits a **reservation request**.
4. The store reviews the request through the **admin panel**.
5. The store can approve or cancel the reservation.

This model is useful for businesses that prefer **manual payment agreements** with customers.

---

# 🔧 Installation

1. Clone the repository

```bash
git clone https://github.com/yourusername/inventory-reservation-system-public.git
```

2. Move the project to your local server

Example with XAMPP:

```
htdocs/inventory-reservation-system-public
```

3. Import the database in **phpMyAdmin**

4. Configure the database connection

Copy the example file:

```
back/conexion.example.php
```

Rename it to:

```
conexion.php
```

Then edit it with your database credentials.

---

# 👨‍💻 Admin Panel

The system includes an admin panel to manage:

* product reservations
* orders
* inventory

Admin pages are located in:

```
/admin
```

---

## 🔐 Admin Panel Security

The system includes a protected admin panel.

Access requirements:

* Direct access via `/admin`
* Valid administrator credentials
* Passwords are stored encrypted in the database

Unauthorized users cannot access the admin area.

---

# 📂 Project Structure

```
admin/        → admin panel
assets/       → styles, images and scripts
back/         → backend logic and database connection
components/   → reusable UI components
uploads/      → uploaded images
```

---

# 🚀 Future Improvements

* user authentication system
* inventory analytics
* email notifications for reservations
* payment gateway integration

---

# 👩‍💻 Author

Developed by **Sofía Olariaga**
Software Developer

Projects developed as part of personal learning and portfolio.
