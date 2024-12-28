# PersonalChat Project with Payment System

PersonalChat is a Laravel-based project that integrates a chat functionality with a payment system, offering features like message throttling, subscription redirects, and user prioritization. This project also leverages Redis for caching and WebSockets for real-time updates.

---

## Features

### Chat Functionality
- **Private Chat**: Allows users to engage in private conversations.
- **Message Throttling**: Limits users to 50 messages per day.
- **Subscription Redirects**: Prompts users to buy subscriptions after reaching chat limits.
- **Real-Time Updates**: Uses WebSockets for live message updates.

### Payment Functionality
- **Daily Payment Throttling**: Restricts users to a maximum of 3 payments daily.
- **Subscription Plan Integration**: Implements subscription functionality with invoicing.
- **Payment Limits**: Middleware to enforce payment limits.

### Additional Features
- **User Prioritization**: Prioritizes users based on activity.
- **Online User Tracking**: Tracks and displays online users dynamically.
- **Redis Caching**: Enhances performance by migrating queues from the database to Redis.
- **Full Test Coverage**: Comprehensive test cases for core components.

---

## Installation

1. Clone the repository:
bash
git clone (https://github.com/Hamed042gh/PersonalChatWithPayment.git)
2. Navigate to the project directory:
bash
cd PersonalChat
3. Install dependencies:
bash
composer install
npm install
npm run dev
4. Configure environment variables:
- Copy `.env.example` to `.env`:
bash
cp .env.example .env
- Set up your database, Redis, and WebSocket configurations in `.env`.

5. Run migrations and seed the database:
bash
php artisan migrate --seed

6. Start the development server:
bash
php artisan serve

---

## Usage

- Start chatting and experience the UI for private conversations.
- Test payment processing with sample subscriptions.
- Monitor real-time updates and online user tracking.

---

## Upcoming Features
- Advanced subscription analytics.
- Customizable message and payment limits.
- Multi-language support.

---

## Contributing

Contributions are welcome! Follow the standard GitHub fork-branch-PR workflow:
1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Submit a pull request with a detailed description.

---

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.

---

## Contact

For any queries or feedback, feel free to reach out to:
- **GitHub**: [Hamed042gh](https://github.com/Hamed042gh)
