# Laravel ERP API Documentation

## Overview
This document provides comprehensive documentation for both REST and GraphQL APIs in the Laravel ERP system.

## Base URLs
- **REST API**: `http://your-domain.com/api/v1`
- **GraphQL API**: `http://your-domain.com/graphql`
- **GraphQL Playground**: `http://your-domain.com/graphql-playground` (development only)

## Authentication

### REST API Authentication
The API uses Laravel Sanctum for authentication. All protected endpoints require a Bearer token in the Authorization header.

#### Login
**POST** `/api/v1/login`

Request:
```json
{
    "email": "user@example.com",
    "password": "password",
    "device_name": "web-browser"
}
```

Response:
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": "uuid",
            "name": "John Doe",
            "email": "user@example.com",
            "company_id": "uuid"
        },
        "token": "1|laravel_sanctum_token",
        "token_type": "Bearer"
    }
}
```

#### Register
**POST** `/api/v1/register`

Request:
```json
{
    "name": "John Doe",
    "email": "user@example.com",
    "password": "password",
    "password_confirmation": "password",
    "company_id": "uuid",
    "device_name": "web-browser"
}
```

#### Logout
**POST** `/api/v1/logout`

Headers:
```
Authorization: Bearer {token}
```

#### Get Current User
**GET** `/api/v1/me`

Headers:
```
Authorization: Bearer {token}
```

### GraphQL Authentication

#### Login Mutation
```graphql
mutation {
  login(email: "user@example.com", password: "password") {
    access_token
    token_type
    user {
      id
      name
      email
    }
  }
}
```

#### For subsequent requests, include in HTTP headers:
```
Authorization: Bearer {access_token}
```

## REST API Endpoints

### Core Module

#### Companies
- **GET** `/api/v1/companies` - List all companies
- **GET** `/api/v1/companies/{id}` - Get company details
- **POST** `/api/v1/companies` - Create new company
- **PUT** `/api/v1/companies/{id}` - Update company
- **DELETE** `/api/v1/companies/{id}` - Delete company

#### Currencies
- **GET** `/api/v1/currencies` - List all currencies
- **GET** `/api/v1/currencies/{id}` - Get currency details
- **POST** `/api/v1/currencies` - Create new currency
- **PUT** `/api/v1/currencies/{id}` - Update currency
- **DELETE** `/api/v1/currencies/{id}` - Delete currency

### CRM Module

#### Leads
- **GET** `/api/v1/leads` - List all leads
- **GET** `/api/v1/leads/{id}` - Get lead details
- **POST** `/api/v1/leads` - Create new lead
- **PUT** `/api/v1/leads/{id}` - Update lead
- **DELETE** `/api/v1/leads/{id}` - Delete lead

Query Parameters (pagination & search):
- `per_page` - Items per page (default: 15)
- `page` - Page number
- `search` - Search term

Example: `/api/v1/leads?per_page=20&page=1&search=john`

#### Opportunities
- **GET** `/api/v1/opportunities`
- **GET** `/api/v1/opportunities/{id}`
- **POST** `/api/v1/opportunities`
- **PUT** `/api/v1/opportunities/{id}`
- **DELETE** `/api/v1/opportunities/{id}`

#### Contacts
- **GET** `/api/v1/contacts`
- **GET** `/api/v1/contacts/{id}`
- **POST** `/api/v1/contacts`
- **PUT** `/api/v1/contacts/{id}`
- **DELETE** `/api/v1/contacts/{id}`

#### Accounts
- **GET** `/api/v1/accounts`
- **GET** `/api/v1/accounts/{id}`
- **POST** `/api/v1/accounts`
- **PUT** `/api/v1/accounts/{id}`
- **DELETE** `/api/v1/accounts/{id}`

### Sales Module

#### Customers
- **GET** `/api/v1/customers`
- **GET** `/api/v1/customers/{id}`
- **POST** `/api/v1/customers`
- **PUT** `/api/v1/customers/{id}`
- **DELETE** `/api/v1/customers/{id}`

#### Quotes
- **GET** `/api/v1/quotes`
- **GET** `/api/v1/quotes/{id}`
- **POST** `/api/v1/quotes`
- **PUT** `/api/v1/quotes/{id}`
- **DELETE** `/api/v1/quotes/{id}`

#### Sales Orders
- **GET** `/api/v1/sales-orders`
- **GET** `/api/v1/sales-orders/{id}`
- **POST** `/api/v1/sales-orders`
- **PUT** `/api/v1/sales-orders/{id}`
- **DELETE** `/api/v1/sales-orders/{id}`

#### Invoices
- **GET** `/api/v1/invoices`
- **GET** `/api/v1/invoices/{id}`
- **POST** `/api/v1/invoices`
- **PUT** `/api/v1/invoices/{id}`
- **DELETE** `/api/v1/invoices/{id}`

### Purchase Module

#### Vendors
- **GET** `/api/v1/vendors`
- **GET** `/api/v1/vendors/{id}`
- **POST** `/api/v1/vendors`
- **PUT** `/api/v1/vendors/{id}`
- **DELETE** `/api/v1/vendors/{id}`

#### Purchase Orders
- **GET** `/api/v1/purchase-orders`
- **GET** `/api/v1/purchase-orders/{id}`
- **POST** `/api/v1/purchase-orders`
- **PUT** `/api/v1/purchase-orders/{id}`
- **DELETE** `/api/v1/purchase-orders/{id}`

#### Bills
- **GET** `/api/v1/bills`
- **GET** `/api/v1/bills/{id}`
- **POST** `/api/v1/bills`
- **PUT** `/api/v1/bills/{id}`
- **DELETE** `/api/v1/bills/{id}`

### Inventory Module

#### Products
- **GET** `/api/v1/products`
- **GET** `/api/v1/products/{id}`
- **POST** `/api/v1/products`
- **PUT** `/api/v1/products/{id}`
- **DELETE** `/api/v1/products/{id}`

#### Product Categories
- **GET** `/api/v1/product-categories`
- **GET** `/api/v1/product-categories/{id}`
- **POST** `/api/v1/product-categories`
- **PUT** `/api/v1/product-categories/{id}`
- **DELETE** `/api/v1/product-categories/{id}`

#### Warehouses
- **GET** `/api/v1/warehouses`
- **GET** `/api/v1/warehouses/{id}`
- **POST** `/api/v1/warehouses`
- **PUT** `/api/v1/warehouses/{id}`
- **DELETE** `/api/v1/warehouses/{id}`

#### Stock Transfers
- **GET** `/api/v1/stock-transfers`
- **GET** `/api/v1/stock-transfers/{id}`
- **POST** `/api/v1/stock-transfers`
- **PUT** `/api/v1/stock-transfers/{id}`
- **DELETE** `/api/v1/stock-transfers/{id}`

### HR Module

#### Employees
- **GET** `/api/v1/employees`
- **GET** `/api/v1/employees/{id}`
- **POST** `/api/v1/employees`
- **PUT** `/api/v1/employees/{id}`
- **DELETE** `/api/v1/employees/{id}`

#### Departments
- **GET** `/api/v1/departments`
- **GET** `/api/v1/departments/{id}`
- **POST** `/api/v1/departments`
- **PUT** `/api/v1/departments/{id}`
- **DELETE** `/api/v1/departments/{id}`

#### Attendance
- **GET** `/api/v1/attendance`
- **GET** `/api/v1/attendance/{id}`
- **POST** `/api/v1/attendance`
- **PUT** `/api/v1/attendance/{id}`
- **DELETE** `/api/v1/attendance/{id}`

#### Leave Requests
- **GET** `/api/v1/leave-requests`
- **GET** `/api/v1/leave-requests/{id}`
- **POST** `/api/v1/leave-requests`
- **PUT** `/api/v1/leave-requests/{id}`
- **DELETE** `/api/v1/leave-requests/{id}`

### Projects Module

#### Projects
- **GET** `/api/v1/projects`
- **GET** `/api/v1/projects/{id}`
- **POST** `/api/v1/projects`
- **PUT** `/api/v1/projects/{id}`
- **DELETE** `/api/v1/projects/{id}`

#### Tasks
- **GET** `/api/v1/tasks`
- **GET** `/api/v1/tasks/{id}`
- **POST** `/api/v1/tasks`
- **PUT** `/api/v1/tasks/{id}`
- **DELETE** `/api/v1/tasks/{id}`

#### Timesheets
- **GET** `/api/v1/timesheets`
- **GET** `/api/v1/timesheets/{id}`
- **POST** `/api/v1/timesheets`
- **PUT** `/api/v1/timesheets/{id}`
- **DELETE** `/api/v1/timesheets/{id}`

### Manufacturing Module

#### Bill of Materials
- **GET** `/api/v1/bill-of-materials`
- **GET** `/api/v1/bill-of-materials/{id}`
- **POST** `/api/v1/bill-of-materials`
- **PUT** `/api/v1/bill-of-materials/{id}`
- **DELETE** `/api/v1/bill-of-materials/{id}`

#### Work Orders
- **GET** `/api/v1/work-orders`
- **GET** `/api/v1/work-orders/{id}`
- **POST** `/api/v1/work-orders`
- **PUT** `/api/v1/work-orders/{id}`
- **DELETE** `/api/v1/work-orders/{id}`

### Accounting Module

#### Chart of Accounts
- **GET** `/api/v1/chart-of-accounts`
- **GET** `/api/v1/chart-of-accounts/{id}`
- **POST** `/api/v1/chart-of-accounts`
- **PUT** `/api/v1/chart-of-accounts/{id}`
- **DELETE** `/api/v1/chart-of-accounts/{id}`

#### Journals
- **GET** `/api/v1/journals`
- **GET** `/api/v1/journals/{id}`
- **POST** `/api/v1/journals`
- **PUT** `/api/v1/journals/{id}`
- **DELETE** `/api/v1/journals/{id}`

#### Journal Entries
- **GET** `/api/v1/journal-entries`
- **GET** `/api/v1/journal-entries/{id}`
- **POST** `/api/v1/journal-entries`
- **PUT** `/api/v1/journal-entries/{id}`
- **DELETE** `/api/v1/journal-entries/{id}`

### POS Module

#### Cash Registers
- **GET** `/api/v1/cash-registers`
- **GET** `/api/v1/cash-registers/{id}`
- **POST** `/api/v1/cash-registers`
- **PUT** `/api/v1/cash-registers/{id}`
- **DELETE** `/api/v1/cash-registers/{id}`

#### Payment Methods
- **GET** `/api/v1/payment-methods`
- **GET** `/api/v1/payment-methods/{id}`
- **POST** `/api/v1/payment-methods`
- **PUT** `/api/v1/payment-methods/{id}`
- **DELETE** `/api/v1/payment-methods/{id}`

#### POS Sessions
- **GET** `/api/v1/pos-sessions`
- **GET** `/api/v1/pos-sessions/{id}`
- **POST** `/api/v1/pos-sessions`
- **PUT** `/api/v1/pos-sessions/{id}`
- **DELETE** `/api/v1/pos-sessions/{id}`

#### POS Orders
- **GET** `/api/v1/pos-orders`
- **GET** `/api/v1/pos-orders/{id}`
- **POST** `/api/v1/pos-orders`
- **PUT** `/api/v1/pos-orders/{id}`
- **DELETE** `/api/v1/pos-orders/{id}`

#### POS Payments
- **GET** `/api/v1/pos-payments`
- **GET** `/api/v1/pos-payments/{id}`
- **POST** `/api/v1/pos-payments`
- **PUT** `/api/v1/pos-payments/{id}`
- **DELETE** `/api/v1/pos-payments/{id}`

## GraphQL API

### Query Examples

#### Get Current User
```graphql
query {
  me {
    id
    name
    email
    company {
      id
      name
    }
  }
}
```

#### List Leads
```graphql
query {
  leads(first: 15, page: 1) {
    data {
      id
      name
      email
      phone
      status
      created_at
    }
    paginatorInfo {
      currentPage
      lastPage
      total
      hasMorePages
    }
  }
}
```

#### Get Single Product
```graphql
query {
  product(id: "uuid") {
    id
    name
    code
    sale_price
    purchase_price
    is_active
  }
}
```

#### List Customers with Filtering
```graphql
query {
  customers(first: 10) {
    data {
      id
      name
      email
      customer_type
      is_active
    }
  }
}
```

### Mutation Examples

#### Create Lead
```graphql
mutation {
  createLead(input: {
    company_id: "uuid"
    name: "John Doe"
    email: "john@example.com"
    phone: "+1234567890"
    status: "new"
  }) {
    id
    name
    email
    status
  }
}
```

#### Update Lead
```graphql
mutation {
  updateLead(id: "uuid", input: {
    status: "qualified"
    description: "Interested in premium package"
  }) {
    id
    status
    description
  }
}
```

#### Create Product
```graphql
mutation {
  createProduct(input: {
    company_id: "uuid"
    name: "Premium Widget"
    code: "WIDGET-001"
    product_type: "product"
    sale_price: 99.99
    purchase_price: 50.00
  }) {
    id
    name
    code
    sale_price
  }
}
```

#### Create POS Order
```graphql
mutation {
  createPosOrder(input: {
    company_id: "uuid"
    pos_session_id: "uuid"
    order_number: "POS-2025-001"
    order_date: "2025-11-18 10:30:00"
    subtotal: 150.00
    total_amount: 165.00
  }) {
    id
    order_number
    total_amount
    status
  }
}
```

## Response Format

### REST API Success Response
```json
{
    "success": true,
    "message": "Success message",
    "data": {
        // Response data
    }
}
```

### REST API Paginated Response
```json
{
    "success": true,
    "message": "Success message",
    "data": [],
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 75,
        "from": 1,
        "to": 15
    },
    "links": {
        "first": "url",
        "last": "url",
        "prev": null,
        "next": "url"
    }
}
```

### REST API Error Response
```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        // Validation errors or other error details
    }
}
```

## Error Codes

- **200** - Success
- **201** - Created
- **400** - Bad Request
- **401** - Unauthorized
- **403** - Forbidden
- **404** - Not Found
- **422** - Validation Error
- **500** - Internal Server Error

## Rate Limiting

The API implements rate limiting to prevent abuse:
- **REST API**: 60 requests per minute per user
- **GraphQL API**: No specific rate limiting (uses query complexity analysis)

## Best Practices

1. **Always use HTTPS** in production
2. **Store tokens securely** - Never expose tokens in client-side code
3. **Implement proper error handling** for API failures
4. **Use pagination** for large datasets
5. **Cache responses** where appropriate
6. **Validate input** on both client and server sides
7. **Use GraphQL** for complex queries with multiple relations
8. **Use REST API** for simple CRUD operations

## Testing

### Using Postman for REST API
1. Import the environment variables
2. Set `base_url` to your API URL
3. Login to get token
4. Set token in Authorization header for protected endpoints

### Using GraphQL Playground
1. Navigate to `/graphql-playground`
2. Use the login mutation to get token
3. Add token to HTTP Headers:
```json
{
  "Authorization": "Bearer your-token-here"
}
```
4. Execute queries and mutations

## Support

For issues or questions about the API:
- Check this documentation first
- Review the GraphQL schema in `/graphql/schema.graphql`
- Contact the development team

## Changelog

### Version 1.0.0 (2025-11-18)
- Initial API release
- REST API v1 for all modules
- GraphQL API with comprehensive schema
- Sanctum authentication
- Complete CRUD operations for all entities
