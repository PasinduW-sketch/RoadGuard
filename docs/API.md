# API Documentation - RoadGuard AI

## Base URL
```
http://localhost:8000/api
```

## Authentication
All protected endpoints require a valid Bearer token in the Authorization header:
```
Authorization: Bearer {token}
```

---

## Authentication Endpoints

### Register User
**POST** `/auth/register`

Request:
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "customer",
  "phone": "+1234567890"
}
```

Response (201):
```json
{
  "message": "User registered successfully",
  "user": {...},
  "access_token": "token_here",
  "token_type": "Bearer"
}
```

### Login
**POST** `/auth/login`

Request:
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

Response (200):
```json
{
  "message": "Login successful",
  "user": {...},
  "access_token": "token_here",
  "token_type": "Bearer"
}
```

### Get Current User
**GET** `/auth/me`

Response (200):
```json
{
  "user": {...}
}
```

### Logout
**POST** `/auth/logout`

Response (200):
```json
{
  "message": "Logged out successfully"
}
```

---

## Vehicle Endpoints

### List Vehicles
**GET** `/vehicles`

Response (200):
```json
{
  "message": "Vehicles retrieved successfully",
  "data": {
    "data": [...],
    "current_page": 1,
    "total": 5
  }
}
```

### Create Vehicle
**POST** `/vehicles`

Request:
```json
{
  "name": "My Car",
  "brand": "Toyota",
  "model": "Camry",
  "year": 2020,
  "registration_number": "ABC123",
  "fuel_type": "petrol",
  "mileage": 15000,
  "color": "Silver"
}
```

Response (201):
```json
{
  "message": "Vehicle created successfully",
  "data": {...}
}
```

### Get Vehicle
**GET** `/vehicles/{id}`

### Update Vehicle
**PUT** `/vehicles/{id}`

### Delete Vehicle
**DELETE** `/vehicles/{id}`

---

## Diagnosis Endpoints

### Get Diagnosis History
**GET** `/diagnoses`

### Create Diagnosis
**POST** `/diagnoses`

Request:
```json
{
  "vehicle_id": 1,
  "symptoms": "Engine won't start, making clicking sounds"
}
```

Response (201):
```json
{
  "message": "Diagnosis created successfully",
  "data": {
    "id": 1,
    "symptoms": "Engine won't start...",
    "ai_response": "...",
    "possible_causes": [...],
    "suggestions": [...],
    "severity_level": "high",
    "requires_professional": true
  }
}
```

### Get Diagnosis
**GET** `/diagnoses/{id}`

---

## Emergency Request Endpoints

### Get Emergency Requests
**GET** `/emergency-requests`

### Create SOS Request
**POST** `/emergency-requests`

Request:
```json
{
  "vehicle_id": 1,
  "issue_title": "Flat Tire",
  "issue_description": "Front left tire is punctured",
  "latitude": 40.7128,
  "longitude": -74.0060,
  "location_address": "Times Square, NY"
}
```

Response (201):
```json
{
  "message": "Emergency request created successfully",
  "data": {
    "id": 1,
    "ticket_number": "RG20240603123456ABCD",
    "status": "pending",
    "latitude": 40.7128,
    "longitude": -74.0060
  }
}
```

### Update Emergency Request Status
**PUT** `/emergency-requests/{id}`

Request:
```json
{
  "status": "accepted",
  "completion_notes": "Service completed"
}
```

### Get Nearby Providers
**GET** `/emergency-requests/nearby/providers`

Query Parameters:
- `latitude` (required): float
- `longitude` (required): float
- `radius` (optional): integer (default: 10 km)

Response (200):
```json
{
  "message": "Nearby providers retrieved successfully",
  "data": [...]
}
```

---

## Subscription Endpoints

### Get Subscription Plans
**GET** `/subscriptions/plans`

Response (200):
```json
{
  "message": "Subscription plans retrieved successfully",
  "data": [
    {
      "id": "free",
      "name": "Free Plan",
      "price": 0,
      "features": {...}
    },
    ...
  ]
}
```

### Get Current Subscription
**GET** `/subscriptions/current`

### Purchase Subscription
**POST** `/subscriptions/purchase`

Request:
```json
{
  "plan_name": "premium",
  "billing_period": "monthly"
}
```

### Get Subscription Usage
**GET** `/subscriptions/usage`

### Cancel Subscription
**POST** `/subscriptions/cancel`

---

## Review Endpoints

### Get Provider Reviews
**GET** `/reviews?provider_id=1`

### Create Review
**POST** `/reviews`

Request:
```json
{
  "provider_id": 1,
  "emergency_request_id": 1,
  "rating": 5,
  "comment": "Excellent service!",
  "would_recommend": true
}
```

### Get User Review
**GET** `/reviews/my-review/{emergencyRequestId}`

---

## Error Responses

### Validation Error (422)
```json
{
  "errors": {
    "email": ["The email field is required."]
  }
}
```

### Not Found (404)
```json
{
  "message": "Resource not found"
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthorized"
}
```

### Server Error (500)
```json
{
  "message": "Internal server error"
}
```

---

## Rate Limiting
- 60 requests per minute for authenticated users
- 10 requests per minute for public endpoints

## Status Codes
- 200: OK
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 404: Not Found
- 422: Validation Error
- 500: Server Error
