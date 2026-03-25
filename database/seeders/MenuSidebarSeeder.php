<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuSideBar;
use Illuminate\Database\Eloquent\Model;


class MenuSidebarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Model::unguard();

        MenuSideBar::create([
            'description' => 'Gestão de Roles',
            'icon' => "fa-user-tag",
            'module' => "",
            'menu_above' => "",
            'level' => 0,
            'route' => "roles",
            'acl' => "roles-all",
            'order' => 1010,
            'active' => true,
            'style' => 'color: cyan;',
        ]);

        MenuSideBar::create([
            'description' => 'Gestão de Permissões',
            'icon' => "fa-user-shield",
            'module' => "",
            'menu_above' => "",
            'level' => 0,
            'route' => "permissions",
            'acl' => "permissions-all",
            'order' => 1011,
            'active' => true,
            'style' => 'color: cyan;',
        ]);

        MenuSideBar::create([
            'description' => 'Roles/Usuarios',
            'icon' => "fa-project-diagram",
            'module' => "",
            'menu_above' => "",
            'level' => 0,
            'route' => "user-roles",
            'acl' => "user-roles-all",
            'order' => 1012,
            'active' => true,
            'style' => 'color: cyan;',
        ]);

        MenuSideBar::create([
            'description' => 'Configurações do sistema',
            'icon' => "fa-tools",
            'module' => "",
            'menu_above' => "",
            'level' => 0,
            'route' => "config",
            'acl' => "configs-all",
            'order' => 1003,
            'active' => true,
            'style' => 'color: cyan;',
        ]);

        MenuSideBar::create([
            'description' => 'Gestão de Usuários',
            'icon' => "fa-user",
            'module' => "",
            'menu_above' => "",
            'level' => 0,
            'route' => "users",
            'acl' => "user-all",
            'order' => 1002,
            'active' => true,
            'style' => 'color: cyan;',
        ]);

        MenuSideBar::create([
            'description' => 'Gestão de Logs',
            'icon' => "fa-list-alt",
            'module' => "",
            'menu_above' => "",
            'level' => 0,
            'route' => "audit-logs",
            'acl' => "audit-all",
            'order' => 1004,
            'active' => true,
            'style' => 'color: cyan;',
        ]);

        MenuSideBar::create([
            'description' => 'Meu Perfil',
            'icon' => "fa-user ",
            'module' => "",
            'menu_above' => "",
            'level' => 0,
            'route' => "profile",
            'acl' => "youself",
            'order' => 0,
            'active' => true,
            'style' => '',
        ]);

        MenuSideBar::create([
            'description' => 'Enviar notificações',
            'icon' => "fa-message ",
            'module' => "",
            'menu_above' => "",
            'level' => 0,
            'route' => "notifications/send",
            'acl' => "notification-all",
            'order' => 1002,
            'active' => true,
            'style' => 'color: cyan;',
        ]);

        MenuSideBar::create([
            'description' => 'Gestão de Menus',
            'icon' => "fa-list-ul ",
            'module' => "",
            'menu_above' => "",
            'level' => 0,
            'route' => "menu",
            'acl' => "menu-all",
            'order' => 1005,
            'active' => true,
            'style' => 'color: cyan;',
        ]);
    }
}
