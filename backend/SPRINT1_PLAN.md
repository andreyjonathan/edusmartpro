[SENDER_ID: 18] | [RECIPIENT_ID: 1] | [STATUS: COMPLETED]

# Sprint 1 Task Breakdown & Execution Plan — EdusmartPro Backend

**PM Signature (ID: 18 → Developer ID: 15):** Follows mandatory PM checklist format with task IDs annotated @ID_15.

- [T1.1] Install Filament v3 @ID_15 — `composer require filament/filament:"^3.2" -W` then `php artisan filament:install --panels`; panel created at `admin` path, login works
- [T1.2] Configure Panel @ID_15 — Edit `app/Providers/Filament/AdminPanelProvider.php` — set branding, colors, navigation groups; custom brand, sidebar groups visible
- [T1.3] Install Spatie Permission Plugin @ID_15 — `composer require filament/spatie-laravel-permission-plugin:"^3.2"` then publish config; Role/Permission resources available in panel
- [T1.4] Setup Filament Auth @ID_15 — Ensure `User` model uses `HasRoles`, configure guard, test login as admin at `/admin/login`
- [T1.5] Theming & RTL @ID_15 — Configure Tailwind theme, Indonesian locale, dark/light mode, Indonesian labels
- [T2.1] UserResource @ID_15 — CRUD for User with roles (BelongsToMany); table searchable/sortable, form with proper field types, validation via StoreUserRequest, export CSV, bulk delete, status filter Active/Inactive
- [T2.2] StudentResource @ID_15 — CRUD for Student; fields: nis, name, class (string), gender, status, address, phone, parent_*; relations: BelongsTo User (self), BelongsTo User (parent); keep `class` as string (future migration to `classroom_id`)
- [T2.3] TeacherResource @ID_15 — CRUD for Teacher; fields: nip, name, subject, gender, status, email, phone, address, user_id; relations: BelongsTo User, HasMany Schedule
- [T2.4] EmployeeResource @ID_15 — CRUD for Employee; fields: nik, name, position, gender, status, email, phone, address, user_id; relations: BelongsTo User
- [T3.1] SubjectResource @ID_15 — CRUD for Subject; fields: name, short_name, code, group, description; relations: HasMany Schedule
- [T3.2] ClassroomResource @ID_15 — CRUD for Classroom; fields: name, major, homeroom_teacher_id, academic_year; relations: BelongsTo Teacher (homeroom), HasMany Student (via class string)
- [T3.3] ScheduleResource @ID_15 — CRUD for Schedule; fields: subject_id, teacher_id, day enum, start_time, end_time, class, room; relations: BelongsTo Subject, BelongsTo Teacher; time pickers, day enum, conflict validation
- [T3.4] PaymentCategoryResource @ID_15 — CRUD for PaymentCategory; fields: name, amount, description, is_active; relations: HasMany Payment
- [T4.1] RoleResource @ID_15 — Wrap Spatie Role model; name, guard_name, permissions (checkbox list via PermissionMultiSelect)
- [T4.2] PermissionResource @ID_15 — Wrap Spatie Permission model; name, guard_name
- [T4.3] Assign Permissions to Roles @ID_15 — Use plugin's `PermissionMultiSelect` in Role form
- [T5.1] Dashboard Widgets @ID_15 — Stats: Total Students, Teachers, Employees, Classrooms, Active Payments; Inertia-to-Filament data mapping
- [T5.2] Navigation Groups @ID_15 — Group: People (User, Student, Teacher, Employee), Academic (Subject, Classroom, Schedule, PaymentCategory), Security (Role, Permission)
- [T5.3] Global Search @ID_15 — Enable global search across all resources
- [T5.4] Activity Log @ID_15 — `filament/activity-log` plugin for audit trail (optional MVP)
- [T6.1] Seed Demo Data @ID_15 — `php artisan db:seed --class=DemoDataSeeder`; create admin: `admin@edusmart.pro` / `password`; sample students/teachers
- [T6.2] E2E Manual Test @ID_15 — Verify all CRUD flows, relations, validation, exports across all 10 resources
- [T6.3] Code Style @ID_15 — `./vendor/bin/pint` on all modified files
- [T6.4] Update README @ID_15 — Document Filament access, default credentials, resource overview
- [T6.5] Tag Release @ID_15 — `git tag sprint-1-complete`; push to remote per user directive

## Definition of Done

All 10 resources accessible via sidebar; Create/Edit/View/Delete functional; Relations render correctly; Validation prevents bad data; Exports generate valid CSV; Dashboard shows meaningful stats; Role/Permission management functional; Code passes `pint` and `php artisan test`; Seeded data visible in panel.

## Technical Specifications

**Resource Pattern (repeat for all 10):**
```php
class {Entity}Resource extends Resource
{
    protected static ?string $navigationGroup = 'Group Name';
    protected static ?string $navigationIcon = 'heroicon-o-...';
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form { ... }
    public static function table(Table $table): Table { ... }
    public static function getRelations(): array { return []; }
    public static function getPages(): array { return [...]; }
}
```

**Key Filament Components:**
- BelongsTo select: `Select::make('relation_id')->relationship('relation', 'name')`
- HasMany manager: separate `RelationManager` class
- Enum: `Select::make('field')->options(Enum::class)`
- Date/Time: `DatePicker`, `TimePicker`
- Toggle: `Toggle::make('is_active')`
- Rich text: `RichEditor` (for description)
- File upload: `FileUpload` (future avatars)

**Validation:** Reuse existing `Store{Entity}Request` / `Update{Entity}Request` classes via `(new StoreEntityRequest())->rules()`.

## File Structure After Sprint

```
app/Filament/
├── Resources/
│   ├── UserResource/UserResource.php
│   ├── StudentResource/StudentResource.php
│   ├── TeacherResource/TeacherResource.php
│   ├── EmployeeResource/EmployeeResource.php
│   ├── SubjectResource/SubjectResource.php
│   ├── ClassroomResource/ClassroomResource.php
│   ├── ScheduleResource/ScheduleResource.php
│   ├── PaymentCategoryResource/PaymentCategoryResource.php
│   ├── RoleResource/RoleResource.php
│   └── PermissionResource/PermissionResource.php
├── Pages/
│   ├── Dashboard.php
│   ├── Users/ListUsers.php / CreateUser.php / EditUser.php / ViewUser.php
│   ├── Students/ ... (same pattern)
│   ├── Teachers/ ... 
│   ├── Employees/ ...
│   ├── Subjects/ ...
│   ├── Classrooms/ ...
│   ├── Schedules/ ...
│   ├── PaymentCategories/ ...
│   ├── Roles/ ...
│   └── Permissions/ ...
├── Widgets/
│   ├── StatsOverview.php
│   └── ...
└── Resources/
    └── (relation managers per entity as needed)
```

## Quick Start Commands (run as @ID_15)

```bash
cd /home/andreewincorp/edusmartpro/backend

# Install Filament
composer require filament/filament:"^3.2" -W
php artisan filament:install --panels

# Install Spatia plugin
composer require filament/spatie-laravel-permission-plugin:"^3.2"

# Generate resources (example)
php artisan make:filament-resource User --generate --view
php artisan make:filament-resource Student --generate --view
# ... repeat for remaining entities

# Run & test
php artisan serve
# Visit http://localhost:8000/admin
```

---

**Plan file written to:** `/home/andreewincorp/edusmartpro/backend/SPRINT1_PLAN.md`
**Next:** Developer Agent (ID: 15) starts with T1.1 — Install Filament v3.