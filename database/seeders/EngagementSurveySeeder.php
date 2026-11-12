<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Survey;
use App\Models\SurveyCategory;
use App\Models\SurveyPeriod;
use App\Models\SurveyDimension;
use App\Models\QuestionTemplate;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EngagementSurveySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin User
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nik' => 'ADM001',
                'name' => 'Administrator HRGA',
                'email' => 'admin@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'department' => 'HRGA',
                'position' => 'Manager',
                'employment_status' => 'Tetap',
                'tenure' => '11 - 15 tahun',
                'gender' => 'Laki-laki',
                'age' => 38,
                'education' => 'S1',
            ]
        );

        // 2. Create Dummy Employees for Testing Login
        // 2. Create Dummy Employees for Testing Login & Survey Data
        $dummyEmployees = [
            // PM1 (Paper Machine 1)
            [
                'nik' => 'ADP00101',
                'username' => 'andi.pratama',
                'name' => 'Andi Pratama',
                'email' => 'andi.pratama@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'PM1',
                'position' => 'Operator',
                'employment_status' => 'Tetap',
                'tenure' => '1 - 5 tahun',
                'gender' => 'Laki-laki',
                'age' => 28,
                'education' => 'SMA/ SMK Sederjat',
            ],
            [
                'nik' => 'ADP00102',
                'username' => 'bambang.wijaya',
                'name' => 'Bambang Wijaya',
                'email' => 'bambang.wijaya@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'PM1',
                'position' => 'Karu',
                'employment_status' => 'Tetap',
                'tenure' => '6 - 10 tahun',
                'gender' => 'Laki-laki',
                'age' => 36,
                'education' => 'D1 - D2 - D3 - D4',
            ],
            [
                'nik' => 'ADP00103',
                'username' => 'eko.prasetyo',
                'name' => 'Eko Prasetyo',
                'email' => 'eko.prasetyo@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'PM1',
                'position' => 'Operator',
                'employment_status' => 'Kontrak',
                'tenure' => 'kurang dari 1 tahun',
                'gender' => 'Laki-laki',
                'age' => 23,
                'education' => 'SMA/ SMK Sederjat',
            ],

            // MRK (Marketing)
            [
                'nik' => 'ADP00104',
                'username' => 'siti.aisyah',
                'name' => 'Siti Aisyah',
                'email' => 'siti.aisyah@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'MRK',
                'position' => 'Staf',
                'employment_status' => 'Tetap',
                'tenure' => '1 - 5 tahun',
                'gender' => 'Perempuan',
                'age' => 26,
                'education' => 'S1',
            ],
            [
                'nik' => 'ADP00105',
                'username' => 'hendra.kurniawan',
                'name' => 'Hendra Kurniawan',
                'email' => 'hendra.kurniawan@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'MRK',
                'position' => 'Ast. Manager',
                'employment_status' => 'Tetap',
                'tenure' => '6 - 10 tahun',
                'gender' => 'Laki-laki',
                'age' => 35,
                'education' => 'S1',
            ],

            // FA (Finance & Accounting)
            [
                'nik' => 'ADP00106',
                'username' => 'budi.santoso',
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'FA',
                'position' => 'Ast. Manager',
                'employment_status' => 'Tetap',
                'tenure' => '6 - 10 tahun',
                'gender' => 'Laki-laki',
                'age' => 34,
                'education' => 'S1',
            ],
            [
                'nik' => 'ADP00107',
                'username' => 'fitri.handayani',
                'name' => 'Fitri Handayani',
                'email' => 'fitri.handayani@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'FA',
                'position' => 'Staf',
                'employment_status' => 'Tetap',
                'tenure' => '1 - 5 tahun',
                'gender' => 'Perempuan',
                'age' => 27,
                'education' => 'S1',
            ],

            // HRGA (Human Resources & General Affairs)
            [
                'nik' => 'ADP00108',
                'username' => 'dewi.lestari',
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'HRGA',
                'position' => 'Karu',
                'employment_status' => 'Tetap',
                'tenure' => '6 - 10 tahun',
                'gender' => 'Perempuan',
                'age' => 31,
                'education' => 'S1',
            ],
            [
                'nik' => 'ADP00109',
                'username' => 'arif.hidayat',
                'name' => 'Arif Hidayat',
                'email' => 'arif.hidayat@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'HRGA',
                'position' => 'Staf',
                'employment_status' => 'Kontrak',
                'tenure' => 'kurang dari 1 tahun',
                'gender' => 'Laki-laki',
                'age' => 25,
                'education' => 'S1',
            ],

            // IT (Information Technology)
            [
                'nik' => 'ADP00110',
                'username' => 'rizky.maulana',
                'name' => 'Rizky Maulana',
                'email' => 'rizky.maulana@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'IT',
                'position' => 'Staf',
                'employment_status' => 'Kontrak',
                'tenure' => 'kurang dari 1 tahun',
                'gender' => 'Laki-laki',
                'age' => 24,
                'education' => 'D1 - D2 - D3 - D4',
            ],
            [
                'nik' => 'ADP00111',
                'username' => 'fajar.ramadhan',
                'name' => 'Fajar Ramadhan',
                'email' => 'fajar.ramadhan@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'IT',
                'position' => 'Karu',
                'employment_status' => 'Tetap',
                'tenure' => '6 - 10 tahun',
                'gender' => 'Laki-laki',
                'age' => 32,
                'education' => 'S1',
            ],

            // PPIC (Production Planning & Inventory Control)
            [
                'nik' => 'ADP00112',
                'username' => 'agus.setiawan',
                'name' => 'Agus Setiawan',
                'email' => 'agus.setiawan@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'PPIC',
                'position' => 'Karu',
                'employment_status' => 'Tetap',
                'tenure' => '11 - 15 tahun',
                'gender' => 'Laki-laki',
                'age' => 39,
                'education' => 'S1',
            ],
            [
                'nik' => 'ADP00113',
                'username' => 'dian.puspita',
                'name' => 'Dian Puspitasari',
                'email' => 'dian.puspita@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'PPIC',
                'position' => 'Staf',
                'employment_status' => 'Tetap',
                'tenure' => '1 - 5 tahun',
                'gender' => 'Perempuan',
                'age' => 29,
                'education' => 'S1',
            ],

            // QC (Quality Control)
            [
                'nik' => 'ADP00114',
                'username' => 'nurul.hidayati',
                'name' => 'Nurul Hidayati',
                'email' => 'nurul.hidayati@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'QC',
                'position' => 'Staf',
                'employment_status' => 'Tetap',
                'tenure' => '1 - 5 tahun',
                'gender' => 'Perempuan',
                'age' => 27,
                'education' => 'D1 - D2 - D3 - D4',
            ],
            [
                'nik' => 'ADP00115',
                'username' => 'surya.darma',
                'name' => 'Surya Darma',
                'email' => 'surya.darma@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'QC',
                'position' => 'Operator',
                'employment_status' => 'Tetap',
                'tenure' => '6 - 10 tahun',
                'gender' => 'Laki-laki',
                'age' => 33,
                'education' => 'SMA/ SMK Sederjat',
            ],

            // UTILITY
            [
                'nik' => 'ADP00116',
                'username' => 'teguh.prakoso',
                'name' => 'Teguh Prakoso',
                'email' => 'teguh.prakoso@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'UTILITY',
                'position' => 'Operator',
                'employment_status' => 'Tetap',
                'tenure' => '1 - 5 tahun',
                'gender' => 'Laki-laki',
                'age' => 30,
                'education' => 'SMA/ SMK Sederjat',
            ],
            [
                'nik' => 'ADP00117',
                'username' => 'wahyu.nugroho',
                'name' => 'Wahyu Nugroho',
                'email' => 'wahyu.nugroho@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'UTILITY',
                'position' => 'Karu',
                'employment_status' => 'Tetap',
                'tenure' => '11 - 15 tahun',
                'gender' => 'Laki-laki',
                'age' => 41,
                'education' => 'D1 - D2 - D3 - D4',
            ],

            // TEKNIK (Maintenance & Engineering)
            [
                'nik' => 'ADP00118',
                'username' => 'ridwan.kamil',
                'name' => 'Ridwan Kamil',
                'email' => 'ridwan.kamil@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'TEKNIK',
                'position' => 'Operator',
                'employment_status' => 'Tetap',
                'tenure' => '1 - 5 tahun',
                'gender' => 'Laki-laki',
                'age' => 29,
                'education' => 'SMA/ SMK Sederjat',
            ],
            [
                'nik' => 'ADP00119',
                'username' => 'yusuf.mansur',
                'name' => 'Yusuf Habibi',
                'email' => 'yusuf.habibi@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'TEKNIK',
                'position' => 'Ast. Manager',
                'employment_status' => 'Tetap',
                'tenure' => '16 - 20 tahun',
                'gender' => 'Laki-laki',
                'age' => 44,
                'education' => 'S1',
            ],

            // PCH (Purchasing & Procurement)
            [
                'nik' => 'ADP00120',
                'username' => 'ratna.sari',
                'name' => 'Ratna Sari Dewi',
                'email' => 'ratna.sari@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'PCH',
                'position' => 'Staf',
                'employment_status' => 'Tetap',
                'tenure' => '1 - 5 tahun',
                'gender' => 'Perempuan',
                'age' => 28,
                'education' => 'S1',
            ],
            [
                'nik' => 'ADP00121',
                'username' => 'ilham.ramli',
                'name' => 'Ilham Ramli',
                'email' => 'ilham.ramli@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'PCH',
                'position' => 'Karu',
                'employment_status' => 'Tetap',
                'tenure' => '6 - 10 tahun',
                'gender' => 'Laki-laki',
                'age' => 35,
                'education' => 'S1',
            ],

            // Additional active staff
            [
                'nik' => 'ADP00122',
                'username' => 'maya.anggraeni',
                'name' => 'Maya Anggraeni',
                'email' => 'maya.anggraeni@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'MRK',
                'position' => 'Staf',
                'employment_status' => 'Kontrak',
                'tenure' => 'kurang dari 1 tahun',
                'gender' => 'Perempuan',
                'age' => 24,
                'education' => 'S1',
            ],
            [
                'nik' => 'ADP00123',
                'username' => 'dedi.irawan',
                'name' => 'Dedi Irawan',
                'email' => 'dedi.irawan@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'PM1',
                'position' => 'Operator',
                'employment_status' => 'Tetap',
                'tenure' => '6 - 10 tahun',
                'gender' => 'Laki-laki',
                'age' => 33,
                'education' => 'SMA/ SMK Sederjat',
            ],
            [
                'nik' => 'ADP00124',
                'username' => 'linda.novita',
                'name' => 'Linda Novitasari',
                'email' => 'linda.novita@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'HRGA',
                'position' => 'Staf',
                'employment_status' => 'Tetap',
                'tenure' => '1 - 5 tahun',
                'gender' => 'Perempuan',
                'age' => 27,
                'education' => 'S1',
            ],
            [
                'nik' => 'ADP00125',
                'username' => 'hasan.basri',
                'name' => 'Hasan Basri',
                'email' => 'hasan.basri@adiprima.co.id',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department' => 'QC',
                'position' => 'Karu',
                'employment_status' => 'Tetap',
                'tenure' => '11 - 15 tahun',
                'gender' => 'Laki-laki',
                'age' => 40,
                'education' => 'D1 - D2 - D3 - D4',
            ]
        ];

        $employeeModels = [];
        foreach ($dummyEmployees as $empData) {
            $employeeModels[] = User::updateOrCreate(['username' => $empData['username']], $empData);
        }

        // 3. Create Master Survey Categories (CRUDable Master Data)
        $catBudayaKerja = SurveyCategory::updateOrCreate(
            ['slug' => 'survey-budaya-kerja'],
            [
                'name' => 'Survey Budaya Kerja',
                'description' => 'Pengukuran penerapan nilai-nilai AKHLAK, keselamatan kerja, integritas, dan budaya kerja PT Adiprima Suraprinta.',
                'icon' => 'bi-people-fill',
                'color' => '#2563EB',
                'order' => 1,
                'is_active' => true,
            ]
        );

        $catEngagement = SurveyCategory::updateOrCreate(
            ['slug' => 'employee-engagement-survey'],
            [
                'name' => 'Employee Engagement Survey',
                'description' => 'Instrumen komprehensif mengukur tingkat keterlibatan, loyalitas, dan motivasi kerja karyawan.',
                'icon' => 'bi-graph-up-arrow',
                'color' => '#059669',
                'order' => 2,
                'is_active' => true,
            ]
        );

        $catCustomerSat = SurveyCategory::updateOrCreate(
            ['slug' => 'customer-satisfaction-survey'],
            [
                'name' => 'Customer Satisfaction Survey',
                'description' => 'Survei indeks kepuasan pelanggan eksternal & mitra kerja terhadap kualitas produk kertas dan pelayanan.',
                'icon' => 'bi-award-fill',
                'color' => '#D97706',
                'order' => 3,
                'is_active' => true,
            ]
        );

        // 4. Create Master Dimensions (Attached to Survey Categories)
        // Dimensions for Employee Engagement Survey
        $dimBasicNeeds = SurveyDimension::updateOrCreate(
            ['name' => 'Basic Needs'],
            [
                'survey_category_id' => $catEngagement->id,
                'code' => 'BN',
                'description' => 'Kebutuhan dasar karyawan mencakup rasa aman, gaji, kompensasi, dan fasilitas kerja.',
                'color' => '#2563EB',
                'order' => 1,
            ]
        );

        $dimIndividual = SurveyDimension::updateOrCreate(
            ['name' => 'Individual Contribution'],
            [
                'survey_category_id' => $catEngagement->id,
                'code' => 'IC',
                'description' => 'Kontribusi individu, pengakuan, pengembangan diri, dan tanggung jawab kerja.',
                'color' => '#7C3AED',
                'order' => 2,
            ]
        );

        $dimTeamwork = SurveyDimension::updateOrCreate(
            ['name' => 'Teamwork'],
            [
                'survey_category_id' => $catEngagement->id,
                'code' => 'TW',
                'description' => 'Kerjasama tim, komunikasi, kepedulian antar rekan kerja, dan keselarasan tujuan.',
                'color' => '#059669',
                'order' => 3,
            ]
        );

        $dimGrowth = SurveyDimension::updateOrCreate(
            ['name' => 'Growth'],
            [
                'survey_category_id' => $catEngagement->id,
                'code' => 'GW',
                'description' => 'Peluang tumbuh, pengembangan karir, kepemimpinan dan supervisi atasan.',
                'color' => '#D97706',
                'order' => 4,
            ]
        );

        $dimGeneral = SurveyDimension::updateOrCreate(
            ['name' => 'General / Umum'],
            [
                'survey_category_id' => $catEngagement->id,
                'code' => 'GN',
                'description' => 'Pertanyaan umum mengenai retensi, kepuasan menyeluruh, dan saran perbaikan.',
                'color' => '#4B5563',
                'order' => 5,
            ]
        );

        // Dimensions for Survey Budaya Kerja
        $dimBudaya = SurveyDimension::updateOrCreate(
            ['name' => 'Budaya & Nilai Kerja'],
            [
                'survey_category_id' => $catBudayaKerja->id,
                'code' => 'BK',
                'description' => 'Penerapan nilai-nilai inti, etika kerja, dan budaya kerja perusahaan.',
                'color' => '#EC4899',
                'order' => 1,
            ]
        );

        $dimK3 = SurveyDimension::updateOrCreate(
            ['name' => 'Keselamatan & Lingkungan Kerja (K3)'],
            [
                'survey_category_id' => $catBudayaKerja->id,
                'code' => 'K3',
                'description' => 'Kepatuhan terhadap SOP K3, kebersihan lingkungan 5R, dan keselamatan kerja.',
                'color' => '#10B981',
                'order' => 2,
            ]
        );

        // Dimensions for Customer Satisfaction Survey
        $dimKualitas = SurveyDimension::updateOrCreate(
            ['name' => 'Kualitas Produk & Layanan'],
            [
                'survey_category_id' => $catCustomerSat->id,
                'code' => 'KP',
                'description' => 'Konsistensi gramatur kertas, ketepatan pengiriman, dan respon layanan teknis.',
                'color' => '#6366F1',
                'order' => 1,
            ]
        );

        $dimPelayanan = SurveyDimension::updateOrCreate(
            ['name' => 'Pelayanan & Komunikasi'],
            [
                'survey_category_id' => $catCustomerSat->id,
                'code' => 'CS',
                'description' => 'Kecepatan respon customer care, ketepatan administrasi, dan koordinasi.',
                'color' => '#F59E0B',
                'order' => 2,
            ]
        );

        // 5. Create Surveys by Category (Active & Archived across editions)
        // Category 1: Survey Budaya Kerja
        $surveyBudaya2026 = Survey::updateOrCreate(
            ['slug' => 'survey-budaya-kerja'],
            [
                'title' => 'Survey Budaya Kerja 2026',
                'category' => $catBudayaKerja->name,
                'survey_category_id' => $catBudayaKerja->id,
                'description' => 'Evaluasi penerapan core values AKHLAK, keselamatan kerja, integritas, dan budaya kerja PT Adiprima Suraprinta tahun 2026.',
                'icon' => 'bi-people-fill',
                'start_date' => Carbon::today()->subDays(5)->toDateString(),
                'end_date' => Carbon::today()->addMonths(2)->toDateString(),
                'is_active' => true,
                'is_archived' => false,
            ]
        );

        $surveyBudaya2027 = Survey::updateOrCreate(
            ['slug' => 'survey-budaya-kerja-2027'],
            [
                'title' => 'Survey Budaya Kerja 2027',
                'category' => $catBudayaKerja->name,
                'survey_category_id' => $catBudayaKerja->id,
                'description' => 'Pengukuran berkala dan tindak lanjut budaya kerja serta kesiapan organisasi menyongsong tahun 2027.',
                'icon' => 'bi-people-fill',
                'start_date' => Carbon::create(2027, 1, 1)->toDateString(),
                'end_date' => Carbon::create(2027, 3, 31)->toDateString(),
                'is_active' => false,
                'is_archived' => false,
            ]
        );

        // Category 2: Employee Engagement Survey
        $engagementSurvey2026 = Survey::updateOrCreate(
            ['slug' => 'engagement-survey'],
            [
                'title' => 'Employee Engagement Survey 2026',
                'category' => $catEngagement->name,
                'survey_category_id' => $catEngagement->id,
                'description' => 'Instrumen komprehensif mengukur tingkat keterlibatan, loyalitas, dan kepuasan karyawan pada dimensi Basic Needs, Individual, Teamwork, dan Growth.',
                'icon' => 'bi-graph-up-arrow',
                'start_date' => Carbon::today()->subDays(7)->toDateString(),
                'end_date' => Carbon::today()->addMonths(1)->toDateString(),
                'is_active' => true,
                'is_archived' => false,
            ]
        );

        $engagementSurvey2025 = Survey::updateOrCreate(
            ['slug' => 'engagement-survey-2025'],
            [
                'title' => 'Employee Engagement Survey 2025 (Arsip)',
                'category' => $catEngagement->name,
                'survey_category_id' => $catEngagement->id,
                'description' => 'Data arsip kuesioner keterlibatan karyawan PT Adiprima Suraprinta periode tahun 2025.',
                'icon' => 'bi-archive-fill',
                'start_date' => Carbon::create(2025, 1, 1)->toDateString(),
                'end_date' => Carbon::create(2025, 2, 28)->toDateString(),
                'is_active' => false,
                'is_archived' => true,
            ]
        );

        // Category 3: Customer Satisfaction Survey
        $customerSat2026 = Survey::updateOrCreate(
            ['slug' => 'customer-satisfaction-survey'],
            [
                'title' => 'Customer Satisfaction Survey 2026',
                'category' => $catCustomerSat->name,
                'survey_category_id' => $catCustomerSat->id,
                'description' => 'Survei indeks kepuasan pelanggan eksternal & mitra kerja terhadap kualitas produk, ketepatan pengiriman, dan layanan purna jual.',
                'icon' => 'bi-award-fill',
                'start_date' => Carbon::today()->subDays(3)->toDateString(),
                'end_date' => Carbon::today()->addMonths(3)->toDateString(),
                'is_active' => true,
                'is_archived' => false,
            ]
        );

        $customerSat2025 = Survey::updateOrCreate(
            ['slug' => 'customer-satisfaction-survey-2025'],
            [
                'title' => 'Customer Satisfaction Survey 2025 (Arsip)',
                'category' => $catCustomerSat->name,
                'survey_category_id' => $catCustomerSat->id,
                'description' => 'Data arsip indeks kepuasan pelanggan terhadap mutu kertas dan layanan tahun 2025.',
                'icon' => 'bi-archive-fill',
                'start_date' => Carbon::create(2025, 1, 1)->toDateString(),
                'end_date' => Carbon::create(2025, 3, 31)->toDateString(),
                'is_active' => false,
                'is_archived' => true,
            ]
        );

        // Alias for backward compatibility
        $engagementSurvey = $engagementSurvey2026;
        $surveyBudaya = $surveyBudaya2026;
        $customerSat = $customerSat2026;

        // Create Active Periods
        $period = SurveyPeriod::updateOrCreate(
            ['survey_id' => $engagementSurvey->id, 'period_name' => 'Tahun 2026'],
            [
                'start_date' => $engagementSurvey->start_date,
                'end_date' => $engagementSurvey->end_date,
                'is_active' => true,
            ]
        );

        SurveyPeriod::updateOrCreate(
            ['survey_id' => $surveyBudaya->id, 'period_name' => 'Periode 2026'],
            [
                'start_date' => $surveyBudaya->start_date,
                'end_date' => $surveyBudaya->end_date,
                'is_active' => true,
            ]
        );

        SurveyPeriod::updateOrCreate(
            ['survey_id' => $customerSat->id, 'period_name' => 'Periode 2026'],
            [
                'start_date' => $customerSat->start_date,
                'end_date' => $customerSat->end_date,
                'is_active' => true,
            ]
        );

        // 5. Question Bank (Templates) and Survey Questions Definition
        $bankQuestions = [
            // Basic Needs
            [
                'dimension_id' => $dimBasicNeeds->id,
                'dimension' => 'Basic Needs',
                'section' => 'B',
                'question_number' => 1,
                'indicator_title' => 'Individual Accountability',
                'question_text' => 'Tugas, tanggung jawab, serta target kinerja yang harus dicapai dalam pekerjaan saya telah disampaikan dengan jelas oleh perusahaan',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 1,
            ],
            [
                'dimension_id' => $dimBasicNeeds->id,
                'dimension' => 'Basic Needs',
                'section' => 'B',
                'question_number' => 2,
                'indicator_title' => 'Kebutuhan Rasa Aman (Safety Needs)',
                'question_text' => 'Peralatan kerja dan perlengkapan keselamatan (seperti APD, rambu bahaya, instalasi proteksi kebakaran, dan lain-lain) yang disediakan oleh perusahaan telah sesuai dengan kebutuhan saya dalam melaksanakan pekerjaan',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 2,
            ],
            [
                'dimension_id' => $dimBasicNeeds->id,
                'dimension' => 'Basic Needs',
                'section' => 'B',
                'question_number' => 3,
                'indicator_title' => 'Kebutuhan Fisik (Physiological Needs)',
                'question_text' => 'Saya mendapatkan hak sebagai karyawan yang meliputi waktu istirahat, izin sakit, dan cuti yang diperhitungkan dalam sistem penggajian berdasarkan ketentuan perusahaan yang berlaku',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 3,
            ],
            [
                'dimension_id' => $dimBasicNeeds->id,
                'dimension' => 'Basic Needs',
                'section' => 'B',
                'question_number' => 4,
                'indicator_title' => 'Kebutuhan Rasa Aman (Safety Needs)',
                'question_text' => 'Perusahaan memberikan jaminan kesehatan dan keselamatan melalui BPJS Kesehatan dan BPJS Ketenagakerjaan sehingga membuat saya merasa lebih aman dalam melaksanakan pekerjaan',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 4,
            ],
            [
                'dimension_id' => $dimBasicNeeds->id,
                'dimension' => 'Basic Needs',
                'section' => 'B',
                'question_number' => 5,
                'indicator_title' => 'Gaji (Salary)',
                'question_text' => 'Perusahaan memberikan nominal gaji yang sesuai dengan beban kerja dan tanggung jawab pekerjaan',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 5,
            ],
            [
                'dimension_id' => $dimBasicNeeds->id,
                'dimension' => 'Basic Needs',
                'section' => 'B',
                'question_number' => 6,
                'indicator_title' => 'Bonus (Salary)',
                'question_text' => 'Bonus yang diberikan oleh perusahaan sesuai dengan performa kinerja saya',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => 'Tetap',
                'applies_to_positions' => 'Karu ke atas',
                'order' => 6,
            ],
            [
                'dimension_id' => $dimBasicNeeds->id,
                'dimension' => 'Basic Needs',
                'section' => 'B',
                'question_number' => 7,
                'indicator_title' => 'Gaji (Salary)',
                'question_text' => 'Kenaikan gaji di perusahaan diberikan berdasarkan kinerja dan kontribusi saya terhadap perusahaan serta sesuai dengan peraturan pemerintah yang berlaku',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 7,
            ],
            [
                'dimension_id' => $dimBasicNeeds->id,
                'dimension' => 'Basic Needs',
                'section' => 'B',
                'question_number' => 8,
                'indicator_title' => 'Kebijakan Perusahaan (Policies and Administration)',
                'question_text' => 'Kebijakan yang diterapkan perusahaan selaras dengan peraturan perusahaan, prosedur, dan pedoman yang berlaku dalam perusahaan',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 8,
            ],
            [
                'dimension_id' => $dimBasicNeeds->id,
                'dimension' => 'Basic Needs',
                'section' => 'B',
                'question_number' => 9,
                'indicator_title' => 'Kondisi Kerja (Working Conditions)',
                'question_text' => 'Kondisi lingkungan kerja serta fasilitas yang disediakan oleh perusahaan mendukung saya dalam melaksanakan pekerjaan',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 9,
            ],

            // Individual Contribution
            [
                'dimension_id' => $dimIndividual->id,
                'dimension' => 'Individual Contribution',
                'section' => 'B',
                'question_number' => 10,
                'indicator_title' => 'Pekerjaan itu Sendiri (Work Itself); Kompetensi (Competence)',
                'question_text' => 'Pekerjaan yang diberikan kepada saya telah sesuai dengan kemampuan dan keahlian yang saya miliki',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 10,
            ],
            [
                'dimension_id' => $dimIndividual->id,
                'dimension' => 'Individual Contribution',
                'section' => 'B',
                'question_number' => 11,
                'indicator_title' => 'Kebutuhan Pengakuan (Esteem Needs); Pengakuan (Recognition)',
                'question_text' => 'Saya mendapatkan apresiasi atas kualitas hasil pekerjaan yang saya tunjukkan',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 11,
            ],
            [
                'dimension_id' => $dimIndividual->id,
                'dimension' => 'Individual Contribution',
                'section' => 'B',
                'question_number' => 12,
                'indicator_title' => 'Hubungan Interpersonal (Interpersonal relationship)',
                'question_text' => 'Atasan dan/atau rekan kerja menunjukkan kepedulian terhadap saya sebagai individu serta memberikan dukungan kepada saya untuk bisa menyelesaikan pekerjaan secara optimal',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 12,
            ],
            [
                'dimension_id' => $dimIndividual->id,
                'dimension' => 'Individual Contribution',
                'section' => 'B',
                'question_number' => 13,
                'indicator_title' => 'Kebutuhan Aktualisasi (Self Actualization)',
                'question_text' => 'Perusahaan memberikan dukungan kepada saya untuk terus mengembangkan diri, baik dalam bentuk training atau coaching',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 13,
            ],
            [
                'dimension_id' => $dimIndividual->id,
                'dimension' => 'Individual Contribution',
                'section' => 'B',
                'question_number' => 14,
                'indicator_title' => 'Tanggung Jawab (Responsibility); Autonomi (Autonomy)',
                'question_text' => 'Atasan memberikan kepercayaan penuh kepada saya dalam melaksanakan pekerjaan serta kewenangan dalam pengambilan keputusan sesuai dengan tanggung jawab pekerjaan yang diberikan kepada saya',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 14,
            ],
            [
                'dimension_id' => $dimIndividual->id,
                'dimension' => 'Individual Contribution',
                'section' => 'B',
                'question_number' => 15,
                'indicator_title' => 'Pencapaian (Achievement)',
                'question_text' => 'Pencapaian atas target pekerjaan yang saya lakukan mendorong saya untuk terus melakukan peningkatan kinerja',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 15,
            ],

            // Teamwork
            [
                'dimension_id' => $dimTeamwork->id,
                'dimension' => 'Teamwork',
                'section' => 'B',
                'question_number' => 16,
                'indicator_title' => 'Group Processing',
                'question_text' => 'Saya diberi kesempatan untuk menyampaikan pendapat dan setiap masukan saya dipertimbangkan oleh atasan maupun rekan kerja',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 16,
            ],
            [
                'dimension_id' => $dimTeamwork->id,
                'dimension' => 'Teamwork',
                'section' => 'B',
                'question_number' => 17,
                'indicator_title' => 'Individual Accountability',
                'question_text' => 'Pekerjaan yang saya lakukan selaras dengan misi atau tujuan perusahaan',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 17,
            ],
            [
                'dimension_id' => $dimTeamwork->id,
                'dimension' => 'Teamwork',
                'section' => 'B',
                'question_number' => 18,
                'indicator_title' => 'Positive Interdependence',
                'question_text' => 'Rekan kerja saya berkomitmen untuk menghasilkan pekerjaan yang berkualitas dan tepat waktu',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 18,
            ],
            [
                'dimension_id' => $dimTeamwork->id,
                'dimension' => 'Teamwork',
                'section' => 'B',
                'question_number' => 19,
                'indicator_title' => 'Kebutuhan Sosial; Social Skills; Keterkaitan',
                'question_text' => 'Hubungan dengan atasan dan rekan kerja di perusahaan terjalin dengan harmonis sehingga dapat mendukung kerja sama tim dan komunikasi yang efektif',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 19,
            ],
            [
                'dimension_id' => $dimTeamwork->id,
                'dimension' => 'Teamwork',
                'section' => 'B',
                'question_number' => 20,
                'indicator_title' => 'Individual Accountability',
                'question_text' => 'Setiap orang dalam tim memiliki tanggung jawab atas pencapaian target dan penyelesaian tugas dalam pekerjaan',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 20,
            ],

            // Growth
            [
                'dimension_id' => $dimGrowth->id,
                'dimension' => 'Growth',
                'section' => 'B',
                'question_number' => 21,
                'indicator_title' => 'Pengawasan (Supervision)',
                'question_text' => 'Atasan saya memiliki kapabilitas dan kompetensi yang sesuai serta dapat memberikan masukan atau arahan yang mendukung pengembangan diri dan kinerja saya',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 21,
            ],
            [
                'dimension_id' => $dimGrowth->id,
                'dimension' => 'Growth',
                'section' => 'B',
                'question_number' => 22,
                'indicator_title' => 'Pengembangan Karir (Advancement); Peluang untuk Berkembang',
                'question_text' => 'Perusahaan memberikan kesempatan yang luas dan fasilitas yang memadai kepada saya untuk belajar dan berkembang sebagai upaya untuk menunjang peningkatan kinerja dan pengembangan karir di perusahaan',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 22,
            ],

            // General Questions (Section C)
            [
                'dimension_id' => $dimGeneral->id,
                'dimension' => 'General',
                'section' => 'C',
                'question_number' => 1,
                'indicator_title' => 'Kemungkinan Bertahan (Retention Likelihood)',
                'question_text' => 'Dari angka 1-4, seberapa besar kemungkinan Anda bertahan dan tetap bekerja di PT Adiprima Suraprinta, dengan mempertimbangkan manfaat, lingkungan kerja, dan peluang pengembangan yang tersedia?',
                'question_type' => 'single_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => false,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 23,
            ],
            [
                'dimension_id' => $dimGeneral->id,
                'dimension' => 'General',
                'section' => 'C',
                'question_number' => 2,
                'indicator_title' => 'Alasan Kemungkinan Bertahan',
                'question_text' => 'Berikan alasan mengapa Anda menjawab dengan penilaian terkait kemungkinan Anda bertahan dan tetap bekerja di PT Adiprima Suraprinta?',
                'question_type' => 'essay',
                'rating_scale' => 4,
                'require_reason_on_low_score' => false,
                'low_score_threshold' => 2,
                'applies_to_employment_status' => null,
                'applies_to_positions' => null,
                'order' => 24,
            ],
        ];

        // Seed templates into question_templates table and survey_questions for Engagement Survey
        $insertedQuestions = [];
        foreach ($bankQuestions as $q) {
            // Create in Question Bank Template
            $template = QuestionTemplate::updateOrCreate(
                [
                    'question_text' => $q['question_text'],
                    'indicator_title' => $q['indicator_title'],
                ],
                [
                    'dimension_id' => $q['dimension_id'],
                    'section' => $q['section'],
                    'question_type' => $q['question_type'],
                    'rating_scale' => $q['rating_scale'],
                    'require_reason_on_low_score' => $q['require_reason_on_low_score'],
                    'low_score_threshold' => $q['low_score_threshold'],
                    'applies_to_employment_status' => $q['applies_to_employment_status'],
                    'applies_to_positions' => $q['applies_to_positions'],
                    'order' => $q['order'],
                ]
            );

            // Create or update in Engagement Survey
            $surveyQData = $q;
            $surveyQData['survey_id'] = $engagementSurvey->id;
            $surveyQData['question_template_id'] = $template->id;

            $insertedQuestions[] = SurveyQuestion::updateOrCreate(
                [
                    'survey_id' => $engagementSurvey->id,
                    'section' => $q['section'],
                    'question_number' => $q['question_number'],
                ],
                $surveyQData
            );

            // Also seed a subset of relevant questions into Survey Budaya Kerja
            if (in_array($q['dimension'], ['Budaya & Nilai Kerja', 'Teamwork', 'Individual Contribution', 'General'])) {
                $budayaQData = $q;
                $budayaQData['survey_id'] = $surveyBudaya->id;
                $budayaQData['question_template_id'] = $template->id;

                SurveyQuestion::updateOrCreate(
                    [
                        'survey_id' => $surveyBudaya->id,
                        'section' => $q['section'],
                        'question_number' => $q['question_number'],
                    ],
                    $budayaQData
                );
            }
        }

        // Seed dedicated Bank Soal Templates for Survey Budaya Kerja
        $budayaBankTemplates = [
            [
                'dimension_id' => $dimBudaya->id,
                'section' => 'B',
                'indicator_title' => 'Penerapan Core Values AKHLAK',
                'question_text' => 'Nilai-nilai utama perusahaan (AKHLAK: Amanah, Kompeten, Harmonis, Loyal, Adaptif, Kolaboratif) telah dipahami dan diterapkan secara konsisten dalam aktivitas kerja harian.',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'order' => 1,
            ],
            [
                'dimension_id' => $dimBudaya->id,
                'section' => 'B',
                'indicator_title' => 'Integritas & Etika Kerja',
                'question_text' => 'Karyawan dan pimpinan di lingkungan unit kerja saya senantiasa menjunjung tinggi transparansi, kejujuran, dan kepatuhan terhadap kode etik perusahaan.',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'order' => 2,
            ],
            [
                'dimension_id' => $dimBudaya->id,
                'section' => 'B',
                'indicator_title' => 'Disiplin & Komitmen Kinerja',
                'question_text' => 'Kedisiplinan terhadap jam kerja, pemenuhan target, dan kepatuhan terhadap regulasi perusahaan dijalankan dengan penuh kesadaran dan komitmen tinggi.',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'order' => 3,
            ],
            [
                'dimension_id' => $dimK3->id,
                'section' => 'B',
                'indicator_title' => 'Kepatuhan Terhadap SOP K3 & Safety',
                'question_text' => 'Seluruh standar operasional keselamatan kerja (SOP K3), penggunaan APD lengkap, dan prosedur tanggap darurat dipatuhi secara ketat di area kerja.',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'order' => 4,
            ],
            [
                'dimension_id' => $dimK3->id,
                'section' => 'B',
                'indicator_title' => 'Penerapan Budaya 5R di Lingkungan Kerja',
                'question_text' => 'Kebersihan, kerapian fasilitas kerja, dan pengelolaan area operasional pabrik dijaga secara konsisten melalui pembiasaan budaya 5R.',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'order' => 5,
            ],
            [
                'dimension_id' => $dimK3->id,
                'section' => 'B',
                'indicator_title' => 'Kepedulian K3 Antar Rekan Kerja',
                'question_text' => 'Rekan kerja dan atasan proaktif saling mengingatkan terhadap potensi bahaya/kondisi tidak aman serta segera melaporkan potensi insiden.',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'order' => 6,
            ],
            [
                'dimension_id' => $dimBudaya->id,
                'section' => 'C',
                'indicator_title' => 'Kesiapan Transformasi Budaya',
                'question_text' => 'Dari skala 1-4, seberapa siap Anda dan unit kerja Anda dalam mendukung transformasi budaya kerja unggul PT Adiprima Suraprinta?',
                'question_type' => 'single_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'order' => 7,
            ],
            [
                'dimension_id' => $dimBudaya->id,
                'section' => 'C',
                'indicator_title' => 'Saran & Aspirasi Penguatan Budaya',
                'question_text' => 'Tuliskan masukan atau usulan konkret untuk memperkuat penerapan budaya kerja AKHLAK dan keselamatan operasional di PT Adiprima Suraprinta.',
                'question_type' => 'essay',
                'rating_scale' => 4,
                'require_reason_on_low_score' => false,
                'low_score_threshold' => 2,
                'order' => 8,
            ],
        ];

        foreach ($budayaBankTemplates as $bTpl) {
            QuestionTemplate::updateOrCreate(
                ['question_text' => $bTpl['question_text']],
                $bTpl
            );
        }

        // Seed Customer Satisfaction Questions
        $csQuestions = [
            [
                'dimension_id' => $dimKualitas->id,
                'dimension' => 'Kualitas Produk & Layanan',
                'section' => 'A',
                'question_number' => 1,
                'indicator_title' => 'Kualitas Produk & Konsistensi Mutu',
                'question_text' => 'Kualitas produk kertas yang dihasilkan oleh PT Adiprima Suraprinta telah memenuhi standar spesifikasi dan ekspektasi yang disepakati.',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'order' => 1,
            ],
            [
                'dimension_id' => $dimKualitas->id,
                'dimension' => 'Kualitas Produk & Layanan',
                'section' => 'A',
                'question_number' => 2,
                'indicator_title' => 'Ketepatan Waktu Pengiriman (On-Time Delivery)',
                'question_text' => 'Proses pengiriman dan distribusi pesanan dilakukan secara tepat waktu dan sesuai jadwal yang telah ditentukan.',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'order' => 2,
            ],
            [
                'dimension_id' => $dimPelayanan->id,
                'dimension' => 'Pelayanan & Komunikasi',
                'section' => 'A',
                'question_number' => 3,
                'indicator_title' => 'Kecepatan Respon Komunikasi & Komplain',
                'question_text' => 'Tim pelayanan pelanggan dan sales cepat tanggap dalam merespons pertanyaan, permintaan order, maupun keluhan pelanggan.',
                'question_type' => 'dual_rating',
                'rating_scale' => 4,
                'require_reason_on_low_score' => true,
                'low_score_threshold' => 2,
                'order' => 3,
            ],
            [
                'dimension_id' => $dimPelayanan->id,
                'dimension' => 'Pelayanan & Komunikasi',
                'section' => 'B',
                'question_number' => 1,
                'indicator_title' => 'Uraian Saran Perbaikan Layanan',
                'question_text' => 'Berikan masukan, kritik, atau saran untuk peningkatan kualitas produk dan pelayanan PT Adiprima Suraprinta ke depan.',
                'question_type' => 'essay',
                'rating_scale' => 4,
                'require_reason_on_low_score' => false,
                'low_score_threshold' => 2,
                'order' => 4,
            ]
        ];

        foreach ($csQuestions as $cq) {
            $cTemplate = QuestionTemplate::updateOrCreate(
                [
                    'question_text' => $cq['question_text'],
                    'indicator_title' => $cq['indicator_title'],
                ],
                [
                    'dimension_id' => $cq['dimension_id'],
                    'section' => $cq['section'],
                    'question_type' => $cq['question_type'],
                    'rating_scale' => $cq['rating_scale'],
                    'require_reason_on_low_score' => $cq['require_reason_on_low_score'],
                    'low_score_threshold' => $cq['low_score_threshold'],
                    'order' => $cq['order'],
                ]
            );

            $cqData = $cq;
            $cqData['survey_id'] = $customerSat->id;
            $cqData['question_template_id'] = $cTemplate->id;

            SurveyQuestion::updateOrCreate(
                [
                    'survey_id' => $customerSat->id,
                    'section' => $cq['section'],
                    'question_number' => $cq['question_number'],
                ],
                $cqData
            );
        }

        // 6. Seed Sample Responses for Dashboard Testing with Realistic Data & Timestamps
        $sampleReasons = [
            'Perlu penyesuaian evaluasi gaji dan kompensasi berkala agar lebih proporsional dengan beban kerja saat ini.',
            'Fasilitas penunjang di area mesin dan perlengkapan K3 perlu peremajaan berkala untuk menunjang keselamatan operasional.',
            'Dukungan pelatihan teknis berkala sangat dibutuhkan saat ada peremajaan mesin atau sistem baru.',
            'Sirkulasi udara dan pendingin di ruang kontrol unit perlu perbaikan agar kondisi kerja lebih nyaman.',
            'Perlu kejelasan jalur jenjang karir dan kriteria penilaian performa tahunan yang lebih transparan.',
            'Koordinasi antar shift kerja masih sering terjadi miss-komunikasi terkait serah terima pekerjaan.',
            'Alat perlindungan diri (APD) mohon stoknya selalu dipastikan siap dan sesuai ukuran standard.'
        ];

        $sampleEssays = [
            'Lingkungan kerja sangat kondusif, rasa kekeluargaan tinggi, dan perusahaan terus berkembang memberikan prospek karir yang baik.',
            'Secara keseluruhan budaya kerja di PT Adiprima Suraprinta sangat positif. Kerjasama lintas unit kerja sudah solid.',
            'Perusahaan sangat memperhatikan keselamatan kerja karyawan. Semoga kedepannya program apresiasi karyawan berprestasi makin ditingkatkan.',
            'Sistem manajemen dan komunikasi atasan-bawahan berjalan transparan dan saling mendukung. Sangat bangga menjadi bagian dari tim ini.',
            'Semoga PT Adiprima Suraprinta terus jaya, inovasi proses terus didorong, serta fasilitas penunjang kerja semakin modern.',
            'Kekompakan tim sangat terasa saat menghadapi kendala operasional. Manajemen selalu responsif dalam memberi arahan.',
            'Penerapan SOP K3 sudah sangat baik. Harapan kami agar program pelatihan kompetensi teknis dapat diadakan secara terjadwal.',
            'Suasana kerja aman dan saling menghargai. Terima kasih atas kepedulian manajemen terhadap kesejahteraan karyawan.'
        ];

        // 6a. Responses for Employee Engagement Survey 2026
        $sampleIndex = 1;
        foreach ($employeeModels as $emp) {
            // Calculate realistic timestamps during office/shift hours (08:00 - 16:30)
            $daysAgo = ($sampleIndex % 10) + 1;
            $hour = 8 + ($sampleIndex % 8);
            $minute = rand(0, 35);
            $second = rand(0, 55);
            $startedAt = Carbon::now()->subDays($daysAgo)->setTime($hour, $minute, $second);
            
            // Duration between 8 and 23 minutes (strictly after started_at)
            $durationMinutes = rand(8, 22);
            $durationSeconds = rand(15, 55);
            $submittedAt = (clone $startedAt)->addMinutes($durationMinutes)->addSeconds($durationSeconds);

            $resp = SurveyResponse::updateOrCreate(
                [
                    'survey_id' => $engagementSurvey->id,
                    'survey_period_id' => $period->id,
                    'user_id' => $emp->id,
                ],
                [
                    'nik' => $emp->nik,
                    'name' => $emp->name,
                    'gender' => $emp->gender,
                    'age' => $emp->age,
                    'education' => $emp->education,
                    'employment_status' => $emp->employment_status,
                    'tenure' => $emp->tenure,
                    'department' => $emp->department,
                    'position' => $emp->position,
                    'started_at' => $startedAt,
                    'submitted_at' => $submittedAt,
                    'created_at' => $startedAt,
                    'updated_at' => $submittedAt,
                    'ip_address' => '192.168.1.' . (10 + $sampleIndex),
                ]
            );

            // Clear old answers for clean re-seed
            SurveyAnswer::where('survey_response_id', $resp->id)->delete();

            foreach ($insertedQuestions as $q) {
                if ($q->section === 'B') {
                    // Check conditional for bonus question
                    if ($q->question_number === 6 && (!$emp->isPermanent() || !$emp->isKaruOrAbove())) {
                        continue;
                    }

                    $exp = rand(3, 4);
                    $real = rand(3, 4);

                    // Realistic distribution of low scores with realistic feedback
                    $hasLowScore = ($sampleIndex % 4 === 0 && in_array($q->question_number, [5, 7, 12, 18]));
                    if ($hasLowScore) {
                        $real = 2;
                    }

                    $reason = null;
                    if ($real <= 2) {
                        $reason = $sampleReasons[$sampleIndex % count($sampleReasons)];
                    }

                    SurveyAnswer::create([
                        'survey_response_id' => $resp->id,
                        'question_id' => $q->id,
                        'expectation_score' => $exp,
                        'reality_score' => $real,
                        'reason_text' => $reason,
                    ]);
                } elseif ($q->section === 'C') {
                    if ($q->question_number === 1) {
                        SurveyAnswer::create([
                            'survey_response_id' => $resp->id,
                            'question_id' => $q->id,
                            'reality_score' => rand(3, 4),
                            'text_answer' => 'Sangat Baik',
                        ]);
                    } elseif ($q->question_number === 2) {
                        SurveyAnswer::create([
                            'survey_response_id' => $resp->id,
                            'question_id' => $q->id,
                            'text_answer' => $sampleEssays[$sampleIndex % count($sampleEssays)],
                        ]);
                    }
                }
            }
            $sampleIndex++;
        }

        // 6b. Seed sample responses for Survey Budaya Kerja 2026
        $budayaQuestions = SurveyQuestion::where('survey_id', $surveyBudaya->id)->get();
        $budayaPeriod = SurveyPeriod::where('survey_id', $surveyBudaya->id)->first();
        if ($budayaQuestions->count() > 0 && $budayaPeriod) {
            foreach (array_slice($employeeModels, 0, 18) as $idx => $bEmp) {
                $daysAgo = ($idx % 6) + 1;
                $hour = 9 + ($idx % 7);
                $minute = rand(5, 40);
                $second = rand(10, 50);
                $bStarted = Carbon::now()->subDays($daysAgo)->setTime($hour, $minute, $second);
                $bSubmitted = (clone $bStarted)->addMinutes(rand(6, 19))->addSeconds(rand(10, 50));

                $bResp = SurveyResponse::updateOrCreate(
                    [
                        'survey_id' => $surveyBudaya->id,
                        'survey_period_id' => $budayaPeriod->id,
                        'user_id' => $bEmp->id,
                    ],
                    [
                        'nik' => $bEmp->nik,
                        'name' => $bEmp->name,
                        'gender' => $bEmp->gender,
                        'age' => $bEmp->age,
                        'education' => $bEmp->education,
                        'employment_status' => $bEmp->employment_status,
                        'tenure' => $bEmp->tenure,
                        'department' => $bEmp->department,
                        'position' => $bEmp->position,
                        'started_at' => $bStarted,
                        'submitted_at' => $bSubmitted,
                        'created_at' => $bStarted,
                        'updated_at' => $bSubmitted,
                        'ip_address' => '192.168.2.' . (20 + $idx),
                    ]
                );

                SurveyAnswer::where('survey_response_id', $bResp->id)->delete();
                foreach ($budayaQuestions as $bq) {
                    $expVal = rand(3, 4);
                    $realVal = rand(3, 4);
                    $reasonVal = null;
                    if ($idx === 2 && $bq->question_number === 4) {
                        $realVal = 2;
                        $reasonVal = 'Perlu sosialisasi berkala kembali tentang internalisasi nilai-nilai budaya kerja kepada staf baru.';
                    }

                    SurveyAnswer::create([
                        'survey_response_id' => $bResp->id,
                        'question_id' => $bq->id,
                        'expectation_score' => $expVal,
                        'reality_score' => $realVal,
                        'reason_text' => $reasonVal,
                        'text_answer' => ($bq->question_type === 'essay' ? 'Penerapan nilai integritas dan kolaborasi kerja sudah berjalan dengan sangat baik di unit kami.' : null),
                    ]);
                }
            }
        }

        // 6c. Seed sample responses for Customer Satisfaction Survey 2026
        $csQuestionsList = SurveyQuestion::where('survey_id', $customerSat->id)->get();
        $csPeriod = SurveyPeriod::where('survey_id', $customerSat->id)->first();
        if ($csQuestionsList->count() > 0 && $csPeriod) {
            $clientList = [
                ['name' => 'PT Gramedia Printing Utama', 'pic' => 'Bpk. Gunawan Wibisono', 'tenure' => '5 tahun'],
                ['name' => 'PT Temprina Media Grafika', 'pic' => 'Ibu Ratna Susanti', 'tenure' => '4 tahun'],
                ['name' => 'PT Surya Paper Packaging', 'pic' => 'Bpk. Ahmad Fauzan', 'tenure' => '3 tahun'],
                ['name' => 'PT Citra Box Perkasa', 'pic' => 'Bpk. Hendra Gunawan', 'tenure' => '6 tahun'],
                ['name' => 'PT Prima Indah Grafika', 'pic' => 'Ibu Maya Septiani', 'tenure' => '2 tahun'],
                ['name' => 'PT Jaya Abadi Packaging', 'pic' => 'Bpk. Deni Kurniawan', 'tenure' => '4 tahun'],
                ['name' => 'PT Multi Warna Offset', 'pic' => 'Bpk. Bobby Setiawan', 'tenure' => '5 tahun'],
                ['name' => 'PT Global Sarana Percetakan', 'pic' => 'Ibu Anita Wijaya', 'tenure' => '3 tahun'],
            ];

            $csEssays = [
                'Kualitas kertas roll sangat stabil dan minim paper break saat masuk mesin cetak kecepatan tinggi. Pengiriman selalu sesuai timeline.',
                'Pelayanan sales dan teknis sangat responsif, terutama saat dibutuhkan penyesuaian gramatur kertas secara mendesak.',
                'Packaging roll rapi dan terlindung dari kelembaban. Terus pertahankan standar mutu prima yang sudah ada.',
                'Koordinasi pengiriman dan konfirmasi surat jalan sangat rapi dan akurat. Kami sangat puas dengan kerjasama ini.',
                'Ketepatan waktu tiba pesanan di gudang kami sangat baik. Harapan kami promo volume pembelian besar dapat terus berlanjut.',
            ];

            foreach ($clientList as $cIdx => $client) {
                $daysAgo = ($cIdx % 5) + 1;
                $hour = 10 + ($cIdx % 5);
                $minute = rand(10, 45);
                $second = rand(5, 50);
                $csStarted = Carbon::now()->subDays($daysAgo)->setTime($hour, $minute, $second);
                $csSubmitted = (clone $csStarted)->addMinutes(rand(5, 16))->addSeconds(rand(10, 50));

                $cResp = SurveyResponse::updateOrCreate(
                    [
                        'survey_id' => $customerSat->id,
                        'survey_period_id' => $csPeriod->id,
                        'nik' => 'CL-' . (1001 + $cIdx),
                    ],
                    [
                        'name' => $client['name'] . ' (' . $client['pic'] . ')',
                        'gender' => ($cIdx % 2 === 0 ? 'Laki-laki' : 'Perempuan'),
                        'age' => rand(32, 48),
                        'education' => 'S1',
                        'employment_status' => 'Eksternal / Mitra',
                        'tenure' => $client['tenure'],
                        'department' => 'MRK / Sales',
                        'position' => 'Purchasing Manager / Client Rep',
                        'started_at' => $csStarted,
                        'submitted_at' => $csSubmitted,
                        'created_at' => $csStarted,
                        'updated_at' => $csSubmitted,
                        'ip_address' => '180.252.' . (10 + $cIdx) . '.' . rand(2, 250),
                    ]
                );

                SurveyAnswer::where('survey_response_id', $cResp->id)->delete();
                foreach ($csQuestionsList as $cqItem) {
                    $isEssay = ($cqItem->question_type === 'essay');
                    $expScore = $isEssay ? null : rand(3, 4);
                    $realScore = $isEssay ? null : rand(3, 4);

                    SurveyAnswer::create([
                        'survey_response_id' => $cResp->id,
                        'question_id' => $cqItem->id,
                        'expectation_score' => $expScore,
                        'reality_score' => $realScore,
                        'text_answer' => $isEssay ? $csEssays[$cIdx % count($csEssays)] : null,
                    ]);
                }
            }
        }
    }
}
