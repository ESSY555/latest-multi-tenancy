<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolNews;
use App\Models\User;
use App\Models\Branch;

class SchoolNewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first super admin user and branch
        $admin = User::where('is_super_admin', true)->first();
        $branch = Branch::first();

        if (!$admin || !$branch) {
            $this->command->warn('No super admin user or branch found. Skipping news seeding.');
            return;
        }

        $newsArticles = [
            [
                'title' => 'Welcome to the New Academic Year 2024-2025',
                'excerpt' => 'We are excited to welcome all students, parents, and staff to another promising academic year filled with opportunities for growth and achievement.',
                'content' => '<p>Dear Students, Parents, and Staff,</p>
                
                <p>Welcome to the 2024-2025 academic year! We are thrilled to begin this journey together and look forward to another year of excellence, innovation, and community building.</p>
                
                <h3>What\'s New This Year</h3>
                <ul>
                    <li><strong>Enhanced Technology Integration:</strong> New smart classrooms and digital learning tools</li>
                    <li><strong>Expanded Sports Programs:</strong> Introduction of new athletic teams and facilities</li>
                    <li><strong>Community Outreach:</strong> Increased partnerships with local organizations</li>
                    <li><strong>Academic Excellence:</strong> New advanced placement courses and enrichment programs</li>
                </ul>
                
                <p>We encourage all students to take advantage of these opportunities and make the most of their educational journey with us.</p>
                
                <p>Here\'s to a successful and fulfilling academic year!</p>
                
                <p>Best regards,<br>
                The School Administration Team</p>',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Student Achievement: Science Fair Winners Announced',
                'excerpt' => 'Congratulations to our outstanding students who excelled at the annual Science Fair, showcasing innovative projects and scientific thinking.',
                'content' => '<p>We are proud to announce the winners of our annual Science Fair, which took place last week. The event showcased over 50 innovative projects from students across all grade levels.</p>
                
                <h3>First Place Winners</h3>
                <ul>
                    <li><strong>Senior Division:</strong> Sarah Johnson - "Sustainable Energy Solutions for Rural Communities"</li>
                    <li><strong>Junior Division:</strong> Michael Chen - "Smart Home Automation Using IoT"</li>
                    <li><strong>Elementary Division:</strong> Emma Rodriguez - "The Effects of Music on Plant Growth"</li>
                </ul>
                
                <h3>Special Recognition</h3>
                <p>Several projects received special recognition for their environmental impact and innovative approach to solving real-world problems.</p>
                
                <p>We congratulate all participants for their hard work and dedication to scientific inquiry!</p>',
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Parent-Teacher Conference Schedule Released',
                'excerpt' => 'Important information about upcoming parent-teacher conferences and how to schedule your appointments.',
                'content' => '<p>We are pleased to announce the schedule for our upcoming Parent-Teacher Conferences. These meetings provide an excellent opportunity to discuss your child\'s progress and address any concerns.</p>
                
                <h3>Conference Dates</h3>
                <ul>
                    <li><strong>Elementary School:</strong> October 15-16, 2024</li>
                    <li><strong>Middle School:</strong> October 17-18, 2024</li>
                    <li><strong>High School:</strong> October 19-20, 2024</li>
                </ul>
                
                <h3>How to Schedule</h3>
                <p>Conference scheduling will open online on September 30th. You will receive an email with detailed instructions on how to book your preferred time slots.</p>
                
                <h3>What to Expect</h3>
                <p>Each conference session will last 15 minutes, during which you can discuss your child\'s academic progress, behavior, and any specific concerns with their teachers.</p>
                
                <p>We look forward to meeting with you!</p>',
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Upcoming School Events and Activities',
                'excerpt' => 'Stay updated with all the exciting events, activities, and important dates for the upcoming months.',
                'content' => '<p>Mark your calendars! Here are the key events and activities happening at our school in the coming months.</p>
                
                <h3>October Events</h3>
                <ul>
                    <li><strong>October 5:</strong> School Spirit Day</li>
                    <li><strong>October 12:</strong> Fall Sports Tournament</li>
                    <li><strong>October 15-16:</strong> Elementary Parent-Teacher Conferences</li>
                    <li><strong>October 25:</strong> Halloween Costume Parade</li>
                    <li><strong>October 30:</strong> School Board Meeting</li>
                </ul>
                
                <h3>November Events</h3>
                <ul>
                    <li><strong>November 8:</strong> Veterans Day Assembly</li>
                    <li><strong>November 15:</strong> Thanksgiving Food Drive</li>
                    <li><strong>November 22:</strong> Early Dismissal for Thanksgiving Break</li>
                    <li><strong>November 25-29:</strong> Thanksgiving Break (No School)</li>
                </ul>
                
                <p>For more details about any of these events, please check our school calendar or contact the main office.</p>',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'New Library Resources and Digital Learning Tools',
                'excerpt' => 'Discover the latest additions to our library collection and new digital learning resources available to all students.',
                'content' => '<p>We are excited to announce significant improvements to our library and digital learning resources, made possible through generous donations and grants.</p>
                
                <h3>New Library Resources</h3>
                <ul>
                    <li><strong>Physical Books:</strong> 500+ new titles across all subjects and reading levels</li>
                    <li><strong>E-Books:</strong> Access to over 10,000 digital books through our online platform</li>
                    <li><strong>Audio Books:</strong> New collection for students with different learning preferences</li>
                    <li><strong>Reference Materials:</strong> Updated encyclopedias, dictionaries, and research guides</li>
                </ul>
                
                <h3>Digital Learning Tools</h3>
                <ul>
                    <li><strong>Online Databases:</strong> Access to academic journals and research materials</li>
                    <li><strong>Learning Apps:</strong> Educational applications for various subjects</li>
                    <li><strong>Virtual Reality:</strong> VR headsets for immersive learning experiences</li>
                    <li><strong>Coding Platforms:</strong> Tools for learning programming and computer science</li>
                </ul>
                
                <p>All students will receive training on how to use these new resources during their library orientation sessions.</p>',
                'is_published' => false, // This will be a draft
                'published_at' => null,
            ],
        ];

        foreach ($newsArticles as $articleData) {
            SchoolNews::create(array_merge($articleData, [
                'author_id' => $admin->id,
                'branch_id' => $branch->id,
            ]));
        }

        $this->command->info('Sample school news articles created successfully!');
    }
}
