# AgroSite Backend Development Roadmap

## Project Overview
This roadmap outlines the development plan for the AgroSite backend API using PHP Laravel framework and MySQL database. The backend will support Products, Services, and Contact management functionality.

---

## Phase 1: Project Setup & Configuration

### 1.1 Environment Setup
- [x] Install Laravel (latest stable version) ✅
- [x] Configure `.env` file with database credentials ✅
- [x] Set up MySQL database connection ✅
- [x] Configure CORS for frontend integration ✅
- [x] Set up Laravel Sanctum for API authentication (if needed) ✅
- [x] Configure file storage for product/service images ✅

**Status:** Complete - See `SETUP_COMPLETE.md` and `ENV_SETUP.md` for details

### 1.2 Project Structure
```
Backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ProductController.php
│   │   │   ├── ServiceController.php
│   │   │   └── ContactController.php
│   │   ├── Requests/
│   │   │   ├── ProductRequest.php
│   │   │   ├── ServiceRequest.php
│   │   │   └── ContactRequest.php
│   │   └── Resources/
│   │       ├── ProductResource.php
│   │       ├── ServiceResource.php
│   │       └── ContactResource.php
│   ├── Models/
│   │   ├── Product.php
│   │   ├── Service.php
│   │   └── Contact.php
│   └── Services/
│       ├── ProductService.php
│       ├── ServiceService.php
│       └── ContactService.php
├── database/
│   ├── migrations/
│   │   ├── create_products_table.php
│   │   ├── create_services_table.php
│   │   └── create_contacts_table.php
│   └── seeders/
│       ├── ProductSeeder.php
│       ├── ServiceSeeder.php
│       └── DatabaseSeeder.php
├── routes/
│   └── api.php
└── tests/
    ├── ProductTest.php
    ├── ServiceTest.php
    └── ContactTest.php
```

---

## Phase 2: Database Design & Migrations

**Status:** Migrations Created ✅ - See `PHASE2_COMPLETE.md` and `RUN_MIGRATIONS.md` for details

### 2.1 Products Table
**Table: `products`**
- `id` (bigint, primary key, auto increment)
- `name` (string, required)
- `description` (text, nullable)
- `category` (enum: 'seeds', 'fertilizers', 'equipment', 'tools')
- `price` (decimal 10,2, required)
- `currency` (string, default: 'USD')
- `image_url` (string, nullable)
- `stock_quantity` (integer, default: 0)
- `status` (enum: 'active', 'inactive', 'out_of_stock', default: 'active')
- `created_at` (timestamp)
- `updated_at` (timestamp)
- `deleted_at` (timestamp, nullable) - for soft deletes

**Indexes:**
- Index on `category`
- Index on `status`
- Full-text index on `name` and `description` for search

### 2.2 Services Table
**Table: `services`**
- `id` (bigint, primary key, auto increment)
- `service_id` (string, unique) - e.g., "S001"
- `name` (string, required)
- `description` (text, nullable)
- `category` (string) - e.g., 'Planning', 'Installation', 'Analysis', 'Consulting', 'Management', 'Technology'
- `icon` (string) - FontAwesome icon class name
- `price` (decimal 10,2, nullable)
- `price_type` (enum: 'fixed', 'monthly', 'hourly', 'per_unit', nullable)
- `active_clients` (integer, default: 0)
- `status` (enum: 'active', 'inactive', 'pending', default: 'active')
- `image_url` (string, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)
- `deleted_at` (timestamp, nullable)

**Indexes:**
- Unique index on `service_id`
- Index on `category`
- Index on `status`

### 2.3 Contacts Table
**Table: `contacts`**
- `id` (bigint, primary key, auto increment)
- `name` (string, required)
- `email` (string, required, unique per submission)
- `phone` (string, nullable)
- `subject` (enum: 'general', 'service', 'consultation', 'support', 'partnership', 'other')
- `message` (text, required)
- `status` (enum: 'new', 'read', 'replied', 'archived', default: 'new')
- `ip_address` (string, nullable) - for spam prevention
- `user_agent` (text, nullable)
- `replied_at` (timestamp, nullable)
- `replied_by` (bigint, nullable) - admin user ID
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Indexes:**
- Index on `email`
- Index on `status`
- Index on `subject`
- Index on `created_at`

---

## Phase 3: Models & Relationships

**Status:** Complete ✅ - See `PHASE3_COMPLETE.md` for details

### 3.1 Product Model
- Implement soft deletes
- Add scopes for filtering (by category, status, price range)
- Add accessors/mutators for price formatting
- Add image URL accessor

### 3.2 Service Model
- Implement soft deletes
- Add scopes for filtering (by category, status)
- Add accessors for formatted price display
- Auto-generate service_id if not provided

### 3.3 Contact Model
- Add scopes for filtering (by status, subject, date range)
- Add accessors for formatted date display
- Implement email validation

---

## Phase 4: API Endpoints Development

**Status:** Complete ✅ - See `PHASE4_COMPLETE.md` for details

### 4.1 Products API Endpoints

#### GET `/api/products`
- List all products with pagination
- Query parameters:
  - `page` (integer)
  - `per_page` (integer, default: 15)
  - `category` (filter by category)
  - `status` (filter by status)
  - `search` (search in name/description)
  - `min_price` (minimum price filter)
  - `max_price` (maximum price filter)
  - `sort` (sort by: name, price, created_at)
  - `order` (asc/desc)

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Premium Wheat Seeds",
      "description": "...",
      "category": "seeds",
      "price": "25.99",
      "currency": "USD",
      "image_url": "...",
      "stock_quantity": 100,
      "status": "active"
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 25,
    "last_page": 2
  }
}
```

#### GET `/api/products/{id}`
- Get single product details

#### POST `/api/products` (Admin only)
- Create new product
- Validation: name, price, category required

#### PUT `/api/products/{id}` (Admin only)
- Update product

#### DELETE `/api/products/{id}` (Admin only)
- Soft delete product

---

### 4.2 Services API Endpoints

#### GET `/api/services`
- List all services with pagination
- Query parameters:
  - `page` (integer)
  - `per_page` (integer, default: 15)
  - `category` (filter by category)
  - `status` (filter by status)
  - `search` (search in name/description)

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "service_id": "S001",
      "name": "Crop Planning & Management",
      "description": "...",
      "category": "Planning",
      "icon": "fa-seedling",
      "price": "299.00",
      "price_type": "monthly",
      "active_clients": 24,
      "status": "active",
      "image_url": "..."
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 8,
    "last_page": 1
  }
}
```

#### GET `/api/services/{id}`
- Get single service details

#### POST `/api/services` (Admin only)
- Create new service
- Auto-generate service_id if not provided

#### PUT `/api/services/{id}` (Admin only)
- Update service
- Update active_clients count

#### DELETE `/api/services/{id}` (Admin only)
- Soft delete service

#### PATCH `/api/services/{id}/update-clients` (Admin only)
- Update active clients count

---

### 4.3 Contact API Endpoints

#### POST `/api/contacts`
- Submit contact form
- Validation: name, email, subject, message required
- Email format validation
- Rate limiting (max 5 submissions per hour per IP)
- Send auto-reply email to user
- Send notification email to admin

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "+1234567890",
  "subject": "consultation",
  "message": "I need help with..."
}
```

**Response:**
```json
{
  "success": true,
  "message": "Thank you for contacting us. We'll get back to you within 24 hours.",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "subject": "consultation",
    "status": "new"
  }
}
```

#### GET `/api/contacts` (Admin only)
- List all contact submissions
- Query parameters:
  - `page` (integer)
  - `per_page` (integer, default: 20)
  - `status` (filter by status)
  - `subject` (filter by subject)
  - `search` (search in name/email/message)
  - `date_from` (filter from date)
  - `date_to` (filter to date)

#### GET `/api/contacts/{id}` (Admin only)
- Get single contact submission details

#### PATCH `/api/contacts/{id}/status` (Admin only)
- Update contact status (new, read, replied, archived)

#### DELETE `/api/contacts/{id}` (Admin only)
- Delete contact submission

---

## Phase 5: Request Validation & Form Requests

**Status:** Complete ✅ - See `PHASE5_COMPLETE.md` for details

### 5.1 ProductRequest
- Validation rules for create/update
- Image upload validation
- Price validation (must be positive)

### 5.2 ServiceRequest
- Validation rules for create/update
- Service ID format validation
- Price type validation

### 5.3 ContactRequest
- Name: required, min 2, max 100
- Email: required, valid email format
- Phone: optional, valid phone format
- Subject: required, must be in allowed values
- Message: required, min 10, max 2000

---

## Phase 6: API Resources (Response Formatting)

**Status:** Complete ✅ - See `PHASE6_COMPLETE.md` for details

### 6.1 ProductResource
- Format product data for API response
- Include formatted price
- Include full image URL

### 6.2 ServiceResource
- Format service data for API response
- Include formatted price with type
- Include active clients count

### 6.3 ContactResource
- Format contact data for API response
- Exclude sensitive information for public endpoints

---

## Phase 7: Service Layer (Business Logic)

**Status:** Complete ✅ - See `PHASE7_COMPLETE.md` for details

### 7.1 ProductService
- Handle product CRUD operations
- Image upload/management
- Search and filtering logic
- Stock management

### 7.2 ServiceService
- Handle service CRUD operations
- Service ID generation logic
- Active clients management

### 7.3 ContactService
- Handle contact form submission
- Email notification logic
- Spam detection
- Rate limiting

---

## Phase 8: Email Notifications

### 8.1 Contact Form Emails
- [ ] Create contact form submission email template
- [ ] Create auto-reply email template
- [ ] Create admin notification email template
- [ ] Configure email service (Mailgun/SendGrid/SMTP)
- [ ] Queue emails for better performance

### 8.2 Email Templates
- Use Laravel Mail with Blade templates
- Responsive email design
- Include company branding

---

## Phase 9: Authentication & Authorization (Optional)

### 9.1 Admin Authentication
- [ ] Implement Laravel Sanctum for API authentication
- [ ] Create admin user model/migration
- [ ] Admin login endpoint: `POST /api/admin/login`
- [ ] Admin logout endpoint: `POST /api/admin/logout`
- [ ] Protected routes for admin operations

### 9.2 Role-Based Access Control
- Admin role for full access
- API token management

---

## Phase 10: Database Seeders

**Status:** Complete ✅ - See `PHASE10_COMPLETE.md` for details

### 10.1 ProductSeeder
- Seed sample products (25+ products)
- Cover all categories: seeds, fertilizers, equipment, tools

### 10.2 ServiceSeeder
- Seed sample services (8 services matching frontend)
- Include all service categories

### 10.3 ContactSeeder (Optional)
- Seed sample contact submissions for testing

---

## Phase 11: API Documentation

### 11.1 API Documentation
- [ ] Use Laravel API documentation tool (Laravel API Documentation or Scribe)
- [ ] Document all endpoints
- [ ] Include request/response examples
- [ ] Include authentication requirements
- [ ] Create Postman collection

---

## Phase 12: Testing

### 12.1 Unit Tests
- [ ] Test Product model methods
- [ ] Test Service model methods
- [ ] Test Contact model methods

### 12.2 Feature Tests
- [ ] Test Product API endpoints
- [ ] Test Service API endpoints
- [ ] Test Contact API endpoints
- [ ] Test validation rules
- [ ] Test error handling

### 12.3 Integration Tests
- [ ] Test complete workflows
- [ ] Test email notifications

---

## Phase 13: Performance Optimization

### 13.1 Database Optimization
- [ ] Add proper indexes
- [ ] Optimize queries (use eager loading)
- [ ] Implement query caching for frequently accessed data

### 13.2 API Optimization
- [ ] Implement API response caching
- [ ] Use pagination for large datasets
- [ ] Optimize image handling (resize, compression)

### 13.3 Code Optimization
- [ ] Use Laravel queues for heavy operations
- [ ] Implement rate limiting
- [ ] Optimize N+1 query problems

---

## Phase 14: Security

### 14.1 Security Measures
- [ ] Implement CSRF protection
- [ ] Sanitize user inputs
- [ ] Implement rate limiting on contact form
- [ ] Add spam detection for contact form
- [ ] Secure file uploads
- [ ] Use HTTPS in production
- [ ] Implement API rate limiting
- [ ] Add input validation on all endpoints

---

## Phase 15: Deployment Preparation

**Status:** Complete ✅ - See `RENDER_DEPLOYMENT_GUIDE.md` and `DEPLOYMENT_CHECKLIST.md` for details

### 15.1 Environment Configuration
- [x] ✅ Production `.env` configuration (`.env.production.example`)
- [x] ✅ Database connection settings
- [x] ✅ Email service configuration
- [x] ✅ File storage configuration (local/S3)
- [x] ✅ Render-specific configuration (`render.yaml`)

### 15.2 Deployment Checklist
- [x] ✅ Deployment checklist created (`DEPLOYMENT_CHECKLIST.md`)
- [x] ✅ Build scripts created (`render-build.sh`)
- [x] ✅ Start scripts created (`render-start.sh`)
- [x] ✅ Deployment guide created (`RENDER_DEPLOYMENT_GUIDE.md`)
- [x] ✅ Run migrations (documented)
- [x] ✅ Seed initial data (documented)
- [x] ✅ Web server configuration (Render handles this)
- [x] ✅ SSL certificate (automatic on Render)
- [x] ✅ Monitoring/logging (Render dashboard)

---

## Phase 16: Frontend Integration

### 16.1 API Integration Points
- [ ] Update frontend to fetch products from API
- [ ] Update frontend to fetch services from API
- [ ] Integrate contact form with API endpoint
- [ ] Handle API errors gracefully
- [ ] Implement loading states
- [ ] Add error handling

---

## Technology Stack

- **Framework:** Laravel 10.x or 11.x
- **Database:** MySQL 8.0+
- **Authentication:** Laravel Sanctum (if needed)
- **Email:** Laravel Mail (SMTP/Mailgun/SendGrid)
- **File Storage:** Local/S3
- **API Documentation:** Laravel API Documentation or Scribe
- **Testing:** PHPUnit
- **Version Control:** Git

---

## Estimated Timeline

- **Phase 1-2:** 2-3 days (Setup & Database)
- **Phase 3-4:** 5-7 days (Models & API Endpoints)
- **Phase 5-7:** 3-4 days (Validation & Services)
- **Phase 8:** 2 days (Email Notifications)
- **Phase 9:** 2-3 days (Authentication - Optional)
- **Phase 10-11:** 2 days (Seeders & Documentation)
- **Phase 12:** 3-4 days (Testing)
- **Phase 13-14:** 2-3 days (Optimization & Security)
- **Phase 15-16:** 2-3 days (Deployment & Integration)

**Total Estimated Time:** 23-31 days (approximately 1 month)

---

## Next Steps

1. Start with Phase 1: Set up Laravel project
2. Create database and configure connection
3. Design and create migrations
4. Build models and relationships
5. Develop API endpoints
6. Test thoroughly
7. Deploy and integrate with frontend

---

## Notes

- All dates and timestamps should use UTC timezone
- Use Laravel's built-in validation and error handling
- Follow Laravel best practices and PSR standards
- Keep API responses consistent
- Implement proper error logging
- Use environment variables for sensitive data
- Consider implementing API versioning (v1, v2) for future scalability

---

## Future Enhancements (Post-MVP)

- [ ] User authentication and profiles
- [ ] Shopping cart functionality
- [ ] Order management system
- [ ] Payment gateway integration
- [ ] Product reviews and ratings
- [ ] Service booking system
- [ ] Admin dashboard API
- [ ] Analytics and reporting
- [ ] Multi-language support
- [ ] Real-time notifications (WebSockets)
- [ ] Advanced search with Elasticsearch
- [ ] Image optimization and CDN integration

