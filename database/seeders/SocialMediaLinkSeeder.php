<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SocialMediaLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $socialMediaLinks = [
            'company_website_url' => [
                'label' => 'Website Url',
                'slug' => 'company_website_url',
                'value' => 'https://www.2hatslogic.com/'
            ],
            'company_linkedin_link' => [
                'label' => 'Linked In Link',
                'slug' => 'company_linkedin_link',
                'value' => config('general.social-media-links.linked-in')
            ],
            'company_facebook_link' => [
                'label' => 'Facebook Link',
                'slug' => 'company_facebook_link',
                'value' => config('general.social-media-links.facebook')
            ],
            'company_instagram_link' => [
                'label' => 'Instagram Link',
                'slug' => 'company_instagram_link',
                'value' => config('general.social-media-links.instagram')
            ],
            'company_twitter_link' => [
                'label' => 'Twitter Link',
                'slug' => 'company_twitter_link',
                'value' => config('general.social-media-links.twitter')
            ]
        ];
        foreach ($socialMediaLinks as $detailItem) {
            Settings::create([
                'label' => $detailItem['label'],
                'slug' => $detailItem['slug'],
                'value' => $detailItem['value']
            ]);
        }
    }
}
