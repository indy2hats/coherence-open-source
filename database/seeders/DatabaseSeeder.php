<?php

use Database\Seeders\CheckListCategorySeeder;
use Database\Seeders\CheckListSeeder;
use Database\Seeders\ClientSeeder;
use Database\Seeders\DashboardSeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\DesignationSeeder;
use Database\Seeders\ExpenseTypesSeeder;
use Database\Seeders\FixedOverheadSeeder;
use Database\Seeders\HolidaySeeder;
use Database\Seeders\LeavesSeeder;
use Database\Seeders\NewsLetterSeeder;
use Database\Seeders\OverheadSeeder;
use Database\Seeders\OverheadTypesSeeder;
use Database\Seeders\PayrollSeeder;
use Database\Seeders\ProjectSeeder;
use Database\Seeders\ProjectSettingsSeeder;
use Database\Seeders\RecruitmentSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\SalaryComponentSeeder;
use Database\Seeders\SessionTypeSeeder;
use Database\Seeders\SettingsSeeder;
use Database\Seeders\SocialMediaLinkSeeder;
use Database\Seeders\TaskSeeder;
use Database\Seeders\TaskStatusTypeSeeder;
use Database\Seeders\TaxonomySeeder;
use Database\Seeders\ToolsSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\UserWishSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DepartmentSeeder::class);
        $this->call(DesignationSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(TaxonomySeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(ProjectSettingsSeeder::class);
        $this->call(ProjectSeeder::class);

        $this->call(TaskStatusTypeSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(TaskSeeder::class);
        $this->call(HolidaySeeder::class);
        $this->call(UserWishSeeder::class);
        $this->call(LeavesSeeder::class);
        $this->call(ToolsSeeder::class);
        $this->call(NewsLetterSeeder::class);
        $this->call(RecruitmentSeeder::class);

        $this->call(SalaryComponentSeeder::class);
        $this->call(PayrollSeeder::class);

        $this->call(OverheadTypesSeeder::class);
        $this->call(FixedOverheadSeeder::class);
        $this->call(OverheadSeeder::class);
        $this->call(CheckListCategorySeeder::class);
        $this->call(CheckListSeeder::class);
        $this->call(SessionTypeSeeder::class);
        $this->call(SocialMediaLinkSeeder::class);
        $this->call(DashboardSeeder::class);
        $this->call(ExpenseTypesSeeder::class);
    }
}
