<?php

return [
    'student' => [
        ['route' => 'student.dashboard', 'label' => 'Dashboard', 'icon' => 'heroicon-s-squares-2x2'],
        ['route' => 'student.schedule',  'label' => 'Class Schedule',  'icon' => 'heroicon-s-schedule'],
        ['route' => 'student.grades',    'label' => 'Grades',    'icon' => 'heroicon-s-grades'],
        ['route' => 'student.balance',   'label' => 'Balance',   'icon' => 'heroicon-s-balance'],
        ['route' => 'student.feedback',  'label' => 'Feedback',  'icon' => 'heroicon-s-feedback'],
    ],
    'teacher' => [
        ['route' => 'teacher.dashboard', 'label' => 'Dashboard', 'icon' => 'heroicon-s-squares-2x2'],
        ['route' => 'teacher.schedule',  'label' => 'Class Schedule',  'icon' => 'heroicon-s-schedule'],
        ['route' => 'teacher.students',  'label' => 'Student List',  'icon' => 'heroicon-s-feedback'],
        ['route' => 'teacher.grades',    'label' => 'Student Grades',    'icon' => 'heroicon-s-grades'],
    ],
    'registrar' => [
        ['route' => 'registrar.dashboard', 'label' => 'Dashboard', 'icon' => 'heroicon-s-squares-2x2'],
        ['route' => 'registrar.records',  'label' => 'Student Records',  'icon' => 'heroicon-s-academic-cap'],
        ['route' => 'registrar.applications', 'label' => 'New Applications',    'icon' => 'heroicon-s-user-plus'],
        ['route' => 'registrar.finalization', 'label' => 'For Finalization',    'icon' => 'heroicon-s-clipboard-document-check'],
        ['route' => 'registrar.reports',   'label' => 'Reports',   'icon' => 'heroicon-s-document-chart-bar'],
    ],
    'cashier' => [
        ['route' => 'cashier.dashboard', 'label' => 'Dashboard', 'icon' => 'heroicon-s-squares-2x2'],
        ['route' => 'cashier.finance', 'label' => 'Dashboard', 'icon' => 'heroicon-s-finance'],
        ['route' => 'cashier.reports', 'label' => 'Dashboard', 'icon' => 'heroicon-s-squares-2x2'],
    ],
    'admin' => [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'heroicon-s-squares-2x2'],
        ['route' => 'admin.students',  'label' => 'Manage Students',  'icon' => 'heroicon-s-schedule'],
        ['route' => 'admin.staffs',    'label' => 'Manage Staffs',    'icon' => 'heroicon-s-grades'],
        ['route' => 'admin.schedules',   'label' => 'Manage Schedules',   'icon' => 'heroicon-s-balance'],
        ['route' => 'admin.news',  'label' => 'News',  'icon' => 'heroicon-s-feedback'],
        ['route' => 'admin.feedbacks',  'label' => 'Feedbacks',  'icon' => 'heroicon-s-feedback'],
    ],
];