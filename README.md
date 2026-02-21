FleetFlow
Operational Intelligence for Fleet & Logistics Management

FleetFlow is a role-based fleet operations system built during a hackathon to bring structure, visibility, and control to delivery fleet management.

Instead of manual logs and scattered information, FleetFlow provides a centralized digital platform to manage vehicles, drivers, trips, safety compliance, and operational costs in a single environment.

The Problem

In many fleet operations:

Vehicle availability is unclear

Dispatch decisions are made manually

Driver license expiry goes unnoticed

Maintenance is reactive, not planned

Fuel and repair costs are difficult to track

Managers lack real-time operational visibility

FleetFlow was built to solve these operational challenges through structured workflows and rule-based controls.

What Makes FleetFlow Unique

FleetFlow focuses on operational control, not just dashboards.

Key system rules:

Trips cannot be created if cargo exceeds vehicle capacity

Drivers with expired licenses cannot be assigned

Adding a maintenance record automatically marks the vehicle In Maintenance

Completing a trip makes the vehicle Available again

Expenses are linked to vehicles for cost tracking

This ensures the system actively prevents operational errors.

System Roles
Fleet Manager

Command Center Dashboard

Vehicle lifecycle management

Driver management

Maintenance control

Operational reports and analytics

Dispatcher

Create and assign trips

Live dispatch monitoring

View vehicle and driver availability

Capacity validation before dispatch

Estimated fuel planning

Safety Officer

Driver compliance monitoring

License expiry tracking

Risk identification (expired or expiring licenses)

Driver status management (On Duty / Off Duty / Suspended)

Financial Analyst

Operational cost overview

Fuel expense tracking

Maintenance cost monitoring

Expense entry and history

Vehicle-wise cost analysis

Cost per trip insights

Core Workflow

Vehicle registered → Status: Available

Dispatcher assigns vehicle and driver

System validates:

Cargo capacity

Driver license validity

Trip started → Vehicle status: On Trip

Trip completed → Vehicle status: Available

Maintenance logged → Vehicle status: In Maintenance

Expenses recorded → Cost updated per vehicle

Technology Stack

Frontend

HTML

CSS

JavaScript

PHP (UI structure with backend placeholders)

Database

MySQL (planned)

Version Control

Git & GitHub

Project Structure
FleetFlow/
│
├── manager/
│   ├── manager_dashboard.php
│   ├── manager_vehicle_registry.php
│   ├── manager_drivers.php
│   ├── manager_trip_dispatcher.php
│   ├── manager_maintenance.php
│   └── manager_reports.php
│
├── dispatcher/
├── safety/
├── finance/
├── assets/
└── database/

Each module follows a consistent layout and role-based functionality.

Key Focus Areas

Real-world logistics workflow simulation

Role-based operational control

Business-rule validation

Clean enterprise-style interface

Error prevention instead of manual monitoring

Built During

Hackathon Project — Designed and implemented under time constraints with focus on practical usability and real operational scenarios.

Team

Divy Choksi — Frontend

Dhruv Pandya — Backend

Jay Gajjar — Documentation

Naivedi Binjwa — Validation

Future Enhancements

Full database integration

Real-time fleet tracking

Automated maintenance alerts

Advanced cost analytics

Smart dispatch optimization

Project Vision

FleetFlow is designed as a decision-support and operational control system that helps organizations manage fleet operations efficiently, safely, and transparently.

video : https://drive.google.com/file/d/1LDM6pMxUKmXcCJ8a9DzONN11AwtmQP2d/view?usp=sharing
