# ShiftLog — Multi-Branch Attendance & Shift Management System

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)
![Tests](https://img.shields.io/badge/Tests-Passing-brightgreen?style=flat)
![License](https://img.shields.io/badge/License-MIT-blue?style=flat)

A production-style employee attendance and shift scheduling system built for multi-branch hospitality/retail businesses. Built to solve a real operational problem: hotel chains and retail businesses in Kenya often track staff shifts on paper or WhatsApp, leading to payroll disputes, unnoticed no-shows, and zero visibility for branch managers.

**Demo credentials:** `manager@shiftlog.test` / `password` (scoped to one branch) or ask for admin access to see the full system.

---

## Table of Contents

- [The Problem](#the-problem)
- [Key Features](#key-features)
- [Screenshots](#screenshots)
- [Architecture](#architecture)
- [Tech Stack](#tech-stack)
- [Authorization Model](#authorization-model)
- [Local Setup](#local-setup)
- [Testing](#testing)
- [Project Structure](#project-structure)
- [Roadmap](#roadmap)
- [Known Limitations](#known-limitations)
- [Author](#author)

---

## The Problem

A hotel chain with multiple branches (Westlands, CBD, Karen, Mombasa Road) needs to know, at a glance:

- Who is scheduled to work today, at which branch, on which shift
- Who showed up on time, who was late, and who was a no-show
- All of this **scoped correctly** — a manager at one branch should never see another branch's staff or payroll-relevant attendance data

ShiftLog solves this with a proper multi-tenant-style permission model, not just a shared login for everyone.

## Key Features

- **Role-based access control** — Admin, Branch Manager, and Staff roles with real enforcement via Laravel Policies (not just hidden UI buttons)
- **Branch-scoped data isolation** — a manager's queries are automatically filtered to their own branch at the database level; direct URL access to another branch returns a `403`, verified by automated tests
- **7-day attendance dashboard** — live view of shift assignments and attendance status (on time / late / absent) per branch
- **Realistic seeded dataset** — 4 branches, 16 departments, 500 employees, and 5,000+ historical attendance records for a demo that feels like a real, in-use system rather than an empty shell
- **Automated authorization tests** — 4 feature tests covering admin access, manager access, cross-branch denial, and guest redirection

## Screenshots

| Screenshot | What it shows |
|---|---|
| `Dashboard.png` | Logged-in dashboard after authentication |
| `Branches.png` | Branch list scoped to the logged-in user's role |
| `Attendance.png` | 7-day attendance table with status badges |
| `Test Manager Unauthorized Access Denied.png` | proof a manager is blocked from viewing another branch's data |

## Architecture

### System Overview

```
┌─────────────┐      HTTPS       ┌──────────────────┐
│   Browser   │ ───────────────▶ │  Laravel App       │
│ (Blade UI)  │ ◀─────────────── │  (Nginx + PHP-FPM)  │
└─────────────┘                  └─────────┬─────────┘
                                            │
                                            ▼
                                  ┌──────────────────┐
                                  │  MySQL Database     │
                                  │  branches, users,   │
                                  │  shifts, attendance │
                                  └──────────────────┘
```

### Data Model (ER Diagram)

```
Branch (1) ───< Department (1) ───< Employee (M) ───(belongsTo)── User (1)
Employee (1) ───< ShiftAssignment (M) >─── Shift (1)
ShiftAssignment (1) ───< AttendanceRecord (1)
```

**Why `shifts` and `shift_assignments` are separate tables:** a `Shift` is a *template* (e.g. "Morning, 7am–3pm, Karen Branch"). A `ShiftAssignment` is a specific employee assigned to that template on a specific date. Splitting these apart is what makes attendance-status logic (late/absent) clean — without it, every shift change would require duplicating time data across every employee record.

**Why a branch isn't a direct column on `users`:** a user's branch is always *derived* through `employee → department → branch`. This keeps branch membership tied to actual department assignment rather than a value that could drift out of sync if edited separately.

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 11 (PHP 8.2) |
| Frontend | Blade + Tailwind CSS (via Laravel Breeze) |
| Database | MySQL 8 |
| Auth | Laravel Breeze |
| Authorization | Laravel Policies |
| Testing | PHPUnit (Feature tests) |
| Local Dev | XAMPP |
| Deployment | Railway |

## Authorization Model

| Role | Can view | Can manage |
|---|---|---|
| **Admin** | All branches | Create/edit/delete branches |
| **Branch Manager** | Only their own branch | View-only for their branch's data |
| **Staff** | Not yet exposed in UI (planned) | — |
| **Guest** | Nothing — redirected to `/login` | — |

Enforcement happens in `app/Policies/BranchPolicy.php` and is called explicitly in the controller via `$this->authorize('view', $branch)` — not via manually scattered `if` checks in Blade templates. This is verified by 4 automated feature tests in `tests/Feature/BranchPolicyTest.php`, covering the admin-bypass path, the manager-allow path, the manager-deny (cross-branch) path, and the guest-redirect path.

## Local Setup

```bash
git clone https://github.com/Night-ryder254/shiftlog.git
cd shiftlog

composer install
npm install

cp .env.example .env
php artisan key:generate

# Set your DB credentials in .env, then:
php artisan migrate:fresh --seed

npm run build
php artisan serve
```

Visit `http://127.0.0.1:8000/register` to create an account, then promote yourself via Tinker:

```bash
php artisan tinker
```
```php
$user = App\Models\User::where('email', 'your@email.com')->first();
$department = App\Models\Department::first();
App\Models\Employee::create([
    'user_id' => $user->id,
    'department_id' => $department->id,
    'role' => 'admin',
]);
```

## Testing

```bash
php artisan test
```

Current coverage: authorization boundaries (`BranchPolicyTest`) — admin access, manager access, cross-branch denial, guest redirection. More coverage (CRUD operations, attendance logic) planned — see [Roadmap](#roadmap).

## Project Structure

```
app/
├── Http/Controllers/BranchController.php   # Branch listing + attendance dashboard
├── Models/                                  # Branch, Department, Employee, Shift,
│                                             # ShiftAssignment, AttendanceRecord, User
├── Policies/BranchPolicy.php                # Authorization rules
database/
├── migrations/                              # Schema definitions
├── factories/                               # Realistic fake data generators
├── seeders/DatabaseSeeder.php               # Orchestrates seeding in dependency order
resources/views/branches/                    # index.blade.php, show.blade.php
tests/Feature/BranchPolicyTest.php           # Authorization test suite
```

## Roadmap

- [ ] Staff self-service clock-in/clock-out flow
- [ ] CSV export of attendance reports
- [ ] Shift creation/editing UI for managers
- [ ] Employee CRUD with department reassignment
- [ ] Feature tests for Shift and Employee resources
- [ ] Email notifications for repeated lateness/no-shows

## Known Limitations

- Staff role exists in the data model but has no dedicated UI yet — only Admin and Manager views are built
- Clock-in/clock-out is currently only seeded data, not a live-action feature yet
- No CSV export implemented yet (listed as a business requirement, planned for next iteration)

## Author

**Nigel Matekwa Alufwani**
BSc Information Technology, KCA University · AWS Certified Cloud Practitioner
[GitHub](https://github.com/Night-ryder254) · nigelmatekwa@gmail.com