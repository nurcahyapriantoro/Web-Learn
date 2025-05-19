<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Module 1: Basic Conversation
        $module1 = Module::create([
            'title' => 'Basic English Conversation',
            'description' => 'Learn the fundamental skills for everyday English conversations with native speakers.',
            'slug' => 'basic-english-conversation',
            'thumbnail' => 'https://placehold.co/600x400/0A84FF/white?text=English+Conversation',
            'order' => 1,
            'level' => 'beginner',
            'duration_minutes' => 120,
            'is_published' => true,
        ]);

        // Lessons for Module 1
        $lesson1 = Lesson::create([
            'module_id' => $module1->id,
            'title' => 'Introducing Yourself',
            'description' => 'Learn how to confidently introduce yourself in English.',
            'slug' => 'introducing-yourself',
            'type' => 'video',
            'video_url' => 'https://storage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            'duration_minutes' => 15,
            'order' => 1,
            'is_published' => true,
            'is_free' => true,
        ]);

        $lesson2 = Lesson::create([
            'module_id' => $module1->id,
            'title' => 'Small Talk Essentials',
            'description' => 'Master the art of small talk with these essential phrases and techniques.',
            'slug' => 'small-talk-essentials',
            'type' => 'mixed',
            'content' => '<p>Small talk is an important part of social interaction in English-speaking cultures. It helps to establish rapport before moving on to more substantive conversation topics.</p><h3>Common Small Talk Topics</h3><ul><li>The weather</li><li>Sports</li><li>Current events (non-controversial)</li><li>Travel</li><li>Food</li></ul>',
            'video_url' => 'https://storage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
            'duration_minutes' => 20,
            'order' => 2,
            'is_published' => true,
        ]);

        // Create quiz for Lesson 2
        $quiz1 = Quiz::create([
            'lesson_id' => $lesson2->id,
            'title' => 'Small Talk Practice Quiz',
            'description' => 'Test your knowledge of small talk expressions and strategies.',
            'pass_score' => 70,
            'is_published' => true,
        ]);

        // Questions for Quiz 1
        $question1 = QuizQuestion::create([
            'quiz_id' => $quiz1->id,
            'question_text' => 'Which is NOT typically a good small talk topic?',
            'question_type' => 'multiple_choice',
            'explanation' => 'Politics can be divisive and is generally avoided in small talk situations.',
            'points' => 1,
            'order' => 1,
        ]);

        // Answers for Question 1
        QuizAnswer::create([
            'quiz_question_id' => $question1->id,
            'answer_text' => 'The weather',
            'is_correct' => false,
            'order' => 1,
        ]);

        QuizAnswer::create([
            'quiz_question_id' => $question1->id,
            'answer_text' => 'Politics',
            'is_correct' => true,
            'order' => 2,
        ]);

        QuizAnswer::create([
            'quiz_question_id' => $question1->id,
            'answer_text' => 'Recent travel experiences',
            'is_correct' => false,
            'order' => 3,
        ]);

        QuizAnswer::create([
            'quiz_question_id' => $question1->id,
            'answer_text' => 'Sports',
            'is_correct' => false,
            'order' => 4,
        ]);

        // Module 2: Business English
        $module2 = Module::create([
            'title' => 'Business English Essentials',
            'description' => 'Master professional English communication skills for workplace success.',
            'slug' => 'business-english-essentials',
            'thumbnail' => 'https://placehold.co/600x400/0A84FF/white?text=Business+English',
            'order' => 2,
            'level' => 'intermediate',
            'duration_minutes' => 180,
            'is_published' => true,
        ]);

        // Lessons for Module 2
        $lesson3 = Lesson::create([
            'module_id' => $module2->id,
            'title' => 'Email Writing',
            'description' => 'Learn to write clear, effective, and professional emails in English.',
            'slug' => 'email-writing',
            'type' => 'text',
            'content' => '<h2>Professional Email Structure</h2><p>A well-written business email typically includes:</p><ul><li><strong>Clear subject line</strong>: Concise and relevant to the content</li><li><strong>Appropriate greeting</strong>: Formal or semi-formal depending on your relationship</li><li><strong>Brief introduction</strong>: State your purpose clearly</li><li><strong>Main content</strong>: Keep it concise and to the point</li><li><strong>Action items</strong>: Clearly state what you expect from the recipient</li><li><strong>Professional closing</strong>: Include appropriate sign-off and signature</li></ul>',
            'duration_minutes' => 25,
            'order' => 1,
            'is_published' => true,
        ]);

        $lesson4 = Lesson::create([
            'module_id' => $module2->id,
            'title' => 'Presentation Skills',
            'description' => 'Develop confidence and clarity when giving business presentations in English.',
            'slug' => 'presentation-skills',
            'type' => 'video',
            'video_url' => 'https://storage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
            'duration_minutes' => 30,
            'has_subtitles' => true,
            'subtitle_url' => 'https://example.com/subtitles/presentation-skills.vtt',
            'order' => 2,
            'is_published' => true,
        ]);

        $lesson5 = Lesson::create([
            'module_id' => $module2->id,
            'title' => 'Meeting Practice',
            'description' => 'Practice English phrases and vocabulary for effective business meetings.',
            'slug' => 'meeting-practice',
            'type' => 'zoom',
            'zoom_link' => 'https://zoom.us/j/example',
            'duration_minutes' => 45,
            'order' => 3,
            'is_published' => true,
        ]);
    }
}
