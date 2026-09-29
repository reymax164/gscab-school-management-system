# GSCAB School Management System

## [Controllers](app/Http/Controllers/)

| Group | File |
|---|---|
| General | [Controller](app/Http/Controllers/Controller.php) |
| Admin | [ClassroomController](app/Http/Controllers/Admin/ClassroomController.php) |
| Admin | [SectionController](app/Http/Controllers/Admin/SectionController.php) |
| Admin | [SectioningController](app/Http/Controllers/Admin/SectioningController.php) |
| Admin | [StudentController](app/Http/Controllers/Admin/StudentController.php) |
| Admin | [SubjectController](app/Http/Controllers/Admin/SubjectController.php) |
| Admin | [SystemSettingController](app/Http/Controllers/Admin/SystemSettingController.php) |
| Admin | [TeacherController](app/Http/Controllers/Admin/TeacherController.php) |
| Admin | [CashierController](app/Http/Controllers/Admin/CashierController.php) |
| Admin | [RegistrarController](app/Http/Controllers/Admin/RegistrarController.php) |
| Applicant | [EnrollmentController](app/Http/Controllers/Applicant/EnrollmentController.php) |
| Auth | [LogoutController](app/Http/Controllers/Auth/LogoutController.php) |
| Auth | [StaffAuthController](app/Http/Controllers/Auth/StaffAuthController.php) |
| Auth | [StudentAuthController](app/Http/Controllers/Auth/StudentAuthController.php) |
| Cashier | [CashierEnrollmentController](app/Http/Controllers/Cashier/CashierEnrollmentController.php) |
| Registrar | [RegistrarEnrolledController](app/Http/Controllers/Registrar/RegistrarEnrolledController.php) |
| Registrar | [RegistrarEnrollmentController](app/Http/Controllers/Registrar/RegistrarEnrollmentController.php) |
| Registrar | [RegistrarReportControllers](app/Http/Controllers/Registrar/RegistrarReportControllers.php) |
| Student | [ScheduleController](app/Http/Controllers/Student/ScheduleController.php) |
| Teacher | [ScheduleController](app/Http/Controllers/Teacher/ScheduleController.php) |
| Teacher | [StudentController](app/Http/Controllers/Teacher/StudentController.php) |

## [Middlewares](app/Http/Middleware/)

| File |
|---|
| [RoleMiddleware](app/Http/Middleware/RoleMiddleware.php) |

## [Requests](app/Http/Requests/)

| File |
|---|
| [StoreSectionRequest](app/Http/Requests/StoreSectionRequest.php) |
| [StoreEnrollmentRequest](app/Http/Requests/StoreEnrollmentRequest.php) |

## [Mail](app/Mail/)

| File |
|---|
| [ApplicationSubmitted](app/Mail/ApplicationSubmitted.php) |

## [Models](app/Models/)

| Group | File |
|---|---|
| Enrollments | [DocumentRequirement](app/Models/Enrollments/DocumentRequirement.php) |
| Enrollments | [EducationalBackground](app/Models/Enrollments/EducationalBackground.php) |
| Enrollments | [Enrollment](app/Models/Enrollments/Enrollment.php) |
| Enrollments | [EnrollmentPayment](app/Models/Enrollments/EnrollmentPayment.php) |
| Enrollments | [StudentProfile](app/Models/Enrollments/StudentProfile.php) |
| General | [Classroom](app/Models/Classroom.php) |
| General | [Section](app/Models/Section.php) |
| General | [Student](app/Models/Student.php) |
| General | [Subject](app/Models/Subject.php) |
| General | [SubjectSchedule](app/Models/SubjectSchedule.php) |
| General | [SystemSetting](app/Models/SystemSetting.php) |
| General | [Teacher](app/Models/Teacher.php) |
| General | [User](app/Models/User.php) |

## [Migrations](database/migrations/)

| File |
|---|
| [0001_01_01_000000_create_users_table.php](database/migrations/0001_01_01_000000_create_users_table.php) |
| [2026_08_18_055223_create_students_table.php](database/migrations/2026_08_18_055223_create_students_table.php) |
| [2026_08_18_055419_create_teachers_table.php](database/migrations/2026_08_18_055419_create_teachers_table.php) |
| [2026_08_19_101113_create_enrollments_table.php](database/migrations/2026_08_19_101113_create_enrollments_table.php) |
| [2026_08_19_101128_create_student_profiles_table.php](database/migrations/2026_08_19_101128_create_student_profiles_table.php) |
| [2026_08_19_101147_create_educational_backgrounds_table.php](database/migrations/2026_08_19_101147_create_educational_backgrounds_table.php) |
| [2026_08_19_101158_create_enrollment_payments_table.php](database/migrations/2026_08_19_101158_create_enrollment_payments_table.php) |
| [2026_08_24_122913_create_document_requirements_table.php](database/migrations/2026_08_24_122913_create_document_requirements_table.php) |
| [2026_08_26_094252_create_subjects_table.php](database/migrations/2026_08_26_094252_create_subjects_table.php) |
| [2026_08_26_094305_create_classrooms_table.php](database/migrations/2026_08_26_094305_create_classrooms_table.php) |
| [2026_08_26_094312_create_sections_table.php](database/migrations/2026_08_26_094312_create_sections_table.php) |
| [2026_08_26_094331_create_system_settings_table.php](database/migrations/2026_08_26_094331_create_system_settings_table.php) |
| [2026_08_26_094335_create_section_student_table.php](database/migrations/2026_08_26_094335_create_section_student_table.php) |
| [2026_08_27_000000_create_subject_schedules_table.php](database/migrations/2026_08_27_000000_create_subject_schedules_table.php) |

## [Routes](routes/)

| File |
|---|
| [web.php](routes/web.php) |
| [admin.php](routes/admin.php) |
| [auth.php](routes/auth.php) |
| [cashier.php](routes/cashier.php) |
| [registrar.php](routes/registrar.php) |
| [student.php](routes/student.php) |
| [teacher.php](routes/teacher.php) |

## [Views](resources/views/)

### Pages

| Area | View |
|---|---|
| Admin | [admin.accounts.cashier.create](resources/views/admin/accounts/cashier/create.blade.php) |
| Admin | [admin.accounts.cashier.edit](resources/views/admin/accounts/cashier/edit.blade.php) |
| Admin | [admin.accounts.cashier.index](resources/views/admin/accounts/cashier/index.blade.php) |
| Admin | [admin.accounts.cashier.show](resources/views/admin/accounts/cashier/show.blade.php) |
| Admin | [admin.accounts.registrar.create](resources/views/admin/accounts/registrar/create.blade.php) |
| Admin | [admin.accounts.registrar.edit](resources/views/admin/accounts/registrar/edit.blade.php) |
| Admin | [admin.accounts.registrar.index](resources/views/admin/accounts/registrar/index.blade.php) |
| Admin | [admin.accounts.registrar.show](resources/views/admin/accounts/registrar/show.blade.php) |
| Admin | [admin.accounts.students.create](resources/views/admin/accounts/students/create.blade.php) |
| Admin | [admin.accounts.students.edit](resources/views/admin/accounts/students/edit.blade.php) |
| Admin | [admin.accounts.students.index](resources/views/admin/accounts/students/index.blade.php) |
| Admin | [admin.accounts.students.show](resources/views/admin/accounts/students/show.blade.php) |
| Admin | [admin.accounts.teachers.create](resources/views/admin/accounts/teachers/create.blade.php) |
| Admin | [admin.accounts.teachers.edit](resources/views/admin/accounts/teachers/edit.blade.php) |
| Admin | [admin.accounts.teachers.index](resources/views/admin/accounts/teachers/index.blade.php) |
| Admin | [admin.accounts.teachers.show](resources/views/admin/accounts/teachers/show.blade.php) |
| Admin | [admin.classrooms.create](resources/views/admin/classrooms/create.blade.php) |
| Admin | [admin.classrooms.edit](resources/views/admin/classrooms/edit.blade.php) |
| Admin | [admin.classrooms.index](resources/views/admin/classrooms/index.blade.php) |
| Admin | [admin.dashboard](resources/views/admin/dashboard.blade.php) |
| Admin | [admin.feedbacks](resources/views/admin/feedbacks.blade.php) |
| Admin | [admin.news](resources/views/admin/news.blade.php) |
| Admin | [admin.sections.create](resources/views/admin/sections/create.blade.php) |
| Admin | [admin.sections.edit](resources/views/admin/sections/edit.blade.php) |
| Admin | [admin.sections.form](resources/views/admin/sections/form.blade.php) |
| Admin | [admin.sections.index](resources/views/admin/sections/index.blade.php) |
| Admin | [admin.sections.sectioning](resources/views/admin/sections/sectioning.blade.php) |
| Admin | [admin.sections.show](resources/views/admin/sections/show.blade.php) |
| Admin | [admin.staffs](resources/views/admin/staffs.blade.php) |
| Admin | [admin.subjects.create](resources/views/admin/subjects/create.blade.php) |
| Admin | [admin.subjects.edit](resources/views/admin/subjects/edit.blade.php) |
| Admin | [admin.subjects.index](resources/views/admin/subjects/index.blade.php) |
| Admin | [admin.system_settings.create](resources/views/admin/system_settings/create.blade.php) |
| Admin | [admin.system_settings.edit](resources/views/admin/system_settings/edit.blade.php) |
| Admin | [admin.system_settings.index](resources/views/admin/system_settings/index.blade.php) |
| Auth | [auth.staff](resources/views/auth/staff.blade.php) |
| Auth | [auth.student](resources/views/auth/student.blade.php) |
| Cashier | [cashier.balances](resources/views/cashier/balances.blade.php) |
| Cashier | [cashier.dashboard](resources/views/cashier/dashboard.blade.php) |
| Cashier | [cashier.enrollment](resources/views/cashier/enrollment.blade.php) |
| Cashier | [cashier.enrollment-show](resources/views/cashier/enrollment-show.blade.php) |
| Cashier | [cashier.history](resources/views/cashier/history.blade.php) |
| Cashier | [cashier.reports](resources/views/cashier/reports.blade.php) |
| Enrollment | [enrollment.form](resources/views/enrollment/form.blade.php) |
| Enrollment | [enrollment.success](resources/views/enrollment/success.blade.php) |
| Guest | [guest.about](resources/views/guest/about.blade.php) |
| Guest | [guest.contact](resources/views/guest/contact.blade.php) |
| Guest | [guest.faqs](resources/views/guest/faqs.blade.php) |
| Guest | [guest.index](resources/views/guest/index.blade.php) |
| Guest | [guest.news](resources/views/guest/news.blade.php) |
| Guest | [guest.track-status](resources/views/guest/track-status.blade.php) |
| Registrar | [registrar.applications.create](resources/views/registrar/applications/create.blade.php) |
| Registrar | [registrar.applications.index](resources/views/registrar/applications/index.blade.php) |
| Registrar | [registrar.applications.show](resources/views/registrar/applications/show.blade.php) |
| Registrar | [registrar.dashboard](resources/views/registrar/dashboard.blade.php) |
| Registrar | [registrar.enrolled.index](resources/views/registrar/enrolled/index.blade.php) |
| Registrar | [registrar.enrolled.show](resources/views/registrar/enrolled/show.blade.php) |
| Registrar | [registrar.reports](resources/views/registrar/reports.blade.php) |
| Student | [student.balance](resources/views/student/balance.blade.php) |
| Student | [student.dashboard](resources/views/student/dashboard.blade.php) |
| Student | [student.feedback](resources/views/student/feedback.blade.php) |
| Student | [student.grades](resources/views/student/grades.blade.php) |
| Student | [student.schedule](resources/views/student/schedule.blade.php) |
| Teacher | [teacher.dashboard](resources/views/teacher/dashboard.blade.php) |
| Teacher | [teacher.grades.index](resources/views/teacher/grades/index.blade.php) |
| Teacher | [teacher.grades.show](resources/views/teacher/grades/show.blade.php) |
| Teacher | [teacher.schedules.index](resources/views/teacher/schedules/index.blade.php) |
| Teacher | [teacher.schedules.show](resources/views/teacher/schedules/show.blade.php) |
| Teacher | [teacher.students.index](resources/views/teacher/students/index.blade.php) |
| Teacher | [teacher.students.show](resources/views/teacher/students/show.blade.php) |

### Components

| Component | File |
|---|---|
| [`<x-footer />`](resources/views/components/footer.blade.php) | `resources/views/components/footer.blade.php` |
| [`<x-form.input />`](resources/views/components/form/input.blade.php) | `resources/views/components/form/input.blade.php` |
| [`<x-form.layout />`](resources/views/components/form/layout.blade.php) | `resources/views/components/form/layout.blade.php` |
| [`<x-form.searchable-select />`](resources/views/components/form/searchable-select.blade.php) | `resources/views/components/form/searchable-select.blade.php` |
| [`<x-form.select />`](resources/views/components/form/select.blade.php) | `resources/views/components/form/select.blade.php` |
| [`<x-guest.layout />`](resources/views/components/guest/layout.blade.php) | `resources/views/components/guest/layout.blade.php` |
| [`<x-guest.nav-link />`](resources/views/components/guest/nav-link.blade.php) | `resources/views/components/guest/nav-link.blade.php` |
| [`<x-icons.facebook />`](resources/views/components/icons/facebook.blade.php) | `resources/views/components/icons/facebook.blade.php` |
| [`<x-icons.messenger />`](resources/views/components/icons/messenger.blade.php) | `resources/views/components/icons/messenger.blade.php` |
| [`<x-layouts.app />`](resources/views/components/layouts/app.blade.php) | `resources/views/components/layouts/app.blade.php` |
| [`<x-layouts.auth />`](resources/views/components/layouts/auth.blade.php) | `resources/views/components/layouts/auth.blade.php` |
| [`<x-nav.bar />`](resources/views/components/nav/bar.blade.php) | `resources/views/components/nav/bar.blade.php` |
| [`<x-nav.group />`](resources/views/components/nav/group.blade.php) | `resources/views/components/nav/group.blade.php` |
| [`<x-nav.link />`](resources/views/components/nav/link.blade.php) | `resources/views/components/nav/link.blade.php` |
| [`<x-table />`](resources/views/components/table.blade.php) | `resources/views/components/table.blade.php` |
| [`<x-table.cell />`](resources/views/components/table/cell.blade.php) | `resources/views/components/table/cell.blade.php` |
| [`<x-table.row />`](resources/views/components/table/row.blade.php) | `resources/views/components/table/row.blade.php` |
| [`<x-widgets.blank />`](resources/views/components/widgets/blank.blade.php) | `resources/views/components/widgets/blank.blade.php` |
| [`<x-widgets.multi-col-scroll />`](resources/views/components/widgets/multi-col-scroll.blade.php) | `resources/views/components/widgets/multi-col-scroll.blade.php` |
| [`<x-widgets.number />`](resources/views/components/widgets/number.blade.php) | `resources/views/components/widgets/number.blade.php` |
| [`<x-widgets.scrollable />`](resources/views/components/widgets/scrollable.blade.php) | `resources/views/components/widgets/scrollable.blade.php` |
