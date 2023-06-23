<?php
    require_once "database.php";

    if(isset($_POST["submit"])){
        $program = $_POST["program"];
        $category = $_POST["category"];
        $orgName = $_POST["orgName"];
        $Name = $_POST["Name"];
        $email = $_POST["email"];
        $response_limit = $_POST["response_limit"]; // Added line

        $pdo = connectToDatabase();

        $upload_dir = "uploads/";
        $programImage = $upload_dir.$_FILES["imageUpload"]["name"];
        $upload_dir.$_FILES["imageUpload"]["name"];
        $upload_file = $upload_dir.basename($_FILES["imageUpload"]["name"]);
        $imageType = strtolower(pathinfo($upload_file,PATHINFO_EXTENSION));
        $check = $_FILES["imageUpload"]["size"];
        $upload_ok = 0;

        if(file_exists($upload_file)){
            echo "<script> alert('The file already exists')</script>";
            $upload_ok = 0;
        }else{
            $upload_ok = 1;
            if($check !== false){
                $upload_ok = 1;
                if($imageType == 'jpg' ||$imageType == 'png' || $imageType == 'jpeg' ){
                    $upload_ok = 1;
                }else{
                    echo "<script> alert('Please change the image format')</script>";
                }
            }else{
                echo "<script> alert('The photo size is 0 please change the photo')</script>";
                $upload_ok = 0;
            }        
        }
        if ($upload_ok == 0){
            echo "<script> alert('Can\'t upload your file, please try again')</script>";
        }else{
            if($program != "" && $orgName != ""){
                move_uploaded_file($_FILES["imageUpload"]["tmp_name"],$upload_file);
                $sql = "INSERT INTO programs(Program, Category, orgName, firstName, email, program_image, response_limit)
                VALUES(:program, :category, :orgName, :Name, :email, :programImage, :response_limit)";

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':program', $program, PDO::PARAM_STR);
                $stmt->bindParam(':category', $category, PDO::PARAM_STR);
                $stmt->bindParam(':orgName', $orgName, PDO::PARAM_STR);
                $stmt->bindParam(':Name', $Name, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':programImage', $programImage, PDO::PARAM_STR);
                $stmt->bindParam(':response_limit', $response_limit, PDO::PARAM_INT);

                if($stmt->execute()){
                    echo "<script> alert('Program Uploaded Successfully')</script>";
                } else {
                    echo "<script> alert('Failed to upload program.')</script>";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
</head>
<body>

    <section>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="program" id="program" placeholder="Program" required>
            <label for="category">Category</label>
            <select name="category" id="category">
                <option value="category 1">category 1</option>
                <option value="category 2">category 2</option>
                <option value="category 3">category 3</option>
                <option value="category 4">category 4</option>
            </select>
            <input type="text" name="orgName" id="orgName" placeholder="Your Organization Name" required>
            <input type="text" name="Name" id="Name" placeholder="Name" required>
            <input type="text" name="email" id="email" placeholder="Email Address" required>
            <input type="number" name="response_limit" id="response_limit" placeholder="Response Limit" required>
            <input type="file" name="imageUpload" id="imageUpload" required hidden>
            <button id="choose" onclick="upload();">Choose Image</button>
            <input type="submit" value="Upload" name="submit">
        </form>
    </section>

    <script> 
        var program = document.getElementById("program");
        var category = document.getElementById("category");
        var orgName = document.getElementById("orgName");
        var Name = document.getElementById("Name");
        var email = document.getElementById("email");
        var response_limit = document.getElementById("response_limit"); // Added line
        var choose = document.getElementById("choose");
        var UploadImage = document.getElementById("imageUpload");
    
        function upload(){
            UploadImage.click();
        }

        UploadImage.addEventListener("change", function(){
            var file = this.files[0];
            if(program.value == ""){
                program.value = file.name;
            }
            choose.innerHTML = "You can change ("+file.name+")picture";
        })
    </script>
    
</body>
</html>
