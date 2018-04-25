<?php

use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->truncate();
    	
        DB::table('pages')->insert([
            'code' => 'teams-list',
            'folder' => 'teams',
            'file' => 'list',
            'name' => 'Teams',
            'icon' => 'fa fa-users',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'users-users',
            'folder' => '',
            'file' => '',
            'name' => 'Users',
            'icon' => 'fa fa-user',
            'tpl' => '',
            'public' => 0,
        ]);
        
        DB::table('pages')->insert([
            'code' => 'users-live',
            'folder' => 'users',
            'file' => 'live',
            'name' => 'Live',
            'icon' => '',
            'tpl' => '',
            'public' => 0,
        ]);
        
        DB::table('pages')->insert([
            'code' => 'users-free',
            'folder' => 'users',
            'file' => 'free',
            'name' => 'Free',
            'icon' => '',
            'tpl' => '',
            'public' => 0,
        ]);
        
        DB::table('pages')->insert([
            'code' => 'users-canceled',
            'folder' => 'users',
            'file' => 'canceled',
            'name' => 'Canceled',
            'icon' => '',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'users-profile',
            'folder' => 'users',
            'file' => 'profile',
            'name' => 'Profile',
            'icon' => 'fa fa-chevron-right',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'users-activate',
            'folder' => 'users',
            'file' => 'activate',
            'name' => 'Activate',
            'icon' => 'fa fa-chevron-right',
            'tpl' => '',
            'public' => 1,
        ]);

        DB::table('pages')->insert([
            'code' => 'plans-list',
            'folder' => 'plans',
            'file' => 'list',
            'name' => 'Payment Plans',
            'icon' => 'fa fa-credit-card',
            'tpl' => '',
            'public' => 0,
        ]);

        /* DB::table('pages')->insert([
            'code' => 'settings-admin',
            'folder' => 'admin',
            'file' => 'settings',
            'name' => 'Settings',
            'icon' => 'fa fa-cog',
            'tpl' => '',
            'public' => 0,
        ]); */

        DB::table('pages')->insert([
            'code' => 'plans-user',
            'folder' => 'plans',
            'file' => 'user',
            'name' => 'Payment Plans',
            'icon' => 'fa fa-credit-card',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'ha-list',
            'folder' => 'ha',
            'file' => 'list',
            'name' => 'HomeAdvisor',
            'icon' => 'fa fa-home',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'ha-user',
            'folder' => 'ha',
            'file' => 'user',
            'name' => 'HomeAdvisor',
            'icon' => 'fa fa-home',
            'tpl' => '',
            'public' => 0,
        ]);
        
        DB::table('pages')->insert([
            'code' => 'vonage-user',
            'folder' => 'vonage',
            'file' => 'user',
            'name' => 'Vonage',
            'icon' => 'fa fa-phone',
            'tpl' => '',
            'public' => 0,
        ]);
        
        DB::table('pages')->insert([
            'code' => 'vonage-list',
            'folder' => 'vonage',
            'file' => 'list',
            'name' => 'Inbox',
            'icon' => 'fa fa-chevron-right',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'surveys-surveys',
            'folder' => '',
            'file' => '',
            'name' => 'Star Rating Question',
            'icon' => 'fa fa-star-o',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'surveys-send',
            'folder' => 'surveys',
            'file' => 'send',
            'name' => 'Clients',
            'icon' => 'fa fa-chevron-right',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'surveys-partners',
            'folder' => 'surveys',
            'file' => 'partners',
            'name' => 'Partners',
            'icon' => 'fa fa-chevron-right',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'analysis-main',
            'folder' => 'surveys',
            'file' => 'analysis',
            'name' => 'Analysis',
            'icon' => 'fa fa-chevron-right',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'settings-alerts',
            'folder' => 'surveys',
            'file' => 'alerts',
            'name' => 'Alerts',
            'icon' => 'fa fa-chevron-right',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'online-reviews',
            'folder' => '',
            'file' => '',
            'name' => 'Online Reviews',
            'icon' => 'fa fa-users',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'settings-analysis',
            'folder' => 'reviews',
            'file' => 'analysis',
            'name' => 'Reviews Analysis',
            'icon' => 'fa fa-chevron-right',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'settings-reviews',
            'folder' => 'reviews',
            'file' => 'settings',
            'name' => 'Settings',
            'icon' => 'fa fa-chevron-right',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'messages-messages',
            'folder' => '',
            'file' => '',
            'name' => 'Text Marketing',
            'icon' => 'fa fa-commenting-o',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'messages-add',
            'folder' => 'marketing',
            'file' => 'add',
            'name' => 'Send',
            'icon' => 'fa fa-chevron-right',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'dialogs-list',
            'folder' => 'marketing',
            'file' => 'inbox',
            'name' => 'Inbox',
            'icon' => 'fa fa-chevron-right',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'messages-list',
            'folder' => 'marketing',
            'file' => 'outbox',
            'name' => 'Outbox',
            'icon' => 'fa fa-chevron-right',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'lists-list',
            'folder' => 'marketing',
            'file' => 'contacts',
            'name' => 'Contacts',
            'icon' => 'fa fa-chevron-right',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'marketing-settings',
            'folder' => 'marketing',
            'file' => 'settings',
            'name' => 'Settings',
            'icon' => 'fa fa-chevron-right',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'appointment-confirmation',
            'folder' => 'appointment',
            'file' => 'send',
            'name' => 'Appointment Confirmation',
            'icon' => 'fa fa-check-square-o',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'texts-reports',
            'folder' => 'texts',
            'file' => 'reports',
            'name' => 'Texts Reports',
            'icon' => 'fa fa-file',
            'tpl' => '',
            'public' => 0,
        ]);

        DB::table('pages')->insert([
            'code' => 'leads-list',
            'folder' => 'leads',
            'file' => 'list',
            'name' => 'Leads',
            'icon' => 'fa fa-users',
            'tpl' => '',
            'public' => 0,
        ]);
        
        DB::table('pages')->insert([
            'code' => 'new_users-send',
            'folder' => 'new_users',
            'file' => 'send',
            'name' => 'New Users',
            'icon' => 'fa fa-user-plus',
            'tpl' => '',
            'public' => 0,
        ]);
        
        DB::table('pages')->insert([
            'code' => 'inbox-inbox',
            'folder' => 'inbox',
            'file' => 'list',
            'name' => 'General Inbox',
            'icon' => 'fa fa-envelope',
            'tpl' => '',
            'public' => 0,
        ]);
        
        DB::table('pages')->insert([
            'code' => 'plans-info',
            'folder' => 'plans',
            'file' => 'info',
            'name' => 'Billing',
            'icon' => 'fa fa-clipboard',
            'tpl' => '',
            'public' => 0,
        ]);




        DB::table('pages_access')->truncate();
    	
        DB::table('pages_access')->insert([
            'code' => 'teams-list',
            'users_type' => 2,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'users-users',
            'users_type' => 2,
        ]);
        
        DB::table('pages_access')->insert([
            'code' => 'users-live',
            'users_type' => 2,
        ]);
        
        DB::table('pages_access')->insert([
            'code' => 'users-free',
            'users_type' => 2,
        ]);
        
        DB::table('pages_access')->insert([
            'code' => 'users-canceled',
            'users_type' => 2,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'plans-list',
            'users_type' => 2,
        ]);

        /* DB::table('pages_access')->insert([
            'code' => 'settings-admin',
            'users_type' => 2,
        ]); */

        DB::table('pages_access')->insert([
            'code' => 'ha-list',
            'users_type' => 2,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'surveys-surveys',
            'users_type' => 1,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'surveys-send',
            'users_type' => 1,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'surveys-partners',
            'users_type' => 1,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'analysis-main',
            'users_type' => 1,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'settings-alerts',
            'users_type' => 1,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'online-reviews',
            'users_type' => 1,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'settings-analysis',
            'users_type' => 1,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'settings-reviews',
            'users_type' => 1,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'messages-messages',
            'users_type' => 1,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'messages-add',
            'users_type' => 1,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'dialogs-list',
            'users_type' => 1,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'messages-list',
            'users_type' => 1,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'lists-list',
            'users_type' => 1,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'marketing-settings',
            'users_type' => 1,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'ha-user',
            'users_type' => 1,
        ]);
        
        DB::table('pages_access')->insert([
            'code' => 'vonage-user',
            'users_type' => 1,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'appointment-confirmation',
            'users_type' => 1,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'texts-reports',
            'users_type' => 2,
        ]);

        DB::table('pages_access')->insert([
            'code' => 'leads-list',
            'users_type' => 2,
        ]);
        
        DB::table('pages_access')->insert([
            'code' => 'new_users-send',
            'users_type' => 2,
        ]);
        
        DB::table('pages_access')->insert([
            'code' => 'inbox-inbox',
            'users_type' => 2,
        ]);
        
        DB::table('pages_access')->insert([
            'code' => 'plans-info',
            'users_type' => 1,
        ]);





        DB::table('pages_menu')->truncate();

        DB::table('pages_menu')->insert([
            'pages_code' => 'users-users',
            'parents_code' => '',
            'plans' => 'none',
            'main' => 0,
            'pos' => 1,
        ]);
        
        DB::table('pages_menu')->insert([
            'pages_code' => 'users-live',
            'parents_code' => 'users-users',
            'plans' => 'none',
            'main' => 1,
            'pos' => 1,
        ]);
        
        DB::table('pages_menu')->insert([
            'pages_code' => 'users-free',
            'parents_code' => 'users-users',
            'plans' => 'none',
            'main' => 0,
            'pos' => 2,
        ]);
        
        DB::table('pages_menu')->insert([
            'pages_code' => 'users-canceled',
            'parents_code' => 'users-users',
            'plans' => 'none',
            'main' => 0,
            'pos' => 3,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'plans-list',
            'parents_code' => '',
            'plans' => 'none',
            'main' => 0,
            'pos' => 3,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'ha-list',
            'parents_code' => '',
            'plans' => 'none',
            'main' => 0,
            'pos' => 4,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'texts-reports',
            'parents_code' => '',
            'plans' => 'none',
            'main' => 0,
            'pos' => 5,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'leads-list',
            'parents_code' => '',
            'plans' => 'none',
            'main' => 0,
            'pos' => 6,
        ]);
        
        DB::table('pages_menu')->insert([
            'pages_code' => 'new_users-send',
            'parents_code' => '',
            'plans' => 'none',
            'main' => 0,
            'pos' => 7,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'inbox-inbox',
            'parents_code' => '',
            'plans' => 'none',
            'main' => 0,
            'pos' => 8,
        ]);
        
        /* DB::table('pages_menu')->insert([
            'pages_code' => 'settings-admin',
            'parents_code' => '',
            'plans' => 'none',
            'main' => 0,
            'pos' => 9,
        ]); */

        DB::table('pages_menu')->insert([
            'pages_code' => 'surveys-surveys',
            'parents_code' => '',
            'plans' => 'none',
            'main' => 0,
            'pos' => 1,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'surveys-surveys',
            'parents_code' => '',
            'plans' => 'free-contractortexter',
            'main' => 0,
            'pos' => 1,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'surveys-surveys',
            'parents_code' => '',
            'plans' => 'text-contractortexter',
            'main' => 0,
            'pos' => 1,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'surveys-surveys',
            'parents_code' => '',
            'plans' => 'star-rating-contractortexter',
            'main' => 0,
            'pos' => 1,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'surveys-send',
            'parents_code' => 'surveys-surveys',
            'plans' => 'none',
            'main' => 1,
            'pos' => 1,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'surveys-send',
            'parents_code' => 'surveys-surveys',
            'plans' => 'free-contractortexter',
            'main' => 1,
            'pos' => 1,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'surveys-send',
            'parents_code' => 'surveys-surveys',
            'plans' => 'text-contractortexter',
            'main' => 1,
            'pos' => 1,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'surveys-send',
            'parents_code' => 'surveys-surveys',
            'plans' => 'star-rating-contractortexter',
            'main' => 1,
            'pos' => 1,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'surveys-partners',
            'parents_code' => 'surveys-surveys',
            'plans' => 'none',
            'main' => 0,
            'pos' => 2,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'surveys-partners',
            'parents_code' => 'surveys-surveys',
            'plans' => 'free-contractortexter',
            'main' => 0,
            'pos' => 2,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'surveys-partners',
            'parents_code' => 'surveys-surveys',
            'plans' => 'text-contractortexter',
            'main' => 0,
            'pos' => 2,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'surveys-partners',
            'parents_code' => 'surveys-surveys',
            'plans' => 'star-rating-contractortexter',
            'main' => 0,
            'pos' => 2,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'analysis-main',
            'parents_code' => 'surveys-surveys',
            'plans' => 'none',
            'main' => 0,
            'pos' => 4,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'analysis-main',
            'parents_code' => 'surveys-surveys',
            'plans' => 'free-contractortexter',
            'main' => 0,
            'pos' => 4,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'analysis-main',
            'parents_code' => 'surveys-surveys',
            'plans' => 'text-contractortexter',
            'main' => 0,
            'pos' => 4,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'analysis-main',
            'parents_code' => 'surveys-surveys',
            'plans' => 'star-rating-contractortexter',
            'main' => 0,
            'pos' => 4,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'settings-alerts',
            'parents_code' => 'surveys-surveys',
            'plans' => 'none',
            'main' => 0,
            'pos' => 3,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'settings-alerts',
            'parents_code' => 'surveys-surveys',
            'plans' => 'free-contractortexter',
            'main' => 0,
            'pos' => 3,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'settings-alerts',
            'parents_code' => 'surveys-surveys',
            'plans' => 'text-contractortexter',
            'main' => 0,
            'pos' => 3,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'settings-alerts',
            'parents_code' => 'surveys-surveys',
            'plans' => 'star-rating-contractortexter',
            'main' => 0,
            'pos' => 3,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'online-reviews',
            'parents_code' => '',
            'plans' => 'none',
            'main' => 0,
            'pos' => 2,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'online-reviews',
            'parents_code' => '',
            'plans' => 'free-contractortexter',
            'main' => 0,
            'pos' => 2,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'online-reviews',
            'parents_code' => '',
            'plans' => 'text-contractortexter',
            'main' => 0,
            'pos' => 2,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'online-reviews',
            'parents_code' => '',
            'plans' => 'star-rating-contractortexter',
            'main' => 0,
            'pos' => 2,
        ]);

        /*DB::table('pages_menu')->insert([
            'pages_code' => 'settings-analysis',
            'parents_code' => 'online-reviews',
            'plans' => 'none',
            'main' => 0,
            'pos' => 1,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'settings-analysis',
            'parents_code' => 'online-reviews',
            'plans' => 'free-contractortexter',
            'main' => 0,
            'pos' => 1,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'settings-analysis',
            'parents_code' => 'online-reviews',
            'plans' => 'text-contractortexter',
            'main' => 0,
            'pos' => 1,
        ]);*/

        DB::table('pages_menu')->insert([
            'pages_code' => 'settings-reviews',
            'parents_code' => 'online-reviews',
            'plans' => 'none',
            'main' => 0,
            'pos' => 2,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'settings-reviews',
            'parents_code' => 'online-reviews',
            'plans' => 'free-contractortexter',
            'main' => 0,
            'pos' => 2,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'settings-reviews',
            'parents_code' => 'online-reviews',
            'plans' => 'text-contractortexter',
            'main' => 0,
            'pos' => 2,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'settings-reviews',
            'parents_code' => 'online-reviews',
            'plans' => 'star-rating-contractortexter',
            'main' => 0,
            'pos' => 2,
        ]);

        /* DB::table('pages_menu')->insert([
            'pages_code' => 'messages-messages',
            'parents_code' => '',
            'plans' => 'none',
            'main' => 0,
            'pos' => 4,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'messages-messages',
            'parents_code' => '',
            'plans' => 'text-contractortexter',
            'main' => 0,
            'pos' => 4,
        ]); */

        DB::table('pages_menu')->insert([
            'pages_code' => 'messages-add',
            'parents_code' => 'messages-messages',
            'plans' => 'none',
            'main' => 0,
            'pos' => 1,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'messages-add',
            'parents_code' => 'messages-messages',
            'plans' => 'text-contractortexter',
            'main' => 0,
            'pos' => 1,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'dialogs-list',
            'parents_code' => 'messages-messages',
            'plans' => 'none',
            'main' => 0,
            'pos' => 2,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'dialogs-list',
            'parents_code' => 'messages-messages',
            'plans' => 'text-contractortexter',
            'main' => 0,
            'pos' => 2,
        ]);


        DB::table('pages_menu')->insert([
            'pages_code' => 'messages-list',
            'parents_code' => 'messages-messages',
            'plans' => 'none',
            'main' => 0,
            'pos' => 3,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'messages-list',
            'parents_code' => 'messages-messages',
            'plans' => 'text-contractortexter',
            'main' => 0,
            'pos' => 3,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'lists-list',
            'parents_code' => 'messages-messages',
            'plans' => 'none',
            'main' => 0,
            'pos' => 4,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'lists-list',
            'parents_code' => 'messages-messages',
            'plans' => 'text-contractortexter',
            'main' => 0,
            'pos' => 4,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'marketing-settings',
            'parents_code' => 'messages-messages',
            'plans' => 'none',
            'main' => 0,
            'pos' => 5,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'marketing-settings',
            'parents_code' => 'messages-messages',
            'plans' => 'text-contractortexter',
            'main' => 0,
            'pos' => 5,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'ha-user',
            'parents_code' => '',
            'plans' => 'none',
            'main' => 0,
            'pos' => 5,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'ha-user',
            'parents_code' => '',
            'plans' => 'text-contractortexter',
            'main' => 0,
            'pos' => 5,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'ha-user',
            'parents_code' => '',
            'plans' => 'star-rating-contractortexter',
            'main' => 0,
            'pos' => 4,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'ha-user',
            'parents_code' => '',
            'plans' => 'home-advisor-contractortexter',
            'main' => 0,
            'pos' => 4,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'dialogs-list',
            'parents_code' => '',
            'plans' => 'home-advisor-contractortexter',
            'main' => 0,
            'pos' => 4,
        ]);
        
        DB::table('pages_menu')->insert([
            'pages_code' => 'vonage-user',
            'parents_code' => '',
            'plans' => 'vonage-contractortexter',
            'main' => 0,
            'pos' => 1,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'vonage-list',
            'parents_code' => '',
            'plans' => 'vonage-contractortexter',
            'main' => 0,
            'pos' => 2,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'appointment-confirmation',
            'parents_code' => '',
            'plans' => 'text-contractortexter',
            'main' => 0,
            'pos' => 3,
        ]);

        DB::table('pages_menu')->insert([
            'pages_code' => 'appointment-confirmation',
            'parents_code' => '',
            'plans' => 'none',
            'main' => 0,
            'pos' => 3,
        ]);
        
        DB::table('pages_menu')->insert([
            'pages_code' => 'plans-info',
            'parents_code' => '',
            'plans' => 'text-contractortexter',
            'main' => 0,
            'pos' => 5,
        ]);
        
        DB::table('pages_menu')->insert([
            'pages_code' => 'plans-info',
            'parents_code' => '',
            'plans' => 'home-advisor-contractortexter',
            'main' => 0,
            'pos' => 5,
        ]);
        
        DB::table('pages_menu')->insert([
            'pages_code' => 'plans-info',
            'parents_code' => '',
            'plans' => 'free-contractortexter',
            'main' => 0,
            'pos' => 5,
        ]);
        
        DB::table('pages_menu')->insert([
            'pages_code' => 'plans-info',
            'parents_code' => '',
            'plans' => 'star-rating-contractortexter',
            'main' => 0,
            'pos' => 5,
        ]);
    }
}
