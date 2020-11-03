<?php
$server = "localhost";
$user = "root";
$password = "";
$nama_database = "profider_db";
$db = mysqli_connect($server, $user, $password, $nama_database);
if (!$db) {
    die("Error" . mysqli_connect_error());
}

function base_url()
{
    // return 'http://localhost/xlhome-revi/';
    return 'http://localhost/2020/projek/xlhome-revi/';
}

function query($query)
{
    global $db;
    $result = mysqli_query($db, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// Funtion CRUD

function upload($foto)
{
    // return false;
    $namafile   = $_FILES[$foto]['name'];
    $ukuranfile = $_FILES[$foto]['size'];
    $error      = $_FILES[$foto]['error'];
    $tmpname    = $_FILES[$foto]['tmp_name'];
    $lokasi     = "../assets/img/upload/";
    // var_dump($_POST);
    // var_dump($_FILES);
    // var_dump($ukuranfile);
    // die;

    // cek apakah tidak ada foto yang di upload
    if ($error === 4) {
        echo "
				<script>
					alert('masukkan foto terlebih dahulu!');
				</script>
			";
        return false;
    }

    // cek valid gambar
    $ekstensigambarvalid = ['jpg', 'jpeg', 'png', 'gif'];
    $ekstensigambar = explode('.', $namafile);
    $ekstensigambar = strtolower(end($ekstensigambar));
    if (!in_array($ekstensigambar, $ekstensigambarvalid)) {
        echo "
				<script>
					alert('yang anda masukkan bukan gambar!');
				</script>
			";
        return false;
    }


    // batas ukuran file
    if ($ukuranfile > 1050000) {
        echo "
				<script>
					alert('ukuran gambar terlalu besar!');
				</script>
			";
        return false;
    }

    // lolos pengecekan
    // generate nama baru
    $namafilebaru = uniqid();
    $namafilebaru .= '.';
    $namafilebaru .= $ekstensigambar;

    if (move_uploaded_file($tmpname, $lokasi . $namafilebaru)) {
        // echo "The file ". htmlspecialchars(basename($namafilebaru). " has been uploaded.";
    } else {
        echo "
				<script>
					alert('Upload gambar gagal!');
				</script>
			";
        return false;
    }

    return $namafilebaru;
}

function iRegistrasi($iRegistrasi)
{
    global $db;
    $id         = $iRegistrasi['id'];
    $alamat     = htmlspecialchars($iRegistrasi['alamat']);
    $paket      = htmlspecialchars($iRegistrasi['paket']);
    $nama       = htmlspecialchars($iRegistrasi['nama']);
    $email      = htmlspecialchars($iRegistrasi['email']);
    $noHp       = htmlspecialchars($iRegistrasi['noHp']);
    $tlp        = htmlspecialchars($iRegistrasi['tlp']);
    $fotoKtp    = htmlspecialchars($iRegistrasi['fotoKtp']);
    $fotoSelfie = htmlspecialchars($iRegistrasi['fotoSelfie']);
    $query      = "INSERT INTO registrasi VALUES('$id','$alamat','$paket','$nama','$email','$noHp','$tlp','$fotoKtp','$fotoSelfie','',0)";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

function iContent($iContent)
{
    global $db;
    $id_content = $iContent['id_content'];
    $judul      = htmlspecialchars($iContent['judul']);
    $harga      = htmlspecialchars($iContent['harga']);
    $sub_judul  = htmlspecialchars($iContent['sub_judul']);
    $fitur_1    = htmlspecialchars($iContent['fitur_1']);
    $fitur_2    = htmlspecialchars($iContent['fitur_2']);
    $fitur_3    = htmlspecialchars($iContent['fitur_3']);
    $fitur_4    = htmlspecialchars($iContent['fitur_4']);
    $fitur_5    = htmlspecialchars($iContent['fitur_5']);
    $fitur_6    = htmlspecialchars($iContent['fitur_6']);
    // $logo_content    = $_FILES['logo_content']['name'];
    $logo_content = upload("logo_content");
    if (!$logo_content) {
        return false;
    }
    $query           = "INSERT INTO content VALUES('$id_content','$judul','$logo_content','$harga','$sub_judul','$fitur_1','$fitur_2','$fitur_3','$fitur_4','$fitur_5','$fitur_6')";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

function eContent($eContent)
{
    global $db;
    $id_content = $eContent['id_content'];
    $judul      = htmlspecialchars($eContent['judul']);
    $logo_lama  = htmlspecialchars($eContent['logo_lama']);
    $harga      = htmlspecialchars($eContent['harga']);
    $sub_Judul   = htmlspecialchars($eContent['sub_judul']);
    $fitur1     = htmlspecialchars($eContent['fitur_1']);
    $fitur2     = htmlspecialchars($eContent['fitur_2']);
    $fitur3     = htmlspecialchars($eContent['fitur_3']);
    $fitur4     = htmlspecialchars($eContent['fitur_4']);
    $fitur5     = htmlspecialchars($eContent['fitur_5']);
    $fitur6     = htmlspecialchars($eContent['fitur_6']);

    // cek upload gambar
    if ($_FILES["logo_content"]["error"] === 4) {
        $logo_baru = $logo_lama;
    } else {
        $logo_baru = upload("logo_content");
        if (!$logo_baru) {
            return false;
        }
    }

    $query      = "UPDATE content SET judul='$judul',logo='$logo_baru',harga='$harga',sub_Judul='$sub_Judul',cont_1='$fitur1',cont_2='$fitur2',cont_3='$fitur3',cont_4='$fitur4',cont_5='$fitur5',cont_6='$fitur6' WHERE id='$id_content'";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

function hContent($hContent)
{
    global $db;

    $id      = $hContent['hapus'];

    $file_dir = "../assets/img/upload/";
    $cari = query("SELECT * FROM content WHERE id = '$id'");
    $file = $cari[0]['logo'];
    unlink($file_dir . $file);

    $query = "DELETE FROM content WHERE id = '$id'";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

function eAdmin($eAdmin)
{
    global $db;
    $id         = $eAdmin['id'];
    $user       = htmlspecialchars($eAdmin['user']);
    $pass       = htmlspecialchars($eAdmin['pass']);
    $TitleHome  = htmlspecialchars($eAdmin['TitleHome']);
    $logo       = htmlspecialchars($eAdmin['logo']);
    $sos1       = htmlspecialchars($eAdmin['sos1']);
    $sos2       = htmlspecialchars($eAdmin['sos2']);
    $agen       = htmlspecialchars($eAdmin['agen']);
    $agen1      = htmlspecialchars($eAdmin['agen1']);
    $agen2      = htmlspecialchars($eAdmin['agen2']);
    $tlp        = htmlspecialchars($eAdmin['tlp']);
    $tlp1       = htmlspecialchars($eAdmin['tlp1']);
    $tlp2       = htmlspecialchars($eAdmin['tlp2']);
    $wa         = htmlspecialchars($eAdmin['wa']);
    $wa1        = htmlspecialchars($eAdmin['wa1']);
    $wa2        = htmlspecialchars($eAdmin['wa2']);
    $alamat     = htmlspecialchars($eAdmin['alamat']);

    $query      = "UPDATE 'admin' SET user='$user',pass='$pass',TitleHome='$TitleHome',logo='$logo',sos1='$sos1',sos2='$sos2',agen='$agen',agen1='$agen1',agen2='$agen2',tlp='$tlp',tlp1='$tlp1',tlp2='$tlp2',wa='$wa',wa1='$wa1',wa2='$wa2',alamat='$alamat' WHERE id='Un!X1d@4pp'";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}
