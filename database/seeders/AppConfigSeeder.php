<?php

namespace Database\Seeders;

use App\Models\AppConfig;
use Illuminate\Database\Seeder;


class AppConfigSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        AppConfig::create(['key' => 'cover-sidebar-user', 'value' => '', 'description' => 'Define o cover da foto de perfil', 'required' => true]);
        AppConfig::create(['key' => 'banner_principal', 'value' => '', 'description' => 'Define o banner obrigatório', 'required' => true]);
        AppConfig::create(['key' => 'app_name', 'value' => '', 'description' => 'Nome da aplicação mostrada no sistema', 'required' => true]);
        AppConfig::create(['key' => 'icon_app', 'value' => '', 'description' => 'Icone da aplicacao recomendado 255x255', 'required' => true]);
        AppConfig::create(['key' => 'icon_user_default', 'value' => '', 'description' => 'Icone do usuario padrão recomendado 35x35', 'required' => true]);
        AppConfig::create(['key' => 'description_user', 'value' => '', 'description' => 'Descrição default do usuario', 'required' => true]);
        // AppConfig::create(['key' => '', 'value' => '', 'description' => '']);

    }
}
