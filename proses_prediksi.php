<?php

header('Content-Type: application/json');

// --- 1. PENGATURAN KONEKSI DATABASE ---
$servername = "localhost"; // Biasanya "localhost" atau "127.0.0.1"
$username = "root";      // Username default Laragon
$password = "";          // Password default Laragon kosong
$dbname = "dreamride_db";  // Nama database yang kita buat

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    // Kirim error jika koneksi gagal, jangan tampilkan detail ke user
    echo json_encode(['error' => 'Gagal terhubung ke server']);
    exit();
}


// --- 2. PROSES DATA DARI JAVASCRIPT (Sama seperti sebelumnya) ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Metode tidak diizinkan']);
    exit();
}

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if (empty($data)) {
    echo json_encode(['error' => 'Data tidak valid']);
    exit();
}

$name = $data['name'] ?? 'Anonymous';
$age = (int)($data['age'] ?? 0);
$income_level = $data['income_level'] ?? '';
$main_purpose = $data['main_purpose'] ?? '';
$main_priority = $data['main_priority'] ?? '';
$style_detail = $data['style_detail'] ?? '';


// --- 3. LOGIKA PREDIKSI (Sama seperti sebelumnya) ---
$motorcycles = [
    'Gear Ultima' => [
        'name' => 'Gear Ultima 125',
        'image' => 'images/gear-ultima.png',
        'price' => 'Rp 18.200.000',
        'specs' => [
            ['label' => 'Mesin', 'value' => '125 cc, Blue Core'],
            ['label' => 'Fitur', 'value' => 'Electric Power Socket, Smart Lock'],
            ['label' => 'Kategori', 'value' => 'Multi-purpose & Praktis']
        ],
        'info' => 'Pilihan rasional untuk kebutuhan harian. Irit, praktis untuk membawa barang, dan bisa diandalkan oleh seluruh anggota keluarga.'
    ],
    'Fazzio' => [
        'name' => 'Fazzio Hybrid',
        'image' => 'images/fazzio.png',
        'price' => 'Rp 22.650.000',
        'specs' => [
            ['label' => 'Mesin', 'value' => '125 cc, Blue Core Hybrid'],
            ['label' => 'Fitur', 'value' => 'Y-Connect, Smart Key System'],
            ['label' => 'Kategori', 'value' => 'Classy & Trendy']
        ],
        'info' => 'Untuk kamu yang ingin tampil gaul dan kekinian. Desainnya yang unik menjadi pusat perhatian dan dilengkapi teknologi modern.'
    ],
    'Grand Filano' => [
        'name' => 'Grand Filano',
        'image' => 'images/grand-filano.png',
        'price' => 'Rp 27.000.000',
        'specs' => [
            ['label' => 'Mesin', 'value' => '125 cc, Blue Core Hybrid'],
            ['label' => 'Fitur', 'value' => 'TFT Display, Y-Connect, Smart Key'],
            ['label' => 'Kategori', 'value' => 'Elegant & Berkelas']
        ],
        'info' => 'Mencerminkan gaya hidup elegan. Desain mewah dengan sentuhan klasik Eropa, cocok untuk kamu yang peduli penampilan berkelas.'
    ],
    'Aerox Alpha' => [
        'name' => 'Aerox Alpha',
        'image' => 'images/aerox-alpha.png',
        'price' => 'Rp 29.500.000',
        'specs' => [
            ['label' => 'Mesin', 'value' => '155 cc, Liquid Cooled VVA'],
            ['label' => 'Fitur', 'value' => 'Y-Connect, Smart Key System'],
            ['label' => 'Kategori', 'value' => 'Sporty & Performa']
        ],
        'info' => 'Bagi yang berjiwa sporty dan menyukai kecepatan. Desain aerodinamis dan performa mesin VVA menjadikannya idola untuk touring dan sunmori.'
    ],
    'NMAX TURBO' => [
        'name' => 'NMAX "TURBO"',
        'image' => 'images/nmax-turbo.png',
        'price' => 'Rp 37.750.000',
        'specs' => [
            ['label' => 'Mesin', 'value' => '155 cc, YECVT, Liquid Cooled VVA'],
            ['label' => 'Fitur', 'value' => 'TFT Display with Navigation, Turbo Y-Shift'],
            ['label' => 'Kategori', 'value' => 'Premium & Teknologi Tinggi']
        ],
        'info' => 'Puncak performa, kemewahan, dan teknologi. Dilengkapi "Turbo" Y-Shift untuk akselerasi instan, ini adalah pilihan utama bagi pengendara mapan.'
    ]
];

function getRecommendation($age, $income_level, $main_purpose, $main_priority, $style_detail) {
    // --- Aturan Baru yang Lebih Cerdas ---

    // 1. Prioritas untuk hobi & performa
    if ($main_purpose === 'hobby' || $main_priority === 'performance') {
        if ($income_level === 'established' || $age > 30) {
            return "NMAX TURBO"; // Pengendara mapan/dewasa yang ingin performa & kenyamanan touring
        }
        return "Aerox Alpha"; // Pilihan utama untuk jiwa muda & sporty
    }

    // 2. Prioritas untuk gaya & penampilan
    if ($main_priority === 'style') {
        if ($style_detail === 'classy_elegant' || $age > 35) {
            return "Grand Filano"; // Gaya elegan, lebih disukai usia dewasa
        }
        return "Fazzio"; // Gaya trendy, cocok untuk anak muda
    }

    // 3. Prioritas untuk keluarga atau kenyamanan
    if ($main_purpose === 'family' || $main_priority === 'comfort') {
        // Jika sangat mapan, NMAX adalah pilihan kenyamanan terbaik
        if ($income_level === 'established') {
            return "NMAX TURBO";
        }
        // Jika tidak, Gear adalah pilihan praktis terbaik untuk keluarga
        return "Gear Ultima";
    }

    // 4. Untuk kebutuhan harian & efisiensi (Default)
    // Jika usia lebih muda dan finansial stabil, Fazzio bisa jadi pilihan harian yang gaya
    if ($age < 25 && $income_level !== 'entry') {
        return "Fazzio";
    }
    
    // Default untuk semua kebutuhan praktis & efisien
    return "Gear Ultima"; 
}

$recommended_motor_name = getRecommendation($age, $income_level, $main_purpose, $main_priority, $style_detail);
$response = $motorcycles[$recommended_motor_name] ?? ['error' => 'Motor tidak ditemukan'];


// --- 4. SIMPAN DATA KE DATABASE (Bagian Baru) ---
// Gunakan prepared statements untuk keamanan dari SQL Injection
$stmt = $conn->prepare(
    "INSERT INTO predictions (name, age, income_level, main_purpose, main_priority, style_detail, recommended_motor) VALUES (?, ?, ?, ?, ?, ?, ?)"
);

// "ssissss" adalah tipe data untuk setiap parameter: s=string, i=integer
$stmt->bind_param(
    "sisssss",
    $name,
    $age,
    $income_level,
    $main_purpose,
    $main_priority,
    $style_detail,
    $recommended_motor_name
);

// Eksekusi query untuk menyimpan data
$stmt->execute();

// Tutup statement dan koneksi
$stmt->close();
$conn->close();


// --- 5. KIRIM HASIL KE JAVASCRIPT (Sama seperti sebelumnya) ---
echo json_encode($response);

?>