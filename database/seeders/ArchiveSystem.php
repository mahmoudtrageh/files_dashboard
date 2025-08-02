<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Archive;
use Illuminate\Support\Facades\Storage;

class ArchiveSystem extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample categories for different file types
        $categories = [
            [
                'name' => 'Documents',
                'slug' => 'documents',
                'is_active' => true,
                'sort_order' => 1,
                'children' => [
                    [
                        'name' => 'Contracts',
                        'slug' => 'contracts',
                        'sort_order' => 1
                    ],
                    [
                        'name' => 'Reports',
                        'slug' => 'reports',
                        'sort_order' => 2
                    ],
                    [
                        'name' => 'Invoices',
                        'slug' => 'invoices',
                        'sort_order' => 3
                    ]
                ]
            ],
            [
                'name' => 'Images',
                'slug' => 'images',
                'is_active' => true,
                'sort_order' => 2,
                'children' => [
                    [
                        'name' => 'Marketing Materials',
                        'slug' => 'marketing-materials',

                        'sort_order' => 1
                    ],
                    [
                        'name' => 'Product Photos',
                        'slug' => 'product-photos',

                        'sort_order' => 2
                    ],
                    [
                        'name' => 'Screenshots',
                        'slug' => 'screenshots',

                        'sort_order' => 3
                    ]
                ]
            ],
            [
                'name' => 'Media',
                'slug' => 'media',

                'is_active' => true,
                'sort_order' => 3,
                'children' => [
                    [
                        'name' => 'Videos',
                        'slug' => 'videos',

                        'sort_order' => 1
                    ],
                    [
                        'name' => 'Audio',
                        'slug' => 'audio',

                        'sort_order' => 2
                    ]
                ]
            ],
            [
                'name' => 'Archives',
                'slug' => 'archives',

                'is_active' => true,
                'sort_order' => 4,
                'children' => [
                    [
                        'name' => 'Backups',
                        'slug' => 'backups',

                        'sort_order' => 1
                    ],
                    [
                        'name' => 'Software',
                        'slug' => 'software',

                        'sort_order' => 2
                    ]
                ]
            ],
            [
                'name' => 'Templates',
                'slug' => 'templates',

                'is_active' => true,
                'sort_order' => 5,
                'children' => [
                    [
                        'name' => 'Email Templates',
                        'slug' => 'email-templates',

                        'sort_order' => 1
                    ],
                    [
                        'name' => 'Document Templates',
                        'slug' => 'document-templates',

                        'sort_order' => 2
                    ]
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);

            $category = Category::create($categoryData);

            foreach ($children as $childData) {
                $childData['parent_id'] = $category->id;
                $childData['is_active'] = true;
                Category::create($childData);
            }
        }

        $this->command->info('Archive categories created successfully!');

        // Create sample archives (optional - only if you want sample data)
        if ($this->command->confirm('Do you want to create sample archive records? (Note: No actual files will be uploaded)')) {
            $this->createSampleArchives();
        }
    }

    /**
     * Create sample archive records without actual files
     */
    private function createSampleArchives(): void
    {
        $categories = Category::whereNotNull('parent_id')->get();

        $sampleArchives = [
            [
                'title' => 'Company Branding Guidelines',

                'status' => 'active'
            ],
            [
                'title' => 'Q4 Financial Report',
                'status' => 'active'
            ],
            [
                'title' => 'Product Catalog 2024',
                'status' => 'active'
            ],
            [
                'title' => 'Training Video - New Employees',
                'status' => 'active'
            ],
            [
                'title' => 'Website Backup - January 2024',
                'status' => 'archived'
            ],
            [
                'title' => 'Social Media Templates',
                'status' => 'active'
            ],
            [
                'title' => 'Client Presentation Draft',
                'status' => 'draft'
            ],
            [
                'title' => 'Logo Variations Pack',
                'status' => 'active'
            ]
        ];

        foreach ($sampleArchives as $archiveData) {
            $archiveData['category_id'] = $categories->random()->id;
            Archive::create($archiveData);
        }

        $this->command->info('Sample archive records created successfully!');
        $this->command->warn('Note: These are sample records without actual files. You can delete them later if needed.');
    }
}
