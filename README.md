# GSCAB School Management System

## Controllers:
- `app\Http\Controllers\Controller.php`

### Admin Controllers
- `app\Http\Controllers\Admin\ClassroomController.php`
- `app\Http\Controllers\Admin\ClassScheduleController.php`
- `app\Http\Controllers\Admin\SectioningController.php`
- `app\Http\Controllers\Admin\StudentController.php`
- `app\Http\Controllers\Admin\SubjectController.php`
- `app\Http\Controllers\Admin\SystemSettingController.php`

### Applicant Controllers
- `app\Http\Controllers\Applicant\EnrollmentController.php`

### Auth Controllers
- `app\Http\Controllers\Auth\LogoutController.php`
- `app\Http\Controllers\Auth\StaffAuthController.php`
- `app\Http\Controllers\Auth\StudentAuthController.php`

### Cashier Controllers
- `app\Http\Controllers\Cashier\CashierEnrollmentController.php`

### Registrar Controllers
- `app\Http\Controllers\Registrar\RegistrarEnrolledController.php`
- `app\Http\Controllers\Registrar\RegistrarEnrollmentController.php`

## Models:
- `app\Models\Classroom.php`
- `app\Models\ClassSchedule.php`
- `app\Models\Student.php`
- `app\Models\Subject.php`
- `app\Models\SubjectSchedule.php`
- `app\Models\SystemSetting.php`
- `app\Models\Teacher.php`
- `app\Models\User.php`

### Enrollment Models
- `app\Models\Enrollments\DocumentRequirement.php`
- `app\Models\Enrollments\EducationalBackground.php`
- `app\Models\Enrollments\Enrollment.php`
- `app\Models\Enrollments\EnrollmentPayment.php`
- `app\Models\Enrollments\StudentProfile.php`

## Migrations:
- `database\migrations\0001_01_01_000000_create_users_table.php`
- `database\migrations\2026_08_18_055223_create_students_table.php`
- `database\migrations\2026_08_18_055419_create_teachers_table.php`
- `database\migrations\2026_08_19_101113_create_enrollments_table.php`
- `database\migrations\2026_08_19_101128_create_student_profiles_table.php`
- `database\migrations\2026_08_19_101147_create_educational_backgrounds_table.php`
- `database\migrations\2026_08_19_101158_create_enrollment_payments_table.php`
- `database\migrations\2026_08_24_122913_create_document_requirements_table.php`
- `database\migrations\2026_08_25_191146_add_reference_code_to_enrollments_table.php`
- `database\migrations\2026_08_26_094252_create_subjects_table.php`
- `database\migrations\2026_08_26_094305_create_classrooms_table.php`
- `database\migrations\2026_08_26_094312_create_class_schedules_table.php`
- `database\migrations\2026_08_26_094331_create_system_settings_table`
- `database\migrations\2026_08_26_094335_create_class_schedule_student_table.php`
- `database\migrations\2026_08_26_125845_rename_user_type_to_role_on_users_table.php`
- `database\migrations\2026_08_27_000000_create_subject_schedules_table.php`

## Routes:
- `routes\web.php`
- `routes\admin.php`
- `routes\auth.php`
- `routes\cashier.php`
- `routes\registrar.php`
- `routes\student.php`
- `routes\teacher.php`

## Views:
1. `resources\views\auth\enroll.blade.php`
2. `resources\views\auth\staff.blade.php`
3. `resources\views\auth\student.blade.php`

4. `resources\views\components\footer.blade.php`
5. `resources\views\components\table.blade.php`

6. `resources\views\components\form\select.blade.php`
7. `resources\views\components\guest\layout.blade.php`
8. `resources\views\components\guest\nav-link.blade.php`

9. `resources\views\components\layouts\app.blade.php`
10. `resources\views\components\layouts\auth.blade.php`

11. `resources\views\components\nav\bar.blade.php`
12. `resources\views\components\nav\link.blade.php`
13. `resources\views\components\nav\group.blade.php`

14. `resources\views\components\table\cell.blade.php`
15. `resources\views\components\table\row.blade.php`

16. `resources\views\components\widgets\blank.blade.php`
17. `resources\views\components\widgets\multi-col-scroll.blade.php`
18. `resources\views\components\widgets\number.blade.php`
19. `resources\views\components\widgets\scrollable.blade.php`