# Laravel ERP System

A comprehensive Enterprise Resource Planning (ERP) system built with Laravel 11 and Filament 3, inspired by Odoo. This system provides a complete suite of business management tools including CRM, Sales, Purchase, Inventory, Manufacturing, HR, Projects, and Accounting modules.

## Features

### Core Modules

#### 1. CRM (Customer Relationship Management)
- **Leads Management**: Track and manage sales leads with priority, status, and revenue tracking
- **Opportunities**: Convert leads to opportunities with probability and stage tracking
- **Contacts**: Manage customer contacts with detailed information
- **Accounts**: Organize customer accounts with hierarchical structure
- **Activities**: Schedule and track calls, meetings, and tasks

#### 2. Sales Management
- **Customers**: Comprehensive customer database with billing/shipping addresses
- **Quotes**: Create and send quotations to customers
- **Sales Orders**: Convert quotes to sales orders with delivery tracking
- **Invoices**: Generate invoices from sales orders
- **Payments**: Track customer payments and outstanding balances

#### 3. Purchase Management
- **Vendors**: Maintain vendor database with payment terms
- **Purchase Orders**: Create and manage purchase orders
- **Bills**: Manage vendor bills and accounts payable
- **Vendor Payments**: Track payments to vendors

#### 4. Inventory Management
- **Products**: Manage product catalog with categories, SKUs, and barcodes
- **Product Categories**: Hierarchical product categorization
- **Warehouses**: Multi-warehouse support with location tracking
- **Stock Levels**: Real-time inventory tracking per warehouse
- **Stock Movements**: Track all inventory transactions
- **Stock Transfers**: Transfer inventory between warehouses

#### 5. Manufacturing
- **Bill of Materials (BOM)**: Define product components and assembly
- **Work Orders**: Plan and track production orders
- **MRP**: Material requirements planning

#### 6. Human Resources
- **Employees**: Complete employee database
- **Departments**: Hierarchical department structure
- **Positions**: Job position definitions with salary ranges
- **Attendance**: Track employee check-in/check-out times
- **Leave Management**: Handle leave requests and approvals
- **Payroll**: Process employee payroll with deductions

#### 7. Project Management
- **Projects**: Create and manage projects with budgets and timelines
- **Tasks**: Organize tasks with assignments and progress tracking
- **Timesheets**: Track time spent on projects and tasks
- **Project Members**: Assign team members with roles and rates

#### 8. Accounting
- **Chart of Accounts**: Define account structure
- **Journals**: Manage different journal types
- **Journal Entries**: Record financial transactions
- **Fiscal Years**: Manage accounting periods

### Core Features
- **Multi-Company Support**: Manage multiple companies in single installation
- **Multi-Currency**: Support for multiple currencies with exchange rates
- **Role-Based Access Control**: Fine-grained permissions using Spatie Permission
- **Activity Logging**: Track all changes with Spatie Activity Log
- **Media Management**: Handle file uploads with Spatie Media Library
- **Audit Trail**: Complete history of all transactions
- **UUID Primary Keys**: Enhanced security and scalability

## Technology Stack

- **Framework**: Laravel 11
- **Admin Panel**: Filament 3
- **Database**: MySQL/PostgreSQL
- **PHP Version**: 8.2+
- **Key Packages**:
  - filament/filament (Admin panel)
  - spatie/laravel-permission (Roles & Permissions)
  - spatie/laravel-activitylog (Audit trails)
  - spatie/laravel-medialibrary (File management)
  - spatie/laravel-settings (Configuration)
  - spatie/laravel-sluggable (URL slugs)

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL 8.0+ or PostgreSQL 13+
- Node.js & NPM

### Steps

1. **Clone the repository**
```bash
git clone https://github.com/tejraj-dev/laravel-oodo-clone.git
cd laravel-oodo-clone
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install NPM dependencies**
```bash
npm install
```

4. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure database**
Edit `.env` file and set your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_erp
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. **Run migrations**
```bash
php artisan migrate
```

7. **Publish vendor assets**
```bash
php artisan vendor:publish --tag=filament-assets
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider"
```

8. **Create storage link**
```bash
php artisan storage:link
```

9. **Build assets**
```bash
npm run build
```

10. **Create admin user**
```bash
php artisan make:filament-user
```

11. **Start development server**
```bash
php artisan serve
```

Access the admin panel at: `http://localhost:8000/admin`

## Module Structure

The application follows a modular architecture with modules organized under `app/Modules/`:

```
app/Modules/
├── Core/               # Core functionality (Companies, Currencies)
├── CRM/               # Customer Relationship Management
├── Sales/             # Sales Management
├── Purchase/          # Purchase Management
├── Inventory/         # Inventory & Warehouse Management
├── Manufacturing/     # Manufacturing & MRP
├── HR/               # Human Resources
├── Projects/         # Project Management
└── Accounting/       # Financial Accounting
```

Each module contains:
- **Models**: Eloquent models with relationships
- **Filament/Resources**: Admin panel resources
- **Services**: Business logic
- **Traits**: Reusable functionality
- **Enums**: Enumeration values

## Database Schema

The system uses UUID primary keys for enhanced security and includes comprehensive foreign key relationships. Key tables include:

- **Core**: companies, currencies, users
- **CRM**: accounts, contacts, leads, opportunities, activities
- **Sales**: customers, quotes, sales_orders, invoices, payments
- **Purchase**: vendors, purchase_orders, bills, vendor_payments
- **Inventory**: products, warehouses, stock_levels, stock_movements, stock_transfers
- **Manufacturing**: bill_of_materials, bom_items, work_orders
- **HR**: employees, departments, positions, attendances, leave_requests, payrolls
- **Projects**: projects, tasks, timesheets, project_members
- **Accounting**: chart_of_accounts, journals, journal_entries

## Configuration

### Company Setup
1. Navigate to Settings → Companies
2. Create your first company
3. Set currency, timezone, and fiscal year settings

### User Management
1. Create users via Settings → Users
2. Assign roles and permissions
3. Link users to employees for HR features

### Initial Data
1. Set up Chart of Accounts for accounting
2. Create product categories and products
3. Define warehouses for inventory tracking
4. Set up departments and positions for HR

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
The project uses Laravel Pint for code formatting:
```bash
./vendor/bin/pint
```

### Generating Resources
To create new Filament resources:
```bash
php artisan make:filament-resource ModelName --generate
```

## Security

- UUID-based primary keys prevent enumeration attacks
- Role-based access control for all features
- Activity logging for audit trails
- Soft deletes for data recovery
- Input validation on all forms
- CSRF protection
- SQL injection prevention through Eloquent ORM

## Performance

- Database indexing on foreign keys and commonly queried fields
- Eager loading to prevent N+1 queries
- Caching for frequently accessed data
- Queue support for heavy operations
- Optimized queries with proper relationships

## Customization

### Adding New Modules
1. Create module directory under `app/Modules/`
2. Define models with appropriate traits
3. Create Filament resources for admin interface
4. Add migrations for database tables
5. Update navigation in Filament panel provider

### Extending Functionality
- Add custom actions to Filament resources
- Create service classes for complex business logic
- Implement event listeners for automated processes
- Add custom widgets to dashboards

## Roadmap

- [ ] Point of Sale (POS) Module
- [ ] E-commerce Integration
- [ ] Advanced Reporting & Analytics
- [ ] API Development (REST/GraphQL)
- [ ] Mobile Application
- [ ] Email Integration
- [ ] SMS Notifications
- [ ] Document Generation (PDF)
- [ ] Workflow Automation
- [ ] Multi-language Support
- [ ] Advanced Dashboards
- [ ] Data Import/Export Tools

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the MIT license.

## Support

For issues, questions, or suggestions:
- Create an issue on GitHub
- Contact: [your-email@example.com]

## Credits

- Built with [Laravel](https://laravel.com)
- Admin panel by [Filament](https://filamentphp.com)
- Inspired by [Odoo](https://www.odoo.com)

## Acknowledgments

- Laravel community for the amazing framework
- Filament team for the beautiful admin panel
- Spatie for the excellent Laravel packages
- All contributors and supporters

---

**Version**: 1.0.0
**Status**: Active Development
**Last Updated**: November 2025
