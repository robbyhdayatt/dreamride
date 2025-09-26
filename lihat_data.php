<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Hasil Kuis DreamRide</title>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #84a8ddff;
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

        /* Styling tambahan agar DataTables & Buttons terlihat lebih rapi */
        table.dataTable thead th {
            background-color: #007BFF;
            color: white;
            border-bottom: 2px solid #0056b3;
        }
        table.dataTable tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        table.dataTable tbody tr:hover {
            background-color: #e9ecef;
        }
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 5px;
        }
        .dataTables_wrapper .dataTables_length select {
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 5px;
        }
        /* Style untuk tombol ekspor */
        .dt-buttons .dt-button {
            background-color: #007BFF;
            color: white !important;
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
            margin: 0 5px 10px 0; /* Memberi jarak antar tombol */
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: background-color 0.3s ease;
        }
        .dt-buttons .dt-button:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>

    <div class="container">
        <h1>📊 Dasbor Hasil Kuis DreamRide</h1>
        
        <table id="tabelHasilKuis" class="display" style="width:100%">
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
                    echo "<tr><td colspan='9' class='no-data'>Koneksi Gagal: " . $conn->connect_error . "</td></tr>";
                } else {
                    $sql = "SELECT id, name, age, income_level, main_purpose, main_priority, style_detail, recommended_motor, created_at FROM predictions ORDER BY created_at DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
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

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#tabelHasilKuis').DataTable( {
            // 'B' untuk menampilkan Buttons
            // 'f' untuk filter (pencarian)
            // 't' untuk tabel
            // 'i' untuk info
            // 'p' untuk pagination
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'pdf'
            ]
        } );
    });
    </script>

</body>
</html>