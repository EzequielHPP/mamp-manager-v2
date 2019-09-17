<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManagerSettings extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'setting' => 'basic.mamp_location',
                'value' => '/Applications/MAMP/',
            ],
            [
                'setting' => 'basic.mamp_location.apache',
                'value' => '/Applications/MAMP/conf/apache/extra/',
            ],
            [
                'setting' => 'basic.template_http',
                'value' => 'templates.blocks.http',
            ],
            [
                'setting' => 'basic.template_https',
                'value' => 'templates.blocks.https',
            ],
            [
                'setting' => 'basic.template_vhosts',
                'value' => 'templates.httpd-vhosts',
            ],
            [
                'setting' => 'basic.template_ssl',
                'value' => 'templates.httpd-ssl',
            ],
            [
                'setting' => 'basic.certificate.crt',
                'value' => resource_path('certificates/server.crt'),
            ],
            [
                'setting' => 'basic.certificate.key',
                'value' => resource_path('certificates/server.key'),
            ],
            [
                'setting' => 'basic.ssl_enabled',
                'value' => 'true',
            ],
            [
                'setting' => 'basic.settings_default_icon',
                'value' => 'home',
            ],
            [
                'setting' => 'basic.mamp_vhosts_file_name',
                'value' => 'httpd-vhosts.conf',
            ],
            [
                'setting' => 'basic.mamp_ssl_file_name',
                'value' => 'httpd-ssl.conf',
            ],
        ];
        foreach ($settings as $setting) {
            $setting['created_at'] = date('Y-m-d H:i:s');
            $setting['updated_at'] = date('Y-m-d H:i:s');
            DB::table('settings')
              ->insert($setting);
        }
    }
}
