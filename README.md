# GetClose - E-Commerce Marketplace

<div align="center">
  <img src="public/images/logo.jpg" alt="GetClose Logo" width="200" height="200" style="border-radius: 20px;">
  <p><strong>A modern multi-vendor e-commerce marketplace built with Laravel</strong></p>
</div>

<p align="center">
<a href="#features"><img src="https://img.shields.io/badge/version-1.0.0-blue.svg" alt="Version"></a>
<a href="#tech-stack"><img src="https://img.shields.io/badge/laravel-11.x-FF2D20.svg" alt="Laravel"></a>
<a href="#tech-stack"><img src="https://img.shields.io/badge/php-8.2+-777BB4.svg" alt="PHP"></a>
<a href="#license"><img src="https://img.shields.io/badge/license-MIT-green.svg" alt="License"></a>
</p>

---

## 📋 Table of Contents

- [About](#about)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [System Architecture](#system-architecture)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [Deployment](#deployment)
- [Contributing](#contributing)
- [License](#license)

---

## 📖 About

**GetClose** is a full-featured multi-vendor e-commerce marketplace that connects local vendors with customers. The platform enables vendors to create shops, manage products, and process orders while providing customers with a seamless shopping experience including cart management, secure payments via Stripe, and order tracking.

### Key Highlights

- 🏪 **Multi-Vendor System**: Vendors can create and manage their own shops
- 🛒 **Shopping Cart**: Full cart management with real-time updates
- 💳 **Secure Payments**: Stripe integration with payment intent API
- 💰 **Vendor Financials**: Comprehensive payout and ledger system
- 🔔 **Notifications**: Real-time in-app and email notifications
- 👥 **Role-Based Access**: Three distinct roles (Client, Vendor, Admin)
- 📱 **Responsive Design**: Mobile-first UI with Tailwind CSS
- 💵 **Currency**: Moroccan Dirham (MAD)

---

## ✨ Features

### For Customers (Clients)
- ✅ Browse and search products
- ✅ View product details with images and reviews
- ✅ Shopping cart management
- ✅ Secure checkout with Stripe or Cash on Delivery
- ✅ Order tracking and history
- ✅ Product reviews and ratings
- ✅ Dispute creation for order issues
- ✅ Real-time notifications

### For Vendors
- ✅ Create and manage shops (with admin approval)
- ✅ Product CRUD operations with image upload
- ✅ Category management
- ✅ Order management and status updates
- ✅ Financial dashboard with earnings tracking
- ✅ Vendor ledger for transaction history
- ✅ Payout request system
- ✅ Eligibility checking for payouts
- ✅ Sales analytics and statistics

### For Administrators
- ✅ Admin dashboard with platform statistics
- ✅ Shop approval/rejection system
- ✅ User management
- ✅ Order management (all vendors)
- ✅ Dispute resolution system
- ✅ Shipping method management
- ✅ Product oversight
- ✅ Platform monitoring

---

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 11.x
- **Database**: PostgreSQL
- **Authentication**: Laravel Breeze
- **Payment Gateway**: Stripe API
- **Email**: Laravel Mail
- **Queue**: Laravel Queue (for async jobs)

### Frontend
- **CSS Framework**: Tailwind CSS
- **Icons**: Phosphor Icons
- **JavaScript**: Vanilla JS + Alpine.js
- **Build Tool**: Vite

### Additional Tools
- **Image Storage**: Laravel Storage (local/public)
- **Pagination**: Laravel Pagination
- **Validation**: Laravel Form Request Validation
- **Testing**: PHPUnit / Pest

---

## 🏗️ System Architecture

### User Roles & Permissions

```
┌─────────────────────────────────────────────┐
│              GetClose Platform               │
├─────────────────────────────────────────────┤
│                                             │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  │
│  │  Client  │  │  Vendor  │  │  Admin   │  │
│  └──────────┘  └──────────┘  └──────────┘  │
│       │             │             │         │
│       ├─────────────┼─────────────┤         │
│       │             │             │         │
│  Browse Products    │      Manage Platform  │
│  Add to Cart   Manage Shops    Approve Shops│
│  Checkout      Manage Products  Manage Users│
│  Track Orders  View Financials  Resolve Issues│
│  Write Reviews Update Orders   View All Data│
│                                             │
└─────────────────────────────────────────────┘
```

### Database Schema

Key entities and relationships:
- **Users** → Shops (1:1 for vendors)
- **Shops** → Products (1:many)
- **Products** → Categories (many:1)
- **Users** → Carts (1:1 for clients)
- **Carts** → CartItems (1:many)
- **Users** → Orders (1:many for clients)
- **Orders** → OrderItems (1:many)
- **Orders** → Payments (1:1)
- **Shops** → VendorBalances (1:1)
- **VendorBalances** → VendorLedgers (1:many)
- **VendorBalances** → Payouts (1:many)
- **Products** → Reviews (1:many)
- **Orders** → Disputes (1:0..1)
- **Users** → Notifications (1:many)

### Payment Flow

```
Client Checkout
    ↓
Create Order (pending)
    ↓
Stripe Payment Intent
    ↓
Payment Success
    ↓
Update Order (paid)
    ↓
Create Payment Record
    ↓
Decrement Product Stock
    ↓
Notify Vendor & Admin
    ↓
Update Vendor Balance
```

---

## 📦 Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- PostgreSQL 14+
- Node.js 18+ & NPM
- Stripe Account (for payments)

### Step-by-Step Setup

1. **Clone the repository**
```bash
git clone <repository-url>
cd GetClose
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node dependencies**
```bash
npm install
```

4. **Create environment file**
```bash
cp .env.example .env
```

5. **Generate application key**
```bash
php artisan key:generate
```

6. **Configure database**

Edit `.env` file with your PostgreSQL credentials:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=getclose
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. **Configure Stripe**

Add your Stripe keys to `.env`:
```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
```

8. **Run migrations and seeders**
```bash
php artisan migrate
php artisan db:seed
```

9. **Create storage link**
```bash
php artisan storage:link
```

10. **Build assets**
```bash
npm run build
```

11. **Start development server**
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

---

## ⚙️ Configuration

### Environment Variables

Key configuration options in `.env`:

```env
# Application
APP_NAME=GetClose
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=getclose
DB_USERNAME=postgres
DB_PASSWORD=secret

# Mail (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@getclose.com
MAIL_FROM_NAME="GetClose"

# Stripe
STRIPE_KEY=pk_test_your_key
STRIPE_SECRET=sk_test_your_secret

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

### Payment Configuration

The platform supports two payment methods:
1. **Stripe** (Credit/Debit cards)
2. **Cash on Delivery** (COD)

To configure Stripe:
1. Create a Stripe account at [stripe.com](https://stripe.com)
2. Get your API keys from the Stripe Dashboard
3. Add them to your `.env` file
4. For production, use live keys (not test keys)

---

## 🚀 Usage

### Default User Accounts

After running the seeder, you can use these test accounts:

```
Admin:
Email: admin@getclose.com
Password: password

Vendor:
Email: vendor@getclose.com
Password: password

Client:
Email: client@getclose.com
Password: password
```

### Accessing Different Dashboards

- **Admin Panel**: `/admin/dashboard`
- **Vendor Dashboard**: `/vendor/dashboard`
- **Client Orders**: `/client/orders`

### Creating a New Vendor

1. Register a new account
2. Navigate to vendor registration
3. Fill in shop details
4. Wait for admin approval
5. Start adding products

### Processing an Order

1. Browse products
2. Add items to cart
3. Proceed to checkout
4. Enter shipping details
5. Select payment method
6. Complete payment (Stripe or COD)
7. Track order status

---

## 📁 Project Structure

```
GetClose/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin controllers
│   │   │   ├── Client/         # Client controllers
│   │   │   ├── Vendor/         # Vendor controllers
│   │   │   └── Auth/           # Authentication controllers
│   │   ├── Middleware/         # Custom middleware (RoleMiddleware)
│   │   └── Requests/           # Form request validation
│   ├── Models/                 # Eloquent models
│   ├── Services/               # Business logic services
│   │   ├── NotificationService.php
│   │   └── VendorPayoutService.php
│   └── Mail/                   # Email templates
├── database/
│   ├── migrations/             # Database migrations
│   ├── seeders/                # Database seeders
│   └── factories/              # Model factories
├── resources/
│   ├── views/
│   │   ├── pages/
│   │   │   ├── admin/          # Admin views
│   │   │   ├── client/         # Client views
│   │   │   ├── vendor/         # Vendor views
│   │   │   └── products/       # Product views
│   │   ├── layouts/            # Blade layouts
│   │   ├── components/         # Blade components
│   │   └── emails/             # Email templates
│   └── js/                     # JavaScript files
├── routes/
│   ├── web.php                 # Web routes
│   ├── auth.php                # Authentication routes
│   └── console.php             # Console commands
├── public/                     # Public assets
├── storage/                    # Storage (logs, uploads)
├── tests/                      # Test files
├── docs/                       # Documentation
│   ├── use-case-diagram.md
│   ├── use-case-diagram.drawio
│   ├── class-diagram.md
│   ├── class-diagram.drawio
│   └── jira-project-plan.csv
└── config/                     # Laravel configuration
```

---

## 📚 Documentation

Detailed documentation is available in the `docs/` directory:

- **[Use Case Diagram](docs/use-case-diagram.md)** - System use cases and actor relationships
- **[Class Diagram](docs/class-diagram.md)** - Database models and relationships
- **[Draw.io Files](docs/*.drawio)** - Editable diagram files for Draw.io
- **[Jira Project Plan](docs/jira-project-plan.csv)** - Sprint planning and task tracking

### Generating API Documentation

To generate API documentation:
```bash
php artisan route:list --json > docs/routes.json
```

---

## 🧪 Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/AuthTest.php
```

### Test Categories

- **Unit Tests**: Model logic, services, helpers
- **Feature Tests**: HTTP requests, authentication, CRUD operations
- **Integration Tests**: Payment flow, order processing

---

## 🌐 Deployment

### Production Checklist

1. **Environment Setup**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Optimize**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan optimize
   ```

3. **Build Assets**
   ```bash
   npm run build
   ```

4. **Migrate Database**
   ```bash
   php artisan migrate --force
   ```

5. **Set Permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

### Server Requirements

- PHP 8.2+
- PostgreSQL 14+
- Nginx or Apache
- SSL Certificate (for Stripe)
- Queue worker (for async jobs)

### Recommended Hosting

- **VPS**: DigitalOcean, Linode, Vultr
- **Platform-as-a-Service**: Laravel Forge, Heroku
- **Shared Hosting**: Not recommended for production

---

## 🤝 Contributing

Thank you for considering contributing to GetClose!

### How to Contribute

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Code Standards

- Follow PSR-12 coding standards
- Write tests for new features
- Document your code with comments
- Use meaningful commit messages

---

## 🔒 Security

If you discover a security vulnerability within GetClose, please send an e-mail to the development team. All security vulnerabilities will be promptly addressed.

### Security Best Practices

- Use environment variables for sensitive data
- Enable HTTPS in production
- Keep Laravel and dependencies updated
- Use strong password policies
- Implement rate limiting

---

## 📄 License

The GetClose platform is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The web framework used
- [Stripe](https://stripe.com) - Payment processing
- [Tailwind CSS](https://tailwindcss.com) - CSS framework
- [Phosphor Icons](https://phosphoricons.com) - Icon library
- [PostgreSQL](https://postgresql.org) - Database system

---

## 📞 Support

For support, please open an issue in the repository or contact the development team.

---

<div align="center">
  <p>Made with ❤️ for local vendors and customers</p>
  <p><strong>GetClose</strong> - Bringing vendors closer to customers</p>
</div>
