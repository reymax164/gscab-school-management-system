<?php

return [
    // student
    'student' => [
        ['route' => 'student.dashboard', 'label' => 'Dashboard', 'icon' => 'heroicon-s-squares-2x2'],
        ['route' => 'student.schedule',  'label' => 'Class Schedule',  'icon' => 'heroicon-s-calendar'],
        ['route' => 'student.grades',    'label' => 'Grades',    'icon' => 'heroicon-s-academic-cap'],
        ['route' => 'student.balance',   'label' => 'Balance',   'icon' => 'heroicon-s-credit-card'],
        ['route' => 'student.feedback',  'label' => 'Feedback',  'icon' => 'heroicon-s-chat-bubble-left-right'],
    ],

    // teacher
    'teacher' => [
        ['route' => 'teacher.dashboard', 'label' => 'Dashboard', 'icon' => 'heroicon-s-squares-2x2'],
        ['route' => 'teacher.schedule',  'label' => 'Class Schedule',  'icon' => 'heroicon-s-calendar'],
        ['route' => 'teacher.students',  'label' => 'Student List',  'icon' => 'heroicon-s-user-group'],
        ['route' => 'teacher.grades',    'label' => 'Student Grades',    'icon' => 'heroicon-s-academic-cap'],
    ],

    // registrar
    'registrar' => [
        ['route' => 'registrar.dashboard', 'label' => 'Dashboard', 'icon' => 'heroicon-s-squares-2x2'],
        ['route' => 'registrar.records.index',  'label' => 'Student Records',  'icon' => 'heroicon-s-academic-cap'],
        
        // REPLACED the 'children' array with a single direct link
        ['route' => 'registrar.applications.index', 'label' => 'Applications & Admission', 'icon' => 'heroicon-s-user-plus'],
        
        ['route' => 'registrar.reports',   'label' => 'Reports','icon' => 'heroicon-s-chart-bar'],
    ],

    // cashier
    'cashier' => [
        ['route' => 'cashier.dashboard', 'label' => 'Dashboard', 'icon' => 'heroicon-s-squares-2x2'],
        
        [
            'label' => 'Payments', 
            'icon' => 'heroicon-s-credit-card',
            'children' => [
                ['route' => 'cashier.enrollment.index', 'label' => 'Pending Enrollments'],
                ['route' => 'cashier.balances.index', 'label' => 'Student Balances'],
            ]
        ],
        
        ['route' => 'cashier.history', 'label' => 'Transaction History', 'icon' => 'heroicon-s-clipboard-document-list'],
        ['route' => 'cashier.reports', 'label' => 'Reports', 'icon' => 'heroicon-s-chart-bar'],
    ],

    // admin
    'admin' => [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'heroicon-s-squares-2x2'],
        ['route' => 'admin.students',  'label' => 'Manage Students',  'icon' => 'heroicon-s-calendar'],
        ['route' => 'admin.staffs',    'label' => 'Manage Staffs', 'icon' => 'heroicon-s-user-group'],
        ['route' => 'admin.schedules',   'label' => 'Manage Schedules', 'icon' => 'heroicon-s-calendar'],
        ['route' => 'admin.news',  'label' => 'Post News',  'icon' => 'heroicon-s-newspaper'],
        ['route' => 'admin.feedbacks',  'label' => 'Read Feedbacks',  'icon' => 'heroicon-s-chat-bubble-left-right'],
    ],
];