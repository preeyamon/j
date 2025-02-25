<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>เพิ่มข้อมูลสินค้า</title>
</head>

<body>
<h1>ฟอร์มเพิ่มข้อมูลสินค้า</h1>

<form method="post" action="" enctype="multipart/form-data">
    ชื่อสินค้า: <input type="text" name="pname" required autofocus> <br>
    รายละเอียดสินค้า: <textarea name="pdetail" required></textarea> <br>
    ราคา: <input type="text" name="pprice" required> <br>
    รูปภาพสินค้า: <input type="file" name="pimage" accept="image/*" required> <br>
    <button type="submit">บันทึก</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include_once("../connectdb.php");

    // รับค่าจากฟอร์ม
    $pname = $_POST['pname'];
    $pdetail = $_POST['pdetail'];
    $pprice = $_POST['pprice'];

    // ตรวจสอบไฟล์รูปภาพ
    if (isset($_FILES['pimage']) && $_FILES['pimage']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['pimage']['tmp_name'];
        $imageName = basename($_FILES['pimage']['name']);
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageExt, $allowedExt)) {
            // เพิ่มข้อมูลลงในฐานข้อมูล
            $sql = "INSERT INTO products (p_name, p_detail, p_price, p_ext) VALUES ('{$pname}', '{$pdetail}', {$pprice}, '{$imageExt}')";
            if (mysqli_query($conn, $sql)) {
                // รับรหัสสินค้าที่เพิ่มใหม่
                $lastId = mysqli_insert_id($conn);
                $newImageName = $lastId . '.' . $imageExt;
                $uploadDir = '../images/';
                $uploadFilePath = $uploadDir . $newImageName;

                // ย้ายไฟล์รูปภาพไปยังโฟลเดอร์
                if (move_uploaded_file($imageTmpPath, $uploadFilePath)) {
                    echo "<script>alert('เพิ่มข้อมูลสำเร็จ');</script>";
                } else {
                    echo "<script>alert('อัปโหลดรูปภาพไม่สำเร็จ');</script>";
                }
            } else {
                echo "<script>alert('เพิ่มข้อมูลไม่สำเร็จ: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('รูปภาพต้องเป็นไฟล์ประเภท JPG, JPEG, PNG หรือ GIF เท่านั้น');</script>";
        }
    } else {
        echo "<script>alert('กรุณาอัปโหลดรูปภาพสินค้า');</script>";
    }
}
?>
</body>
</html>
