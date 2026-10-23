# Teacher Portal Dashboard – Technical Breakdown (EdusmartPro)

## 1. Goal
Provide a dedicated **Teacher Dashboard** (role `teacher`) accessible at `/teacher/dashboard` with:
- Stat cards: Classes taught, Total students, Today's schedule, Pending grading.
- Today's teaching schedule (interactive list).
- Quick attendance entry per class/session.
- Assignment & grade entry UI.
- School announcements widget.

All components follow existing Inertia + Vue 3 + Tailwind patterns used in `resources/js/Pages/Dashboard.vue`.

---

## 2. Database / Models (already present)

| Model | Key Relations | Notes |
|-------|---------------|-------|
| `Teacher` | `belongsTo User`, `hasMany Schedule`, `hasMany Classroom` (homeroom) | links `user_id` |
| `Schedule` | `belongsTo Teacher`, `belongsTo Subject`, `belongsTo Classroom`, `belongsTo AcademicYear` | day_of_week, start_time, end_time |
| `Student` | `belongsTo Classroom`, `hasMany Attendance`, `hasMany Grade` | nis, name |
| `Attendance` | `belongsTo Student`, `belongsTo Schedule`, `belongsTo Teacher` | status enum: `hadir, izin, sakit, alpa` |
| `Grade` | `belongsTo Student`, `belongsTo Subject`, `belongsTo Teacher` | task, uts, uas, final, letter |
| `Announcement` (new) | `belongsTo User (admin)`, `target_roles` json | published_at, title, body |

**New migration**: `create_announcements_table`.

---

## 3. Backend – Controllers & Routes

| Route | Controller@Method | Middleware | Purpose |
|-------|-------------------|------------|---------|
| `GET /teacher/dashboard` | `TeacherDashboardController@index` | `auth, role:teacher` | Main portal view |
| `GET /teacher/schedule/today` | `TeacherDashboardController@todaySchedule` | same | JSON for schedule widget |
| `POST /teacher/attendance` | `TeacherDashboardController@storeAttendance` | same | Bulk attendance create |
| `POST /teacher/grades` | `TeacherDashboardController@storeGrade` | same | Bulk grade create |
| `GET /teacher/announcements` | `TeacherDashboardController@announcements` | same | Latest announcements |

**New Controller**: `app/Http/Controllers/Teacher/TeacherDashboardController.php` (namespaced under `Teacher`).

---

## 4. Frontend – Vue Components (under `resources/js/Pages/Teacher/`)

```
Teacher/
├── Dashboard.vue                # Main entry (Inertia page)
├── Components/
│   ├── StatCard.vue            # Reusable metric card
│   ├── ScheduleList.vue        # Today’s schedule table
│   ├── AttendanceForm.vue      # Quick attendance grid
│   ├── GradeEntryForm.vue      # Assignment + score grid
│   └── AnnouncementWidget.vue  # Announcement list
```

All components import UI primitives (`Card`, `Button`, `Input`, `Select`) from `Components/ui/` and icons from `lucide-vue-next`.

---

## 5. Data Shapes (TypeScript interfaces for props)

```ts
interface StatCardProps {
  label: string
  value: number | string
  icon: Component
  trend?: { value: number; up: boolean }
}

interface ScheduleItem {
  id: number
  subject: string
  classroom: string
  start_time: string   // HH:mm
  end_time: string
  student_count: number
}

interface AttendanceRow {
  student_id: number
  name: string
  status: 'hadir'|'izin'|'sakit'|'alpa'
}

interface GradeRow {
  student_id: number
  name: string
  task_score?: number
  mid_exam_score?: number
  final_exam_score?: number
}

interface Announcement {
  id: number
  title: string
  body: string
  published_at: string
}
```

---

## 6. UX / UI Guidelines (Researcher output)

- **Responsive grid**: 4 stat cards on ≥lg, 2 on md, 1 on sm.
- **Schedule list**: horizontal scroll on mobile, sticky time column.
- **Attendance**: single-page matrix (students × status radios) with **Save All** button; optimistic UI via Inertia `form`.
- **Grade entry**: tabbed per assessment type (Tugas / UTS / UAS) with live final-score preview (same 30/30/40 formula).
- **Announcements**: max 5 items, “Lihat semua” link to `/announcements`.
- **Color palette**: indigo primary, emerald success (hadir), amber warning (izin), rose danger (alpa), slate neutrals.
- **Accessibility**: proper `<label>`/`<input>` pairing, ARIA roles for tables, focus-visible outlines.

---

## 7. Implementation Steps (Sprint)

| # | Task | Owner | Estimate |
|---|------|-------|----------|
| 1 | Migration `announcements` + seeder | Dev | 0.5h |
| 2 | `TeacherDashboardController` with 5 methods | Dev | 1.5h |
| 3 | Routes registration (`role:teacher` group) | Dev | 0.25h |
| 4 | Vue page + 5 components | Dev | 3h |
| 5 | Tailwind refinements & dark-mode check | Dev | 0.5h |
| 6 | Unit + feature tests (auth, attendance bulk, grade bulk) | Dev | 1h |
| 7 | Security audit (authz, input sanitisation) | Sec | 0.5h |
| 8 | Merge, build, push → Vercel auto-deploy | Dev | 0.25h |

---

## 8. Acceptance Criteria
- Teacher logs in → redirected to `/teacher/dashboard`.
- All 4 stat cards show live counts.
- Attendance form saves multiple rows in one request; validation errors inline.
- Grade form computes final score client-side & server-side.
- Announcements widget shows latest 5 from admins.
- Zero console errors; `npm run build` passes on Node 20+.
- All new routes return 403 for non-teacher roles.

---

## 9. Next Handoff
**Researcher (@ID_12)** – supply UX references (competitor teacher dashboards, Tailwind component examples) and confirm the `Announcement` migration schema.

**Developer (@ID_15)** – begin implementation once researcher data arrives; push incremental commits per step.

**Security (@ID_29)** – review PR after step 6.

---

*Prepared by Project Manager (ID 18) – ready for Researcher hand-off.*