<?php
    require_once 'database.php';

    $pdo = connectToDatabase();

    $sql = 'SELECT * FROM programs';
    $all_programs = $pdo->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programs</title>
    <style>
        .card {
            display: inline-block;
            margin: 10px;
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            cursor: pointer;
        }
        .card img {
            width: 200px;
        }
    </style>
</head>
<body>
    <main>
        <?php while($row = $all_programs->fetch(PDO::FETCH_ASSOC)) { ?>
            <div class="card" onclick="showProgramDetails(<?php echo $row['id']; ?>)">
                <img src="<?php echo $row['program_image']; ?>" alt="">
                <p class="program"><?php echo $row['Program']; ?></p>
            </div>
        <?php } ?>
    </main>

    <script>
        function showProgramDetails(programId) {
            // Redirect the user to another PHP file with the program ID as a query parameter
            window.location.href = "form.php?programId=" + programId;
        }
    </script>
</body>
</html>
