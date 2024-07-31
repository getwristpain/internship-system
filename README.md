# Simapekael Development Version (smpkl-dev)

## Description

A Laravel-based web application for managing internship information systems (PKL) at a Vocational High School. This project includes features for user management, assessment, reporting, and more.

## Features

- **User Management**: Roles include admin, department staff, student, teacher, supervisor.
- **Assessment**: Evaluation system with aspects and indicators.
- **Reporting**: Internship report management with file uploads and verification.
- **Livewire Integration**: Fully dynamic interface using Livewire and Volt.
- **Middleware**: Custom middleware registration in `bootstrap/app.php`.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/reasnovynt/smpkl-dev.git
   ```
2. Navigate to the project directory:
   ```bash
   cd smpkl-dev
   ```
3. Install dependencies:
   ```bash
   composer install
   npm install
   ```
4. Configure the environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. Migrate the database:
   ```bash
   php artisan migrate
   ```
6. Start the development server:
   ```bash
   php artisan serve
   ```

## Usage

- Access the application at `http://localhost:8000`.
- Admin can manage users, roles, and permissions.
- Students can submit and manage their internship reports.
- Supervisors can evaluate students based on predefined aspects and indicators.

## Contributing

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes.
4. Commit your changes (`git commit -m 'Add new feature'`).
5. Push to the branch (`git push origin feature-branch`).
6. Open a pull request.

## Credits

This project was developed by [reasnovynt](https://github.com/reasnovynt). Special thanks to the contributors and the open-source community for their support and contributions.

## License

This project is open-source and available under the [MIT License](LICENSE).