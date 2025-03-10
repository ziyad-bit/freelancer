# Freelancer Project

## Overview
Freelancer Project is a web application built with **Laravel**, **JavaScript**, and **CSS**. It is designed to connect freelancers with clients, allowing users to post jobs, hire talent, and manage freelance work efficiently.

## Features
- **User Authentication**: Secure login and registration system.
- **Freelancer & Client Profiles**: Separate dashboards for freelancers and clients.
- **Job Posting**: Clients can create job listings with project details.
- **Bidding System**: Freelancers can bid on projects with their proposals.
- **Messaging System**: Real-time chat between freelancers and clients.
- **Payment Integration**: Secure transactions using hyperPay gateway.
- **Admin Panel**: Manage users, projects, and debates.
- **transactions showing**: show all transactions for clients and freelancer.

## Tech Stack
- **Backend**: Laravel (PHP Framework)
- **Frontend**: JavaScript, CSS, Blade Templates
- **Database**: MySQL
- **cache**: redis
- **websocket**: laravel reverb
- **Payment Gateway**: hyperPay gateway
- **Deployment**: Nginx,DigitalOcean

## Installation
### Prerequisites
Ensure you have the following installed:
- PHP `>= 8.1`
- Composer
- Node.js & npm
- MySQL
- Redis

### Steps to Install
1. **Clone the Repository**
   ```sh
   git clone https://github.com/ziyad-bid/freelancer.git
   ```
2. **Install Dependencies**
   ```sh
   composer install
   npm install
   ```
3. **Set Up Environment**
   Copy `.env.example` to `.env` and update database credentials:
   ```sh
   php artisan key:generate
   ```
4. **Run Migrations & Seeders**
   ```sh
   php artisan migrate --seed
   ```
5. **Build Frontend Assets**
   ```sh
   npm run dev  # For development
   npm run prod # For production
   ```
6. **Start the Application**
   ```sh
   php artisan serve
   ```
   The application should be available at `http://127.0.0.1:8000`

## Deployment
For production, consider deploying with:
- **DigitalOcean / Nginx**

## Contribution
Contributions are welcome! Follow these steps:
1. Fork the repository
2. Create a feature branch (`git checkout -b feature-name`)
3. Commit changes (`git commit -m 'Add new feature'`)
4. Push to the branch (`git push origin feature-name`)
5. Open a Pull Request

## License
This project is open-source and available under the [MIT License](LICENSE).

## Contact
For any inquiries, reach out to **[ziyad199523@gmail.com]** or open an issue on GitHub.

---
Made with ❤️ using Laravel, JavaScript & CSS.

