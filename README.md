# RoadGuard AI - AI-Powered Roadside Assistance Platform

A complete production-ready web application for roadside assistance using Laravel 12, MySQL, Tailwind CSS, and AI-powered diagnostics.

## 🚀 Features

### Customer Features
- User Registration & Authentication
- Vehicle Management (Add/Edit/Delete)
- AI-Powered Vehicle Diagnostics
- Emergency SOS Requests with GPS Tracking
- Real-time Rescue Service Tracking
- Service Provider Rating & Reviews
- Subscription Plan Management

### Service Provider Features
- Service Provider Registration
- Emergency Request Management
- Live Status Updates
- Earnings Dashboard
- Customer Reviews & Ratings

### Admin Features
- User Management
- Service Provider Verification
- Analytics Dashboard
- Subscription Management
- Emergency Request Monitoring

### Technical Features
- Role-based Authentication (Customer, Provider, Admin)
- AI Diagnosis using Google Gemini API
- Google Maps Integration for Nearby Services
- Stripe Payment Integration
- Real-time Notifications (Email & In-App)
- REST API with Laravel Sanctum
- WebSocket Support for Real-time Updates
- Responsive Design with Tailwind CSS
- Dark Mode Support

## 📋 Tech Stack

- **Backend**: Laravel 12
- **Database**: MySQL
- **Frontend**: Tailwind CSS, JavaScript
- **APIs**: Google Maps, Google Gemini, Stripe
- **Authentication**: Laravel Sanctum
- **Real-time**: Laravel Reverb/WebSockets
- **Payment**: Stripe
- **Email**: Laravel Mail

## 📁 Project Structure

```
RoadGuard/
├── app/
│   ├── Models/
│   ├── Controllers/
│   ├── Services/
│   └── Notifications/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── resources/
│   ├── views/
│   ├── js/
│   └── css/
├── routes/
│   ├── web.php
│   ├── api.php
│   └── channels.php
├── config/
├── public/
└── tests/
```

## 🗄️ Database Tables

1. **users** - User accounts with roles
2. **vehicles** - Customer vehicles
3. **providers** - Service providers (garages, tow trucks, mechanics)
4. **emergency_requests** - SOS emergency tickets
5. **diagnoses** - AI diagnosis history
6. **subscriptions** - User subscription plans
7. **payments** - Payment records
8. **notifications** - System notifications
9. **reviews** - Service provider reviews
10. **roles** - User roles

## 🔧 Installation

### Prerequisites
- PHP 8.3+
- Composer
- MySQL 8.0+
- Node.js & npm

### Setup Instructions

1. **Clone the repository**
```bash
git clone https://github.com/PasinduW-sketch/RoadGuard.git
cd RoadGuard
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node dependencies**
```bash
npm install
```

4. **Setup environment file**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure your .env file**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=roadguard
DB_USERNAME=root
DB_PASSWORD=

GOOGLE_MAPS_API_KEY=your_key
GEMINI_API_KEY=your_key
STRIPE_PUBLIC_KEY=your_key
STRIPE_SECRET_KEY=your_key

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

6. **Create database and run migrations**
```bash
php artisan migrate
php artisan db:seed
```

7. **Build assets**
```bash
npm run build
```

8. **Start the application**
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## 🔑 Default Test Accounts

### Admin
- Email: admin@roadguard.com
- Password: password123

### Customer
- Email: customer@roadguard.com
- Password: password123

### Service Provider
- Email: provider@roadguard.com
- Password: password123

## 📚 API Documentation

See [API.md](docs/API.md) for complete API documentation.

## 🎨 Pages & Routes

### Public Pages
- `/` - Home
- `/about` - About
- `/pricing` - Subscription Plans
- `/login` - Login
- `/register` - Register

### Customer Dashboard
- `/dashboard` - Main dashboard
- `/vehicles` - Vehicle management
- `/diagnoses` - Diagnosis history
- `/emergency-requests` - SOS history
- `/providers` - Browse providers
- `/subscriptions` - Subscription management
- `/profile` - User profile

### Service Provider Dashboard
- `/provider/dashboard` - Provider dashboard
- `/provider/requests` - Active requests
- `/provider/earnings` - Earnings summary
- `/provider/reviews` - Customer reviews

### Admin Dashboard
- `/admin/dashboard` - Admin dashboard
- `/admin/users` - User management
- `/admin/providers` - Provider management
- `/admin/requests` - Emergency requests
- `/admin/subscriptions` - Subscription management
- `/admin/analytics` - Analytics

## 🔐 Authentication & Authorization

User roles:
- **Customer** - Can request services, manage vehicles
- **Provider** - Can accept requests, manage services
- **Admin** - Full system access

## 🚨 Emergency SOS Workflow

1. Customer clicks SOS button
2. System captures GPS location
3. Customer describes issue
4. AI provides initial diagnosis
5. Emergency ticket created
6. Nearby providers notified
7. Provider accepts request
8. Real-time tracking begins
9. Provider arrives and completes service
10. Customer rates provider

## 💳 Subscription Plans

### Free Plan
- 2 emergency requests/month
- Basic AI diagnosis

### Premium Plan
- Unlimited requests
- Priority dispatch
- Advanced AI diagnosis
- Maintenance reminders

### Enterprise Plan
- Fleet management
- Multiple vehicles
- Analytics dashboard
- Dedicated support

## 📧 Notifications

System sends notifications for:
- New emergency request (to providers)
- Request accepted (to customer)
- Provider arrived (to customer)
- Service completed (to both)
- Subscription expiry reminder
- New reviews/ratings

## 🛠️ Deployment

See [DEPLOYMENT.md](docs/DEPLOYMENT.md) for detailed deployment instructions.

## 📄 License

This project is licensed under the MIT License.

## 👥 Contributors

- Pasindu W (PasinduW-sketch)

## 📞 Support

For support, email support@roadguard.com or create an issue on GitHub.

---

**RoadGuard AI** - Making Roads Safer, One Breakdown at a Time 🚗
