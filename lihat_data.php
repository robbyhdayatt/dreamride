<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Hasil Kuis DreamRide</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #007BFF;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 0.9rem;
        }

        thead {
            background-color: #007BFF;
            color: white;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        tbody tr:hover {
            background-color: #e9ecef;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>📊 Dasbor Hasil Kuis DreamRide</h1>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Usia</th>
                    <th>Pendapatan</th>
                    <th>Tujuan</th>
                    <th>Prioritas</th>
                    <th>Detail Gaya</th>
                    <th>Rekomendasi Motor</th>
                    <th>Waktu Submit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // --- KONEKSI DATABASE ---
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "dreamride_db";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    // Tampilkan error di dalam tabel jika koneksi gagal
                    echo "<tr><td colspan='9' class='no-data'>Koneksi Gagal: " . $conn->connect_error . "</td></tr>";
                } else {
                    // --- AMBIL DATA DARI TABEL ---
                    $sql = "SELECT id, name, age, income_level, main_purpose, main_priority, style_detail, recommended_motor, created_at FROM predictions ORDER BY created_at DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Looping untuk menampilkan setiap baris data
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            // Menggunakan htmlspecialchars untuk keamanan
                            echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                            echo "<td>" . $row["age"] . "</td>";
                            echo "<td>" . htmlspecialchars($row["income_level"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["main_purpose"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["main_priority"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["style_detail"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["recommended_motor"]) . "</td>";
                            echo "<td>" . $row["created_at"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='no-data'>Belum ada data yang tersimpan.</td></tr>";
                    }
                    $conn->close();
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>