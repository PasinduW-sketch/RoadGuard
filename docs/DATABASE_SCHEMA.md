# Database Schema - ER Diagram

## Entity Relationships

```
Users (1) ----< (many) Vehicles
Users (1) ----< (many) Diagnoses
Users (1) ----< (many) EmergencyRequests
Users (1) ----< (many) Subscriptions
Users (1) ----< (many) Payments
Users (1) ----< (many) Notifications
Users (1) ----< (many) Reviews
Users (1) ----> (1) Providers

Vehicles (1) ----< (many) Diagnoses
Vehicles (1) ----< (many) EmergencyRequests

Providers (1) ----< (many) EmergencyRequests
Providers (1) ----< (many) Reviews

EmergencyRequests (1) ----< (many) Reviews

Subscriptions (1) ----< (many) Payments
```

## Table Structure

### users
- id (PK)
- name
- email (UNIQUE)
- password
- phone
- role (customer, provider, admin)
- avatar
- address
- latitude
- longitude
- is_active
- is_verified
- email_verified_at
- last_login_at
- timestamps
- soft deletes

### vehicles
- id (PK)
- user_id (FK -> users)
- name
- brand
- model
- year
- registration_number (UNIQUE)
- fuel_type
- mileage
- description
- color
- vin (UNIQUE, nullable)
- is_active
- timestamps
- soft deletes

### providers
- id (PK)
- user_id (FK -> users)
- business_name
- service_type
- description
- license_number (UNIQUE)
- license_expiry
- registration_number
- business_phone
- latitude
- longitude
- business_address
- city
- state
- postal_code
- service_radius_km
- logo
- website
- rating
- total_jobs
- completed_jobs
- is_verified
- is_active
- verified_at
- timestamps
- soft deletes

### emergency_requests
- id (PK)
- user_id (FK -> users)
- vehicle_id (FK -> vehicles)
- provider_id (FK -> providers, nullable)
- ticket_number (UNIQUE)
- issue_title
- issue_description
- latitude
- longitude
- location_address
- status
- photos (JSON)
- accepted_at
- started_at
- completed_at
- completion_notes
- estimated_arrival_minutes
- is_emergency
- timestamps
- soft deletes

### diagnoses
- id (PK)
- user_id (FK -> users)
- vehicle_id (FK -> vehicles)
- symptoms
- ai_response
- possible_causes (JSON)
- suggestions (JSON)
- severity_level
- requires_professional
- ai_model
- tokens_used
- timestamps

### subscriptions
- id (PK)
- user_id (FK -> users)
- plan_name
- stripe_subscription_id (UNIQUE, nullable)
- price
- billing_period
- max_requests
- current_requests
- priority_dispatch
- advanced_diagnostics
- maintenance_reminders
- fleet_management
- max_vehicles
- is_active
- started_at
- expires_at
- renews_at
- timestamps
- soft deletes

### payments
- id (PK)
- user_id (FK -> users)
- subscription_id (FK -> subscriptions, nullable)
- stripe_payment_id (UNIQUE, nullable)
- amount
- currency
- status
- payment_method
- transaction_details (JSON)
- notes
- paid_at
- refunded_at
- timestamps

### notifications
- id (PK)
- user_id (FK -> users)
- type
- title
- message
- reference_type
- reference_id
- is_read
- is_sent_email
- read_at
- sent_at
- timestamps

### reviews
- id (PK)
- user_id (FK -> users)
- provider_id (FK -> providers)
- emergency_request_id (FK -> emergency_requests)
- rating (1-5)
- comment
- would_recommend
- service_quality (JSON)
- professionalism (JSON)
- value_for_money (JSON)
- is_verified_purchase
- timestamps
- soft deletes

## Indexes
- users: email
- vehicles: user_id, registration_number
- providers: user_id, service_type, is_verified
- emergency_requests: user_id, provider_id, status, ticket_number
- diagnoses: user_id, vehicle_id
- subscriptions: user_id, plan_name
- payments: user_id, stripe_payment_id, status
- notifications: user_id, is_read
- reviews: provider_id, user_id, rating
