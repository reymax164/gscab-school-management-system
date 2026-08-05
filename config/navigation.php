<?php

return [
    'student' => [
        ['route' => 'student.dashboard', 'label' => 'Dashboard', 'icon' => 'icons/dashboard.png'],
        ['route' => 'student.schedule',  'label' => 'Class Schedule',  'icon' => 'icons/schedule.png'],
        ['route' => 'student.grades',    'label' => 'Grades',    'icon' => 'icons/grades.png'],
        ['route' => 'student.balance',   'label' => 'Balance',   'icon' => 'icons/balance.png'],
        ['route' => 'student.feedback',  'label' => 'Feedback',  'icon' => 'icons/feedback.png'],
    ],
    'teacher' => [
        ['route' => 'teacher.dashboard', 'label' => 'Dashboard', 'icon' => 'icons/dashboard.png'],
        ['route' => 'teacher.schedule',  'label' => 'Class Schedule',  'icon' => 'icons/schedule.png'],
        ['route' => 'teacher.student-list',  'label' => 'Student List',  'icon' => 'icons/feedback.png'],
        ['route' => 'teacher.grades',    'label' => 'Student Grades',    'icon' => 'icons/grades.png'],
    ],
    'registrar' => [
        ['route' => 'registrar.dashboard', 'label' => 'Dashboard', 'icon' => 'icons/dashboard.png'],
        ['route' => 'registrar.records',  'label' => 'Student Records',  'icon' => 'icons/schedule.png'],
        ['route' => 'registrar.admissions',    'label' => 'Admissions',    'icon' => 'icons/grades.png'],
        ['route' => 'registrar.reports',   'label' => 'Reports',   'icon' => 'icons/balance.png'],
    ],
    'cashier' => [
        ['route' => 'cashier.dashboard', 'label' => 'Dashboard', 'icon' => 'icons/dashboard.png'],
    ],
    'admin' => [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'icons/dashboard.png'],
        ['route' => 'admin.students',  'label' => 'Manage Students',  'icon' => 'icons/schedule.png'],
        ['route' => 'admin.staffs',    'label' => 'Manage Staffs',    'icon' => 'icons/grades.png'],
        ['route' => 'admin.schedules',   'label' => 'Manage Schedules',   'icon' => 'icons/balance.png'],
        ['route' => 'admin.news',  'label' => 'News',  'icon' => 'icons/feedback.png'],
        ['route' => 'admin.feedbacks',  'label' => 'Feedbacks',  'icon' => 'icons/feedback.png'],
    ],
];