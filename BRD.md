# Business Requirements Document (BRD)

## K2 Computer E-Commerce Platform

**Document Version:** 1.0
**Date:** November 4, 2025
**Project Name:** K2 Computer E-Commerce System
**Platform:** Laravel 12.0 Web Application
**Target Market:** Cambodia

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Business Objectives](#2-business-objectives)
3. [Project Scope](#3-project-scope)
4. [Stakeholders](#4-stakeholders)
5. [Functional Requirements](#5-functional-requirements)
6. [Non-Functional Requirements](#6-non-functional-requirements)
7. [User Roles & Permissions](#7-user-roles--permissions)
8. [User Stories & Use Cases](#8-user-stories--use-cases)
9. [System Architecture](#9-system-architecture)
10. [Data Requirements](#10-data-requirements)
11. [Integration Requirements](#11-integration-requirements)
12. [User Interface Requirements](#12-user-interface-requirements)
13. [Security Requirements](#13-security-requirements)
14. [Assumptions & Constraints](#14-assumptions--constraints)
15. [Dependencies](#15-dependencies)
16. [Success Criteria & KPIs](#16-success-criteria--kpis)
17. [Implementation Roadmap](#17-implementation-roadmap)
18. [Risks & Mitigation](#18-risks--mitigation)
19. [Appendix](#19-appendix)

---

## 1. Executive Summary

### 1.1 Project Overview

K2 Computer is a full-stack e-commerce web application designed to facilitate online sales of computer hardware, peripherals, and electronics in the Cambodian market. The platform enables customers to browse products, manage shopping carts, complete purchases through multiple payment methods including the local KHQR payment system, and manage their accounts.

### 1.2 Business Need

The project addresses the growing demand for online computer hardware retail in Cambodia by providing:
- A user-friendly digital storefront for computer products
- Integration with Cambodian payment systems (KHQR/Bakong)
- Multi-category product catalog (laptops, desktops, monitors, accessories, networking, printers)
- Complete e-commerce functionality including cart, wishlist, and checkout
- Administrative tools for inventory and category management

### 1.3 Current Status

**Development Phase:** Alpha/MVP (Minimum Viable Product)
**Completion Level:** ~60%
**Status:** Core features implemented with session-based data storage; database integration and payment processing require completion.

---

## 2. Business Objectives

### 2.1 Primary Objectives

1. **Establish Online Presence**
   - Create a professional e-commerce platform for K2 Computer
   - Enable 24/7 product browsing and purchasing
   - Expand market reach beyond physical locations

2. **Revenue Generation**
   - Facilitate online sales with multiple payment methods
   - Reduce overhead costs associated with physical retail
   - Enable scalable business growth

3. **Customer Experience**
   - Provide seamless shopping experience from browsing to checkout
   - Offer local payment options (KHQR) familiar to Cambodian users
   - Enable wishlist and cart management for convenience

4. **Operational Efficiency**
   - Streamline order management and processing
   - Enable administrators to manage product catalog efficiently
   - Automate pricing calculations (tax, shipping, discounts)

### 2.2 Secondary Objectives

1. Support multi-branch operations (infrastructure ready)
2. Provide API access for future mobile applications
3. Enable data-driven decisions through analytics
4. Build customer loyalty through user accounts and order history

---

## 3. Project Scope

### 3.1 In Scope

#### Customer-Facing Features
- Product browsing with category and brand filtering
- Advanced search functionality
- Product detail pages with specifications
- Shopping cart management
- Wishlist functionality
- Multi-step checkout process
- Order confirmation
- User registration and authentication
- Profile management
- Password reset functionality

#### Payment Integration
- KHQR (Cambodian QR code payment) integration
- Cash on Delivery (COD) option
- Credit/Debit card payment (infrastructure ready)

#### Administrative Features
- Category management (CRUD operations)
- Product management (database schema ready)
- Order viewing (infrastructure ready)

#### Technical Features
- Responsive web design (mobile, tablet, desktop)
- Session-based cart and wishlist
- Email verification for accounts
- RESTful API endpoints for categories

### 3.2 Out of Scope (Future Phases)

- Mobile applications (iOS/Android)
- Customer reviews and ratings functionality
- Product comparison feature
- Real-time inventory management
- Advanced analytics dashboard
- Multi-language support (English/Khmer)
- SMS notifications
- Loyalty program/rewards system
- Live chat customer support
- Product recommendations engine
- Social media integration
- Gift cards and promotional codes
- Subscription products
- Order tracking system
- Return/refund management

---

## 4. Stakeholders

### 4.1 Primary Stakeholders

| Stakeholder | Role | Responsibilities |
|------------|------|------------------|
| **Business Owner** | Decision Maker | Final approval, business strategy, budget allocation |
| **Customers** | End Users | Browse, purchase products, provide feedback |
| **Development Team** | Implementers | Design, develop, test, deploy application |
| **Administrators** | Operations | Manage products, categories, orders, customers |

### 4.2 Secondary Stakeholders

- Payment Gateway Providers (KHQR/Bakong)
- Hosting Provider (Vercel)
- Marketing Team
- Customer Support Staff

---

## 5. Functional Requirements

### 5.1 User Management

#### FR-UM-001: User Registration
**Priority:** High
**Description:** Users shall be able to create accounts with email and password
**Acceptance Criteria:**
- Form collects: name, email, password, password confirmation
- Email uniqueness validation
- Password minimum 8 characters with complexity rules
- Email verification sent upon registration
- User assigned default role "student" (customer)

#### FR-UM-002: User Authentication
**Priority:** High
**Description:** Users shall be able to log in and log out securely
**Acceptance Criteria:**
- Login with email and password
- "Remember me" functionality
- Session timeout after 120 minutes of inactivity
- Secure logout clearing all session data
- Failed login attempt handling

#### FR-UM-003: Password Management
**Priority:** High
**Description:** Users shall be able to reset forgotten passwords
**Acceptance Criteria:**
- "Forgot Password" link on login page
- Password reset email with secure token
- Token expiration after 60 minutes
- New password confirmation
- Password updated successfully

#### FR-UM-004: Profile Management
**Priority:** Medium
**Description:** Users shall be able to view and edit their profile information
**Acceptance Criteria:**
- Update name and email address
- Change password with current password verification
- Delete account with password confirmation
- Profile changes saved to database

#### FR-UM-005: Email Verification
**Priority:** Medium
**Description:** Users must verify email to access protected features
**Acceptance Criteria:**
- Verification email sent upon registration
- Dashboard access requires verified email
- Resend verification email option
- Verification link expires after 24 hours

### 5.2 Product Management

#### FR-PM-001: Product Catalog Display
**Priority:** High
**Description:** System shall display available products with details
**Acceptance Criteria:**
- Product grid layout with images
- Display: name, price, rating, brand, availability
- Pagination or infinite scroll
- Responsive design for all devices
- Product count indicator

#### FR-PM-002: Product Details
**Priority:** High
**Description:** Users shall view comprehensive product information
**Acceptance Criteria:**
- Large product image
- Full product description
- Specifications table
- Pricing information (original, discounted)
- Stock status and quantity
- Add to cart button
- Add to wishlist button
- SKU and brand information

#### FR-PM-003: Product Search
**Priority:** High
**Description:** Users shall search products by keyword
**Acceptance Criteria:**
- Search bar accessible from all pages
- Search across: name, brand, category, description
- Real-time search results
- No results message when applicable
- Search term persistence

#### FR-PM-004: Product Filtering
**Priority:** High
**Description:** Users shall filter products by multiple criteria
**Acceptance Criteria:**
- Filter by category (Laptops, Desktops, Monitors, etc.)
- Filter by brand (multi-select checkbox)
- Filter by price range ($0-100, $100-500, $500-1000, $1000+)
- Filters apply simultaneously (AND logic)
- Clear filters option
- Filter count indicators

#### FR-PM-005: Product Sorting
**Priority:** Medium
**Description:** Users shall sort products by various attributes
**Acceptance Criteria:**
- Sort by: Price (Low to High), Price (High to Low)
- Sort by: Name (A-Z), Rating, Newest
- Sorting preserves applied filters
- Default sort order defined

#### FR-PM-006: Admin Product Management (Planned)
**Priority:** High
**Description:** Admins shall manage product inventory
**Acceptance Criteria:**
- Create new products with all attributes
- Update product information and pricing
- Delete products
- Upload product images
- Set stock quantities
- Enable/disable products

### 5.3 Shopping Cart

#### FR-SC-001: Add to Cart
**Priority:** High
**Description:** Users shall add products to shopping cart
**Acceptance Criteria:**
- Add product from listing or detail page
- Default quantity: 1
- Visual confirmation (toast notification)
- Cart counter updates in header
- Duplicate items increase quantity

#### FR-SC-002: View Cart
**Priority:** High
**Description:** Users shall view all cart items with totals
**Acceptance Criteria:**
- Display: product image, name, price, quantity
- Show subtotal per line item
- Calculate and display: Subtotal, Shipping, Tax, Total
- Empty cart message when applicable
- Continue shopping link

#### FR-SC-003: Update Cart Quantity
**Priority:** High
**Description:** Users shall modify item quantities in cart
**Acceptance Criteria:**
- Increment/decrement buttons
- Direct quantity input field
- Real-time price recalculation
- AJAX update without page reload
- Minimum quantity: 1
- Maximum quantity: stock availability

#### FR-SC-004: Remove from Cart
**Priority:** High
**Description:** Users shall remove individual items from cart
**Acceptance Criteria:**
- Remove button per line item
- Confirmation prompt (optional)
- Immediate cart recalculation
- Success notification
- Cart counter updates

#### FR-SC-005: Clear Cart
**Priority:** Medium
**Description:** Users shall empty entire cart at once
**Acceptance Criteria:**
- "Clear Cart" button on cart page
- Confirmation prompt required
- All items removed immediately
- Empty cart message displayed
- Cart counter resets to 0

#### FR-SC-006: Cart Calculations
**Priority:** High
**Description:** System shall accurately calculate order totals
**Acceptance Criteria:**
- Subtotal = Sum of (price × quantity)
- Tax = Subtotal × 8%
- Shipping = $15 if Subtotal < $100, else FREE
- Total = Subtotal + Tax + Shipping
- All amounts rounded to 2 decimal places

### 5.4 Wishlist

#### FR-WL-001: Add to Wishlist
**Priority:** Medium
**Description:** Users shall save products for later purchase
**Acceptance Criteria:**
- Heart icon on product cards
- Toggle functionality (add/remove)
- Visual state change (filled/outline heart)
- AJAX operation without page reload
- Toast notification on add

#### FR-WL-002: View Wishlist
**Priority:** Medium
**Description:** Users shall view all wishlisted products
**Acceptance Criteria:**
- Grid layout similar to product listing
- Display product image, name, price
- Add to cart button
- Remove from wishlist button
- Empty wishlist message

#### FR-WL-003: Remove from Wishlist
**Priority:** Medium
**Description:** Users shall delete items from wishlist
**Acceptance Criteria:**
- Remove button per item
- Immediate removal without confirmation
- Success notification
- Wishlist count updates

### 5.5 Checkout & Orders

#### FR-CO-001: Checkout Process
**Priority:** High
**Description:** Users shall complete purchase through guided checkout
**Acceptance Criteria:**
- Multi-step form: Contact Info → Shipping → Payment
- Required fields: First Name, Last Name, Email, Phone
- Required fields: Address, City, State, Zip Code
- Order summary sidebar with totals
- Edit cart link
- Payment method selection

#### FR-CO-002: Order Generation
**Priority:** High
**Description:** System shall generate unique order numbers
**Acceptance Criteria:**
- Format: K2-XXXXXX (6-digit random number)
- Unique order number per transaction
- Order number displayed on confirmation page
- Order number sent via email (when implemented)

#### FR-CO-003: Order Confirmation
**Priority:** High
**Description:** Users shall receive order confirmation
**Acceptance Criteria:**
- Confirmation page with order number
- Display order details and totals
- Delivery estimate information
- Print order option
- Continue shopping link
- Cart cleared after successful order

#### FR-CO-004: Order Storage (To Be Implemented)
**Priority:** High
**Description:** System shall persist order data to database
**Acceptance Criteria:**
- Create order record with customer info
- Link order items to products
- Store payment method and status
- Record order date/time
- Enable order retrieval for users and admins

### 5.6 Payment Processing

#### FR-PP-001: KHQR Payment
**Priority:** High
**Description:** Users shall pay via Cambodian KHQR system
**Acceptance Criteria:**
- Generate QR code with order amount
- Display merchant information (BUNSONG EAR, PHNOM PENH)
- Support KHR currency
- Transaction verification via MD5 hash
- JWT authentication with KHQR API
- Payment success/failure handling

#### FR-PP-002: Cash on Delivery
**Priority:** Medium
**Description:** Users shall select COD payment option
**Acceptance Criteria:**
- COD option on checkout page
- Order confirmed without payment processing
- COD status stored with order
- Instructions displayed to customer

#### FR-PP-003: Card Payment (Planned)
**Priority:** Medium
**Description:** Users shall pay with credit/debit cards
**Acceptance Criteria:**
- Secure payment form (PCI compliant)
- Accept major card types (Visa, Mastercard)
- CVV and expiration validation
- Payment gateway integration
- Transaction status handling

### 5.7 Category Management

#### FR-CM-001: View Categories
**Priority:** High
**Description:** Admins shall view all product categories
**Acceptance Criteria:**
- List all categories in admin panel
- Display category name and ID
- Edit and delete actions per category
- Add new category button

#### FR-CM-002: Create Category
**Priority:** High
**Description:** Admins shall add new product categories
**Acceptance Criteria:**
- Category creation form
- Required field: Name (max 255 characters)
- Validation and error handling
- Success notification
- Redirect to category list

#### FR-CM-003: Update Category
**Priority:** High
**Description:** Admins shall edit existing categories
**Acceptance Criteria:**
- Edit form pre-populated with current data
- Update category name
- Validation rules applied
- Success notification
- Changes reflected immediately

#### FR-CM-004: Delete Category
**Priority:** High
**Description:** Admins shall remove categories
**Acceptance Criteria:**
- Delete button per category
- Confirmation prompt
- Category removed from database
- Handle products in deleted category (reassign or cascade)
- Success notification

### 5.8 API Endpoints

#### FR-API-001: Category API
**Priority:** Medium
**Description:** Provide RESTful API for category operations
**Acceptance Criteria:**
- GET /api/categories - List all categories (JSON)
- GET /api/categories/{id} - Get single category (JSON)
- PUT /api/categories/{id} - Update category
- DELETE /api/categories/{id} - Delete category
- Proper HTTP status codes (200, 201, 204, 404)
- JSON response format

#### FR-API-002: Product API (Planned)
**Priority:** Low
**Description:** Provide RESTful API for product operations
**Acceptance Criteria:**
- GET /api/products - List products with pagination
- GET /api/products/{id} - Get product details
- Filter and sort parameters
- Search capability
- JSON response format

---

## 6. Non-Functional Requirements

### 6.1 Performance

#### NFR-PERF-001: Page Load Time
**Priority:** High
**Target:** < 3 seconds for initial page load on 4G connection
**Measurement:** Google Lighthouse, WebPageTest

#### NFR-PERF-002: Database Query Performance
**Priority:** High
**Target:** All database queries complete in < 500ms
**Measurement:** Laravel Telescope, Query logging

#### NFR-PERF-003: Concurrent Users
**Priority:** Medium
**Target:** Support 100+ concurrent users without degradation
**Measurement:** Load testing with JMeter/Artillery

#### NFR-PERF-004: API Response Time
**Priority:** Medium
**Target:** API endpoints respond in < 200ms
**Measurement:** API monitoring tools

### 6.2 Scalability

#### NFR-SCALE-001: Database Scalability
**Priority:** Medium
**Requirement:** Database design supports 100,000+ products and 10,000+ users

#### NFR-SCALE-002: Horizontal Scaling
**Priority:** Low
**Requirement:** Application architecture supports multiple server instances

### 6.3 Availability & Reliability

#### NFR-AVAIL-001: Uptime
**Priority:** High
**Target:** 99.5% uptime (monthly)
**Measurement:** Uptime monitoring (UptimeRobot, Pingdom)

#### NFR-AVAIL-002: Error Rate
**Priority:** High
**Target:** < 1% error rate for all requests
**Measurement:** Application logs, error tracking (Sentry)

#### NFR-AVAIL-003: Data Backup
**Priority:** High
**Requirement:** Daily automated database backups with 30-day retention

### 6.4 Usability

#### NFR-USAB-001: Responsive Design
**Priority:** High
**Requirement:** Application fully functional on devices from 320px to 4K displays
**Measurement:** Browser DevTools, physical device testing

#### NFR-USAB-002: Browser Compatibility
**Priority:** High
**Requirement:** Support latest 2 versions of Chrome, Firefox, Safari, Edge
**Measurement:** Cross-browser testing

#### NFR-USAB-003: Accessibility
**Priority:** Medium
**Target:** WCAG 2.1 Level AA compliance
**Measurement:** Lighthouse, WAVE, axe DevTools

#### NFR-USAB-004: User Learning Curve
**Priority:** Medium
**Target:** New users complete purchase within 10 minutes without assistance
**Measurement:** User testing sessions

### 6.5 Security

#### NFR-SEC-001: Authentication
**Priority:** High
**Requirement:** Passwords hashed with bcrypt (cost factor 12 minimum)
**Implementation:** Laravel default authentication

#### NFR-SEC-002: HTTPS
**Priority:** High
**Requirement:** All traffic encrypted with TLS 1.2 or higher
**Implementation:** SSL certificate on production domain

#### NFR-SEC-003: CSRF Protection
**Priority:** High
**Requirement:** All state-changing requests protected against CSRF
**Implementation:** Laravel CSRF token middleware

#### NFR-SEC-004: SQL Injection Prevention
**Priority:** High
**Requirement:** All database queries use prepared statements
**Implementation:** Laravel Eloquent ORM

#### NFR-SEC-005: XSS Prevention
**Priority:** High
**Requirement:** All user input escaped before output
**Implementation:** Blade template engine auto-escaping

#### NFR-SEC-006: Session Security
**Priority:** High
**Requirement:** Secure session configuration with HttpOnly, SameSite cookies
**Implementation:** Laravel session middleware

#### NFR-SEC-007: Rate Limiting
**Priority:** Medium
**Requirement:** API and login endpoints rate-limited (60 requests/minute)
**Implementation:** Laravel throttle middleware

### 6.6 Maintainability

#### NFR-MAINT-001: Code Standards
**Priority:** Medium
**Requirement:** Follow PSR-12 coding standards for PHP
**Measurement:** Laravel Pint, PHPStan

#### NFR-MAINT-002: Documentation
**Priority:** Medium
**Requirement:** All public methods documented with PHPDoc
**Measurement:** Code review

#### NFR-MAINT-003: Version Control
**Priority:** High
**Requirement:** All code stored in Git with meaningful commit messages
**Implementation:** GitHub/GitLab repository

#### NFR-MAINT-004: Testing
**Priority:** Medium
**Target:** 70% code coverage with unit and feature tests
**Measurement:** PHPUnit/Pest coverage reports

### 6.7 Compatibility

#### NFR-COMP-001: PHP Version
**Priority:** High
**Requirement:** PHP 8.2 or higher

#### NFR-COMP-002: Database
**Priority:** High
**Requirement:** PostgreSQL 13+ or MySQL 8.0+

#### NFR-COMP-003: Browser Support
**Priority:** High
**Requirement:** Chrome 90+, Firefox 88+, Safari 14+, Edge 90+

### 6.8 Localization

#### NFR-LOCAL-001: Currency
**Priority:** High
**Requirement:** Support USD display with $ symbol
**Future:** KHR (Cambodian Riel) with ៛ symbol

#### NFR-LOCAL-002: Timezone
**Priority:** Medium
**Requirement:** All timestamps in Indochina Time (ICT, UTC+7)

#### NFR-LOCAL-003: Language (Future)
**Priority:** Low
**Requirement:** Multi-language support (English, Khmer)

---

## 7. User Roles & Permissions

### 7.1 Role Definitions

#### Customer (Student)
**Description:** Regular users who browse and purchase products
**Permissions:**
- Browse products and categories
- Search and filter products
- Add/remove items from cart
- Add/remove items from wishlist
- Complete checkout and place orders
- View own profile and order history
- Update own profile information
- Reset own password

#### Administrator (Admin)
**Description:** Staff members who manage the platform
**Permissions:**
- All customer permissions
- View all orders
- Manage product catalog (CRUD)
- Manage categories (CRUD)
- View customer information
- Process refunds (when implemented)
- Generate reports (when implemented)
- Manage system settings (when implemented)

#### Guest (Unauthenticated)
**Description:** Visitors without accounts
**Permissions:**
- Browse products and categories
- Search and filter products
- View product details
- Add items to session-based cart
- Register for account
- Login to existing account

### 7.2 Permission Matrix

| Feature | Guest | Customer | Admin |
|---------|-------|----------|-------|
| View Products | ✓ | ✓ | ✓ |
| Search Products | ✓ | ✓ | ✓ |
| View Cart | ✓ | ✓ | ✓ |
| Checkout | ✗ | ✓ | ✓ |
| View Order History | ✗ | ✓ (own) | ✓ (all) |
| Manage Wishlist | ✗ | ✓ | ✓ |
| Update Profile | ✗ | ✓ (own) | ✓ (own) |
| Manage Products | ✗ | ✗ | ✓ |
| Manage Categories | ✗ | ✗ | ✓ |
| View All Orders | ✗ | ✗ | ✓ |
| Generate Reports | ✗ | ✗ | ✓ |

---

## 8. User Stories & Use Cases

### 8.1 Customer User Stories

#### US-001: Browse Products
**As a** customer
**I want to** browse available products
**So that** I can find items I'm interested in purchasing

**Acceptance Criteria:**
- Products displayed in grid layout with images
- Each product shows name, price, and rating
- Categories visible in navigation
- Filtering options available

---

#### US-002: Search for Products
**As a** customer
**I want to** search for products by keyword
**So that** I can quickly find specific items

**Acceptance Criteria:**
- Search bar accessible from any page
- Results display within 2 seconds
- Relevant products shown based on search term
- No results message when applicable

---

#### US-003: Filter Products
**As a** customer
**I want to** filter products by category, brand, and price
**So that** I can narrow down my options

**Acceptance Criteria:**
- Multiple filters apply simultaneously
- Product count updates with filters
- Clear filters option available
- Filter state persists when navigating back

---

#### US-004: Add to Cart
**As a** customer
**I want to** add products to my shopping cart
**So that** I can purchase them later

**Acceptance Criteria:**
- Add to cart button on product pages
- Visual confirmation when item added
- Cart counter updates in header
- Can add multiple quantities

---

#### US-005: Manage Cart
**As a** customer
**I want to** view and modify my cart contents
**So that** I can review my order before checkout

**Acceptance Criteria:**
- View all cart items with images and prices
- Update quantities for each item
- Remove individual items
- See total cost including tax and shipping
- Cart persists across sessions

---

#### US-006: Save for Later (Wishlist)
**As a** customer
**I want to** add products to my wishlist
**So that** I can purchase them in the future

**Acceptance Criteria:**
- Heart icon on each product
- One-click add to wishlist
- View all wishlisted items
- Move wishlist items to cart

---

#### US-007: Complete Checkout
**As a** customer
**I want to** enter shipping information and payment method
**So that** I can complete my purchase

**Acceptance Criteria:**
- Guided checkout process
- Form validation for required fields
- Multiple payment method options
- Order review before submission
- Order confirmation with order number

---

#### US-008: Pay with KHQR
**As a** Cambodian customer
**I want to** pay using KHQR/Bakong
**So that** I can use my local mobile banking app

**Acceptance Criteria:**
- KHQR option available at checkout
- QR code generated with order amount
- Instructions for scanning and paying
- Payment verification
- Order confirmed after successful payment

---

#### US-009: Create Account
**As a** visitor
**I want to** register for an account
**So that** I can save my information and track orders

**Acceptance Criteria:**
- Registration form with name, email, password
- Email verification required
- Welcome email sent
- Automatic login after registration
- Profile accessible after verification

---

#### US-010: Manage Profile
**As a** customer
**I want to** update my account information
**So that** my details stay current

**Acceptance Criteria:**
- Edit name and email address
- Change password securely
- Delete account option
- Changes saved immediately
- Confirmation messages displayed

---

### 8.2 Administrator User Stories

#### US-011: Manage Categories
**As an** administrator
**I want to** create, edit, and delete product categories
**So that** I can organize the product catalog

**Acceptance Criteria:**
- View list of all categories
- Add new category with name
- Edit existing category names
- Delete categories (with confirmation)
- Changes reflect immediately on site

---

#### US-012: Manage Products (Planned)
**As an** administrator
**I want to** add, edit, and remove products
**So that** I can maintain accurate inventory

**Acceptance Criteria:**
- Create product with all details
- Upload product images
- Set pricing and stock levels
- Edit product information
- Disable/enable products
- Delete products with confirmation

---

#### US-013: View Orders (Planned)
**As an** administrator
**I want to** view all customer orders
**So that** I can fulfill them and track sales

**Acceptance Criteria:**
- List all orders with filters
- View order details (customer, items, payment)
- Update order status
- Search orders by number or customer
- Export order data

---

### 8.3 Use Case Diagrams

#### UC-001: Complete Purchase Flow

```
Customer → View Products → Filter/Search → Select Product
    → View Details → Add to Cart → View Cart → Update Quantities
    → Proceed to Checkout → Enter Shipping Info → Select Payment
    → Confirm Order → Receive Confirmation
```

#### UC-002: Admin Product Management

```
Admin → Login → Admin Dashboard → Product Management
    → Create Product / Edit Product / Delete Product
    → Save Changes → View Updated Catalog
```

---

## 9. System Architecture

### 9.1 Technology Stack

#### Backend
- **Framework:** Laravel 12.0
- **Language:** PHP 8.2+
- **Architecture:** MVC (Model-View-Controller)
- **ORM:** Eloquent
- **Authentication:** Laravel Breeze
- **API:** RESTful design

#### Frontend
- **Template Engine:** Blade
- **CSS Framework:** Tailwind CSS 3.x
- **JavaScript:** Vanilla JS + AJAX
- **Build Tool:** Vite 5.x
- **Icons:** Font Awesome

#### Database
- **Primary:** PostgreSQL 13+
- **Alternative:** MySQL 8.0+
- **Database Name:** k2_computer
- **Migrations:** Laravel migration system

#### Deployment
- **Platform:** Vercel (Serverless)
- **Alternative:** Traditional hosting (Apache/Nginx + PHP-FPM)
- **Version Control:** Git
- **Repository:** GitHub/GitLab

#### Third-Party Services
- **Payment Gateway:** KHQR (khqr-gateway/bakong-khqr-php)
- **Email:** SMTP (configurable)
- **Caching:** Redis (optional), File cache (default)
- **Queue:** Database queue driver

### 9.2 Application Architecture

```
┌─────────────────────────────────────────────────────┐
│                  Presentation Layer                  │
│  (Blade Templates, JavaScript, Tailwind CSS)         │
└─────────────────────────────────────────────────────┘
                         ↓
┌─────────────────────────────────────────────────────┐
│                  Application Layer                   │
│  ┌─────────────────────────────────────────────┐   │
│  │  Controllers (Product, Cart, Checkout, etc) │   │
│  └─────────────────────────────────────────────┘   │
│  ┌─────────────────────────────────────────────┐   │
│  │  Middleware (Auth, CSRF, Throttle)          │   │
│  └─────────────────────────────────────────────┘   │
│  ┌─────────────────────────────────────────────┐   │
│  │  Services (Payment, Email, Cart)            │   │
│  └─────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────┘
                         ↓
┌─────────────────────────────────────────────────────┐
│                   Data Access Layer                  │
│  ┌─────────────────────────────────────────────┐   │
│  │  Models (User, Product, Category, Order)    │   │
│  └─────────────────────────────────────────────┘   │
│  ┌─────────────────────────────────────────────┐   │
│  │  Eloquent ORM                               │   │
│  └─────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────┘
                         ↓
┌─────────────────────────────────────────────────────┐
│                   Database Layer                     │
│              PostgreSQL / MySQL                      │
└─────────────────────────────────────────────────────┘

                    External APIs
            ┌──────────────────────────┐
            │  KHQR/Bakong Payment API │
            └──────────────────────────┘
```

### 9.3 Directory Structure

```
e-ecommerce-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   └── CategoryController.php
│   │   │   ├── Api/
│   │   │   │   └── CategoryController.php
│   │   │   ├── Auth/ (7 controllers)
│   │   │   ├── CartController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── HomeController.php
│   │   │   ├── KhqrController.php
│   │   │   ├── ProductController.php
│   │   │   ├── ProfileController.php
│   │   │   └── WishlistController.php
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   │   ├── Category.php
│   │   └── User.php
│   └── Providers/
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── resources/
│   ├── views/ (Blade templates)
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php
│   ├── auth.php
│   └── api.php (to be created)
├── public/
│   └── images/products/
├── config/
├── storage/
└── tests/
```

### 9.4 Database Schema

#### Entity Relationship Diagram (ERD)

```
┌─────────────────┐
│     users       │
├─────────────────┤
│ id (PK)         │
│ name            │
│ email (unique)  │
│ password        │
│ type (enum)     │
│ email_verified  │
│ timestamps      │
└─────────────────┘
         │
         │ 1:N
         ↓
┌─────────────────┐       ┌─────────────────┐
│    orders       │       │   categories    │
├─────────────────┤       ├─────────────────┤
│ id (PK)         │       │ id (PK)         │
│ user_id (FK)    │       │ name            │
│ order_number    │       │ description     │
│ status          │       │ timestamps      │
│ total_amount    │       └─────────────────┘
│ payment_method  │                │
│ timestamps      │                │ 1:N
└─────────────────┘                ↓
         │                 ┌─────────────────┐
         │ 1:N             │   products      │
         ↓                 ├─────────────────┤
┌─────────────────┐       │ id (PK)         │
│  order_items    │ N:1   │ category_id(FK) │
├─────────────────┤←──────│ name            │
│ id (PK)         │       │ description     │
│ order_id (FK)   │       │ price           │
│ product_id (FK) │       │ original_price  │
│ quantity        │       │ discount        │
│ price           │       │ brand           │
│ timestamps      │       │ sku             │
└─────────────────┘       │ stock_qty       │
                          │ rating          │
                          │ review_count    │
┌─────────────────┐       │ image_url       │
│   payments      │       │ specifications  │
├─────────────────┤       │ timestamps      │
│ id (PK)         │       └─────────────────┘
│ order_id (FK)   │
│ payment_method  │       ┌─────────────────┐
│ amount          │       │   carts         │
│ status          │       ├─────────────────┤
│ transaction_id  │       │ id (PK)         │
│ timestamps      │       │ user_id (FK)    │
└─────────────────┘       │ product_id (FK) │
                          │ quantity        │
┌─────────────────┐       │ timestamps      │
│   branches      │       └─────────────────┘
├─────────────────┤
│ id (PK)         │       ┌─────────────────┐
│ name            │       │   wishlists     │
│ phone           │       ├─────────────────┤
│ logo            │       │ id (PK)         │
│ remark          │       │ user_id (FK)    │
│ timestamps      │       │ product_id (FK) │
└─────────────────┘       │ timestamps      │
                          └─────────────────┘
```

---

## 10. Data Requirements

### 10.1 Data Entities

#### User Data
- **Storage:** Database (users table)
- **Retention:** Until account deletion
- **Backup:** Daily
- **Fields:** ID, name, email, password (hashed), type, verification status, timestamps

#### Product Data
- **Storage:** Database (products table)
- **Retention:** Indefinite (until deleted by admin)
- **Backup:** Daily
- **Fields:** ID, name, description, price, discount, brand, category, SKU, stock, rating, images, specifications

#### Order Data
- **Storage:** Database (orders, order_items tables)
- **Retention:** 7 years (regulatory compliance)
- **Backup:** Daily with long-term archival
- **Fields:** Order number, customer info, items, quantities, prices, totals, payment method, status, timestamps

#### Payment Data
- **Storage:** Database (payments table)
- **Retention:** 7 years
- **Backup:** Daily with encryption
- **Fields:** Payment ID, order reference, method, amount, status, transaction ID, timestamps
- **Security:** PCI-DSS compliance for card data (when implemented)

#### Session Data
- **Storage:** Database (sessions table) or Cache
- **Retention:** 120 minutes (session lifetime)
- **Fields:** Session ID, user ID, IP address, user agent, payload

#### Cart Data (Current)
- **Storage:** Session (temporary)
- **Retention:** Until session expires or checkout
- **Future:** Database for logged-in users

#### Wishlist Data (Current)
- **Storage:** Session (temporary)
- **Retention:** Until session expires
- **Future:** Database for logged-in users

### 10.2 Data Validation Rules

#### User Input Validation
- **Email:** Valid email format, unique in database, max 255 characters
- **Password:** Minimum 8 characters, requires uppercase, lowercase, number
- **Name:** Required, string, max 255 characters
- **Phone:** Numeric, 8-12 digits
- **Address:** Required for checkout, max 500 characters
- **Zip Code:** 4-6 digits

#### Product Data Validation
- **Name:** Required, unique, max 255 characters
- **Price:** Numeric, positive, 2 decimal places
- **Quantity:** Integer, non-negative
- **SKU:** Unique, alphanumeric, max 50 characters
- **Category:** Must exist in categories table

#### Order Data Validation
- **Order Number:** Unique, format K2-XXXXXX
- **Total Amount:** Positive, matches calculation
- **Payment Method:** Enum (card, khqr, cod)

### 10.3 Data Migration

#### Current State
- Users: Database-driven ✓
- Categories: Database-driven ✓
- Products: Hardcoded in controllers ✗
- Orders: Session-based ✗
- Carts: Session-based ✗
- Wishlists: Session-based ✗

#### Required Migrations
1. **Product Migration:** Transfer hardcoded products to database with seeders
2. **Cart Migration:** Implement database cart storage for logged-in users
3. **Order Migration:** Store completed orders in database
4. **Wishlist Migration:** Database storage for logged-in users

---

## 11. Integration Requirements

### 11.1 Payment Gateway Integration

#### KHQR/Bakong Integration
**Status:** Partially Implemented
**Library:** khqr-gateway/bakong-khqr-php v1.0
**Endpoints:**
- QR Code Generation: `/generate-qrcode`
- Transaction Verification: `/check-transaction`

**Configuration Required:**
- Merchant ID
- Merchant Name: BUNSONG EAR
- Merchant City: PHNOM PENH
- Account: ear_bunsong@aclb
- JWT Token for API authentication
- Currency: KHR (Cambodian Riel)

**Integration Points:**
1. Generate QR code with order amount at checkout
2. Display QR code to customer
3. Poll transaction status
4. Verify payment via MD5 hash
5. Update order status upon confirmation
6. Handle payment failures and retries

#### Card Payment Integration (Planned)
**Status:** Not Implemented
**Options:**
- Stripe
- PayPal
- ABA PayWay (Cambodia-specific)

**Requirements:**
- PCI-DSS compliance
- SSL certificate
- Secure token handling
- 3D Secure support
- Webhook configuration for payment status

### 11.2 Email Service Integration

**Status:** Configured, not fully implemented
**Protocol:** SMTP
**Use Cases:**
- User registration confirmation
- Email verification
- Password reset
- Order confirmation
- Shipping notifications (future)

**Configuration:**
- MAIL_MAILER=smtp
- MAIL_HOST (e.g., smtp.gmail.com)
- MAIL_PORT (587 for TLS)
- MAIL_USERNAME
- MAIL_PASSWORD
- MAIL_ENCRYPTION=tls
- MAIL_FROM_ADDRESS
- MAIL_FROM_NAME="K2 Computer"

### 11.3 Storage Integration (Future)

**Purpose:** Product images, user uploads
**Options:**
- Local storage (current)
- AWS S3
- DigitalOcean Spaces
- Cloudinary

**Requirements:**
- Image optimization
- CDN for fast delivery
- Backup strategy
- Access control

### 11.4 Analytics Integration (Future)

**Options:**
- Google Analytics
- Facebook Pixel
- Custom analytics dashboard

**Metrics:**
- Page views
- Conversion rate
- Cart abandonment
- Popular products
- Revenue tracking

---

## 12. User Interface Requirements

### 12.1 Design Principles

1. **Simplicity:** Clean, uncluttered interface
2. **Consistency:** Uniform design across all pages
3. **Responsive:** Adapt to all screen sizes
4. **Accessibility:** WCAG 2.1 AA compliance
5. **Performance:** Fast load times, optimized images

### 12.2 Layout Requirements

#### Header
- Logo/brand name (top left)
- Search bar (center)
- Cart icon with counter (top right)
- User menu (login/register or profile)
- Navigation menu (categories, deals, contact)

#### Footer
- Company information
- Social media links
- Contact details
- Links (About, Privacy, Terms)
- Newsletter signup (future)

#### Product Grid
- Responsive grid (4 columns desktop, 2 mobile)
- Product card: image, name, price, rating, add to cart
- Hover effects
- Quick view option (future)

#### Product Detail Page
- Large product image gallery
- Product title and brand
- Price with original price strikethrough
- Rating and review count
- Add to cart button
- Add to wishlist button
- Quantity selector
- Specifications table
- Product description
- Related products (future)

#### Shopping Cart
- Item list with images
- Quantity adjustment (+/- buttons)
- Remove item button
- Order summary sidebar (subtotal, shipping, tax, total)
- Proceed to checkout button
- Continue shopping link

#### Checkout Page
- Multi-step progress indicator
- Form fields grouped logically
- Order summary sidebar
- Payment method selection with icons
- Place order button
- Security badges

### 12.3 Color Scheme

**Primary Colors:**
- Primary: Blue (#3B82F6 or custom brand color)
- Secondary: Gray (#6B7280)
- Accent: Orange/Red for CTAs (#F59E0B)

**Status Colors:**
- Success: Green (#10B981)
- Error: Red (#EF4444)
- Warning: Yellow (#F59E0B)
- Info: Blue (#3B82F6)

**Neutrals:**
- Background: White (#FFFFFF)
- Text: Dark Gray (#111827)
- Borders: Light Gray (#E5E7EB)

### 12.4 Typography

**Font Family:** System font stack (Tailwind default)
- `font-sans`: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif

**Font Sizes (Tailwind):**
- Headings: text-3xl (H1), text-2xl (H2), text-xl (H3)
- Body: text-base (16px)
- Small: text-sm (14px)
- Extra Small: text-xs (12px)

### 12.5 Interactive Elements

#### Buttons
- **Primary:** Blue background, white text, rounded corners
- **Secondary:** White background, blue border, blue text
- **Danger:** Red background, white text (delete actions)
- **Disabled:** Gray background, gray text, cursor not-allowed

#### Forms
- Labels above inputs
- Placeholder text in lighter gray
- Focus state: blue border
- Error state: red border with error message below
- Success state: green checkmark icon

#### Icons
- Font Awesome (solid/regular styles)
- Consistent size (16px, 20px, 24px)
- Color matches text or brand color

#### Notifications (Toasts)
- Top-right position
- Auto-dismiss after 3-5 seconds
- Close button
- Icon indicating type (success, error, info)

### 12.6 Responsive Breakpoints

- **Mobile:** < 640px (sm)
- **Tablet:** 640px - 1024px (sm to lg)
- **Desktop:** > 1024px (lg+)
- **Wide Desktop:** > 1280px (xl+)

### 12.7 Accessibility Requirements

- Semantic HTML elements
- Alt text for all images
- ARIA labels where needed
- Keyboard navigation support
- Focus indicators
- Sufficient color contrast (4.5:1 minimum)
- Screen reader compatibility

---

## 13. Security Requirements

### 13.1 Authentication Security

- Password hashing with bcrypt (cost 12)
- Email verification required
- Password reset via secure token (60-minute expiration)
- Session timeout after 120 minutes
- "Remember me" with secure cookie
- Account lockout after 5 failed login attempts (future)

### 13.2 Authorization Security

- Role-based access control (customer/admin)
- Admin routes protected with middleware
- Users can only access own data
- CSRF protection on all POST/PUT/DELETE requests
- API authentication with tokens (when implemented)

### 13.3 Data Security

- Passwords never stored in plain text
- Sensitive data encrypted at rest (payment info)
- HTTPS/TLS encryption in transit
- Database connection encryption
- Environment variables for secrets (.env)
- .env file excluded from version control

### 13.4 Application Security

- SQL injection prevention (Eloquent ORM)
- XSS prevention (Blade auto-escaping)
- CSRF token validation
- Input validation and sanitization
- File upload restrictions (type, size)
- Rate limiting on sensitive endpoints
- Security headers (CSP, X-Frame-Options, etc.)

### 13.5 Payment Security

- PCI-DSS compliance (for card payments)
- Payment data not stored directly
- Tokenization for card details
- Webhook signature verification
- Secure payment gateway communication (HTTPS + JWT)

### 13.6 Session Security

- Secure session cookies (HttpOnly, SameSite)
- Session ID regeneration after login
- Session data encrypted
- Session cleanup for expired sessions

### 13.7 Monitoring & Logging

- Failed login attempt logging
- Payment transaction logging
- Error and exception logging
- Security event logging (suspicious activity)
- Regular security audits
- Dependency vulnerability scanning

---

## 14. Assumptions & Constraints

### 14.1 Assumptions

1. **User Base:**
   - Target users have internet access and smartphone/computer
   - Users familiar with online shopping
   - Primary language: English (Khmer support future)

2. **Technical:**
   - Hosting provider supports PHP 8.2+
   - PostgreSQL or MySQL database available
   - SMTP server accessible for email
   - SSL certificate available for production

3. **Business:**
   - Product inventory managed externally initially
   - Customer service handled via email/phone
   - Physical fulfillment process exists
   - Product images provided by supplier or created internally

4. **Payment:**
   - KHQR credentials obtainable for production
   - Customer base familiar with KHQR/Bakong
   - COD acceptable for local deliveries

5. **Legal:**
   - Business registered and licensed
   - Tax collection requirements understood
   - Privacy policy and terms of service prepared
   - Compliance with Cambodian e-commerce regulations

### 14.2 Constraints

#### Technical Constraints
1. **Platform Limitation:** Vercel serverless may have cold start times
2. **Database:** Current implementation uses session storage (limited scalability)
3. **File Storage:** Local storage not suitable for multiple servers
4. **Email Limits:** SMTP provider may have sending limits
5. **No Real-Time:** WebSocket/real-time features not supported in current architecture

#### Budget Constraints
1. Free/low-cost hosting (Vercel free tier)
2. Limited budget for paid services (payment gateways, CDN)
3. Development time constraints

#### Resource Constraints
1. Single developer or small team
2. Limited QA resources
3. No dedicated DevOps engineer

#### Time Constraints
1. MVP development prioritized over full features
2. Some features deferred to Phase 2

#### Regulatory Constraints
1. Must comply with Cambodian data protection laws
2. Tax calculation must match local requirements
3. Payment processing must follow financial regulations

#### Business Constraints
1. Manual order fulfillment initially
2. Limited product catalog (starting with ~10-50 products)
3. Single-branch operation initially

---

## 15. Dependencies

### 15.1 Technical Dependencies

#### External Services
1. **KHQR/Bakong Payment Gateway**
   - Service availability required for KHQR payments
   - API access with valid credentials
   - Stable internet connection

2. **Email Service Provider**
   - SMTP server (Gmail, SendGrid, Mailgun, etc.)
   - Valid credentials and configuration
   - Sender reputation for deliverability

3. **Hosting Provider**
   - Vercel or alternative hosting
   - Database hosting (if separate)
   - SSL certificate provision

4. **Domain Name**
   - Registered domain
   - DNS configuration

#### Software Dependencies (PHP/Composer)
- PHP 8.2 or higher
- Laravel Framework 12.0
- Laravel Breeze 2.3
- KHQR Gateway Library 1.0
- Pest Testing Framework 3.8
- Additional packages per composer.json

#### Frontend Dependencies (Node/npm)
- Vite 5.x
- Tailwind CSS 3.x
- Font Awesome
- Additional packages per package.json

### 15.2 Data Dependencies

1. **Product Data:** Require product information (names, descriptions, images, prices, specs)
2. **Category Data:** Product categorization structure
3. **Test Data:** Sample products and users for development/testing

### 15.3 Business Dependencies

1. **Product Suppliers:** Inventory availability and pricing
2. **Shipping Partner:** Delivery logistics and costs
3. **Payment Gateway Approval:** KHQR merchant account approval
4. **Legal Documents:** Terms of service, privacy policy, refund policy

### 15.4 Team Dependencies

1. **Developer:** Application development and maintenance
2. **Designer:** UI/UX design (or use templates)
3. **Content Creator:** Product images, descriptions, marketing content
4. **QA Tester:** Testing and bug reporting (or developer performs)
5. **Business Owner:** Requirements, approvals, business logic

---

## 16. Success Criteria & KPIs

### 16.1 Project Success Criteria

#### Technical Success
- [ ] All functional requirements implemented
- [ ] Application passes security audit
- [ ] Page load time < 3 seconds
- [ ] 99.5% uptime in production
- [ ] Zero critical bugs in production
- [ ] Test coverage > 70%

#### Business Success
- [ ] Successful product launch
- [ ] Able to process orders end-to-end
- [ ] At least 3 payment methods functional
- [ ] Admin can manage catalog independently
- [ ] Positive user feedback (> 4.0/5.0 average)

#### User Success
- [ ] Users can complete purchase in < 10 minutes
- [ ] Cart abandonment rate < 70%
- [ ] < 5% checkout error rate
- [ ] Mobile users can complete purchase successfully

### 16.2 Key Performance Indicators (KPIs)

#### Sales Metrics
1. **Conversion Rate**
   - Target: 2-5%
   - Calculation: (Orders / Visitors) × 100

2. **Average Order Value (AOV)**
   - Target: $200+
   - Calculation: Total Revenue / Number of Orders

3. **Revenue**
   - Target: Month-over-month growth
   - Measurement: Total sales value

4. **Cart Abandonment Rate**
   - Target: < 70%
   - Calculation: (Carts Created - Orders) / Carts Created × 100

#### User Engagement Metrics
5. **Active Users**
   - Daily Active Users (DAU)
   - Monthly Active Users (MAU)
   - Target: Steady growth

6. **Bounce Rate**
   - Target: < 50%
   - Measurement: Single-page visits

7. **Time on Site**
   - Target: > 3 minutes average
   - Measurement: Session duration

8. **Pages per Session**
   - Target: > 4 pages
   - Measurement: Average page views

#### Product Metrics
9. **Product Views**
   - Most viewed products
   - View-to-cart ratio

10. **Search Effectiveness**
    - Search result click-through rate
    - Zero-result searches (target < 10%)

#### Technical Metrics
11. **Page Load Time**
    - Target: < 3 seconds
    - Tool: Google Lighthouse

12. **Error Rate**
    - Target: < 1%
    - Measurement: Failed requests / Total requests

13. **Uptime**
    - Target: 99.5%
    - Tool: Uptime monitoring service

14. **API Response Time**
    - Target: < 200ms
    - Measurement: Average API latency

#### Customer Satisfaction
15. **Customer Support Tickets**
    - Measurement: Number and resolution time
    - Target: < 24-hour response time

16. **User Ratings**
    - Target: > 4.0/5.0 average
    - Measurement: App store or feedback ratings

17. **Net Promoter Score (NPS)**
    - Target: > 50
    - Measurement: Survey responses

#### Marketing Metrics
18. **Customer Acquisition Cost (CAC)**
    - Calculation: Marketing Spend / New Customers
    - Target: < 20% of AOV

19. **Return on Ad Spend (ROAS)**
    - Target: > 3:1
    - Calculation: Revenue / Ad Spend

20. **Email Open Rate**
    - Target: > 20%
    - Measurement: Email campaign analytics

---

## 17. Implementation Roadmap

### 17.1 Phase 1: MVP Completion (Current Priority)

**Timeline:** 4-6 weeks
**Status:** 60% Complete

#### Tasks Remaining:
1. **Database Integration (2 weeks)**
   - [ ] Create Product model with relationships
   - [ ] Create Order and OrderItem models
   - [ ] Implement product seeder with sample data
   - [ ] Replace hardcoded products with database queries
   - [ ] Migrate cart to database for logged-in users
   - [ ] Implement persistent wishlist in database

2. **KHQR Payment Completion (1 week)**
   - [ ] Test QR code generation with production credentials
   - [ ] Implement transaction verification polling
   - [ ] Handle payment success/failure scenarios
   - [ ] Store payment records in database
   - [ ] Send order confirmation emails

3. **Order Management (1 week)**
   - [ ] Persist orders to database after checkout
   - [ ] Display order history in user profile
   - [ ] Create admin order list view
   - [ ] Implement order status updates
   - [ ] Add order search and filtering

4. **Admin Dashboard (1 week)**
   - [ ] Create admin dashboard layout
   - [ ] Implement product CRUD operations
   - [ ] Add product image upload functionality
   - [ ] Create order management interface
   - [ ] Add role-based middleware for admin routes

5. **Testing & Bug Fixes (1 week)**
   - [ ] Write unit tests for models
   - [ ] Write feature tests for controllers
   - [ ] Test all user flows end-to-end
   - [ ] Fix identified bugs
   - [ ] Cross-browser testing

**Deliverables:**
- Fully functional e-commerce platform
- Database-driven product catalog
- Working KHQR payment integration
- Admin panel for management
- Order persistence and history

---

### 17.2 Phase 2: Enhancement & Optimization (2-3 months)

**Timeline:** Months 2-3 after MVP launch

#### Features:
1. **Payment Methods**
   - Card payment integration (Stripe/ABA PayWay)
   - Cash on Delivery implementation
   - Payment receipt generation

2. **User Experience**
   - Product reviews and ratings
   - Related products recommendations
   - Recently viewed products
   - Advanced search with autocomplete
   - Product quick view modal

3. **Email Notifications**
   - Order confirmation emails
   - Shipping notification emails
   - Password reset emails (complete)
   - Newsletter subscription (optional)

4. **Admin Features**
   - Sales dashboard with charts
   - Inventory management
   - Low stock alerts
   - Bulk product import (CSV)
   - Discount/coupon management

5. **Performance Optimization**
   - Image optimization and lazy loading
   - Database query optimization
   - Implement caching (Redis)
   - CDN for static assets
   - Bundle size reduction

6. **SEO & Marketing**
   - SEO-friendly URLs
   - Meta tags and Open Graph
   - Sitemap generation
   - Google Analytics integration
   - Facebook Pixel integration

**Deliverables:**
- Enhanced user experience
- Multiple payment options
- Comprehensive admin tools
- Improved performance
- Marketing analytics

---

### 17.3 Phase 3: Scale & Advanced Features (3-6 months)

**Timeline:** Months 4-6 after MVP launch

#### Features:
1. **Multi-language Support**
   - English and Khmer translations
   - Language switcher
   - Localized content

2. **Mobile Application**
   - React Native or Flutter app
   - API enhancement for mobile
   - Push notifications

3. **Advanced Features**
   - Wishlist sharing
   - Product comparison
   - Gift cards and vouchers
   - Loyalty points program
   - Saved addresses (address book)
   - Multiple shipping addresses

4. **Analytics & Reporting**
   - Custom admin reports
   - Sales trends analysis
   - Customer behavior analytics
   - Inventory forecasting
   - Export reports (PDF, Excel)

5. **Customer Service**
   - Live chat integration
   - FAQ/Help center
   - Ticket support system
   - Order tracking system
   - Return/refund management

6. **Marketing Automation**
   - Abandoned cart emails
   - Personalized product recommendations
   - Customer segmentation
   - Email marketing campaigns
   - Promotional banners

**Deliverables:**
- Multi-platform presence
- Advanced business intelligence
- Customer engagement tools
- Marketing automation
- Scalable infrastructure

---

### 17.4 Phase 4: Future Enhancements (6+ months)

**Long-term Vision:**
- Multi-vendor marketplace
- Subscription products
- AR product visualization
- AI-powered chatbot
- Voice search
- Social commerce integration
- B2B wholesale portal
- Franchise/branch management
- Advanced inventory with warehouse management
- International shipping

---

## 18. Risks & Mitigation

### 18.1 Technical Risks

#### RISK-001: Database Migration Complexity
**Probability:** Medium
**Impact:** High
**Description:** Migrating from hardcoded products to database may introduce bugs

**Mitigation:**
- Create comprehensive seeders with existing product data
- Implement tests before and after migration
- Use feature flags to toggle between hardcoded and database
- Gradual rollout with thorough testing

---

#### RISK-002: Payment Gateway Integration Issues
**Probability:** Medium
**Impact:** High
**Description:** KHQR API may have undocumented behaviors or downtime

**Mitigation:**
- Implement robust error handling
- Add fallback to COD when payment gateway unavailable
- Monitor payment gateway status
- Maintain detailed logging for troubleshooting
- Test with sandbox before production

---

#### RISK-003: Performance Degradation
**Probability:** Medium
**Impact:** Medium
**Description:** Application may slow down with increased traffic or data

**Mitigation:**
- Implement caching strategy (query, view, route)
- Database indexing on frequently queried fields
- Load testing before launch
- CDN for static assets
- Optimize database queries with eager loading

---

#### RISK-004: Security Vulnerabilities
**Probability:** Low
**Impact:** Critical
**Description:** Security breaches could expose customer data or payment info

**Mitigation:**
- Regular security audits
- Keep dependencies updated
- Follow OWASP top 10 guidelines
- Implement rate limiting
- Use Laravel's built-in security features
- Penetration testing before launch

---

#### RISK-005: Third-Party Service Downtime
**Probability:** Low
**Impact:** Medium
**Description:** Email service, payment gateway, or hosting downtime

**Mitigation:**
- Choose reliable service providers
- Implement graceful error handling
- Queue email sending for retry
- Monitor service status
- Have backup/alternative providers identified

---

### 18.2 Business Risks

#### RISK-006: Low User Adoption
**Probability:** Medium
**Impact:** High
**Description:** Customers may prefer physical store or competitors

**Mitigation:**
- Competitive pricing and promotions
- Marketing campaign before launch
- Easy-to-use interface
- Local payment methods (KHQR)
- Excellent customer service
- Gather and act on user feedback

---

#### RISK-007: Inventory Management Issues
**Probability:** Medium
**Impact:** Medium
**Description:** Overselling products or inaccurate stock levels

**Mitigation:**
- Real-time inventory tracking
- Low stock alerts for admins
- Automatic out-of-stock status
- Sync with physical inventory system
- Regular inventory audits

---

#### RISK-008: Payment Fraud
**Probability:** Low
**Impact:** High
**Description:** Fraudulent transactions or chargebacks

**Mitigation:**
- Address verification
- Fraud detection rules
- Manual review for large orders
- Secure payment processing
- Clear refund policy

---

#### RISK-009: Logistics and Fulfillment Issues
**Probability:** Medium
**Impact:** Medium
**Description:** Delays or errors in order delivery

**Mitigation:**
- Partner with reliable shipping companies
- Order tracking system
- Clear delivery timeframes
- Customer notifications at each stage
- Easy return/exchange process

---

### 18.3 Project Risks

#### RISK-010: Scope Creep
**Probability:** High
**Impact:** Medium
**Description:** Additional features requested during development

**Mitigation:**
- Clear project scope documentation (this BRD)
- Change request process
- Prioritization matrix
- Regular stakeholder communication
- Phase-based implementation

---

#### RISK-011: Resource Constraints
**Probability:** Medium
**Impact:** Medium
**Description:** Limited development time or team availability

**Mitigation:**
- Prioritize MVP features
- Use existing libraries and packages
- Realistic timeline estimation
- Regular progress reviews
- Defer non-critical features to later phases

---

#### RISK-012: Regulatory Compliance Issues
**Probability:** Low
**Impact:** High
**Description:** Non-compliance with Cambodian e-commerce or tax laws

**Mitigation:**
- Consult with legal advisor
- Research local regulations
- Implement required tax calculations
- Privacy policy and terms of service
- Business license and permits

---

## 19. Appendix

### 19.1 Glossary

| Term | Definition |
|------|------------|
| **AOV** | Average Order Value - average amount spent per order |
| **API** | Application Programming Interface |
| **BRD** | Business Requirements Document |
| **CRUD** | Create, Read, Update, Delete operations |
| **COD** | Cash on Delivery payment method |
| **CSRF** | Cross-Site Request Forgery security vulnerability |
| **JWT** | JSON Web Token for authentication |
| **KHQR** | Khmer Quick Response - Cambodian national payment system |
| **KPI** | Key Performance Indicator |
| **MVP** | Minimum Viable Product |
| **ORM** | Object-Relational Mapping (Eloquent in Laravel) |
| **PCI-DSS** | Payment Card Industry Data Security Standard |
| **SKU** | Stock Keeping Unit - unique product identifier |
| **SSL/TLS** | Secure Sockets Layer / Transport Layer Security |
| **XSS** | Cross-Site Scripting security vulnerability |

### 19.2 References

1. **Laravel Documentation:** https://laravel.com/docs/12.x
2. **Tailwind CSS Documentation:** https://tailwindcss.com/docs
3. **KHQR Bakong Documentation:** (Payment gateway provider)
4. **PCI-DSS Standards:** https://www.pcisecuritystandards.org
5. **WCAG Accessibility Guidelines:** https://www.w3.org/WAI/WCAG21/quickref/
6. **OWASP Top 10:** https://owasp.org/www-project-top-ten/

### 19.3 Document History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2025-11-04 | Development Team | Initial BRD creation |

### 19.4 Approval

This Business Requirements Document has been reviewed and approved by:

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Business Owner | _______________ | _______________ | _____ |
| Project Manager | _______________ | _______________ | _____ |
| Lead Developer | _______________ | _______________ | _____ |
| QA Lead | _______________ | _______________ | _____ |

---

## Contact Information

**Project Name:** K2 Computer E-Commerce Platform
**Document Owner:** [Your Name]
**Email:** [Your Email]
**Last Updated:** November 4, 2025
**Version:** 1.0

---

*This document is confidential and intended solely for the use of the individuals or entities to which it is addressed.*
