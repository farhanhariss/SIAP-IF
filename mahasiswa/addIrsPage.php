<?php require_once '../bootstrap/header.html';
require_once '../lib/db_login.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
} else {
    $user = $_SESSION['user']['Role'];
    if ($user != '1') {
        header("Location: ../index.php");
    }
}

$nim = $_SESSION['user']['Nim_Nip'];
$smt = $_GET['semester'];

if (isset($_POST['submit'])) {

    for ($i = 0; $i < count($_POST['mata_kuliah']); $i++) {
        if ($_POST['mata_kuliah'][$i] != "" && $_POST['kelas'][$i] != "") {
            $result = $db->query("INSERT INTO tb_nilai (Nim, Semester, Kode_Matkul, Kelas) VALUES ('$nim', '$smt', '" . $_POST['mata_kuliah'][$i] . "', '" . $_POST['kelas'][$i] . "')");
        }
    }
    $namaFile = $_FILES['uploadIrs']['name'];
$namaSementara = $_FILES['uploadIrs']['tmp_name'];

// tentukan lokasi file akan dipindahkan
$dirUpload = "../upload/irs/";

// pindahkan file
$terupload = move_uploaded_file($namaSementara, $dirUpload.$namaFile);
    $query3 = "SELECT SUM(SKS) as TotalSKS FROM tb_nilai n JOIN tb_matkul k WHERE n.Kode_Matkul = k.Kode_Matkul AND n.Nim = '" . $nim . "' AND n.Semester = '" . $smt . "' ";
    $sumSKS = $db->query($query3)->fetch_object();
    if ($sumSKS->TotalSKS != null) {
        $result2 = $db->query("INSERT INTO tb_irs(Nim, Semester, Status, Jml_SKS, File_IRS) VALUES('$nim', '$smt', 'Belum Disetujui', '" . $sumSKS->TotalSKS . "','$namaFile')");
    } else {
?> <div class="alert alert-error">Data Gagal Disimpan <?php echo $db->error ?></div> <?php
                                                                                                }
                                                                                                header("Location: ./irsMhsPage.php");
                                                                                            }
                                                                                                    ?>


<div class="row g-0">
    <div class="col-2">
        <?php require_once '../dashboard/sidebarMhs.php' ?>

    </div>
    <div class="col p-4">
        <h3 class="d-flex justify-content-center">IRS</h3>
        <div class="card">

            <h4 class="card-header">Entry IRS</h4>
            <form class="card-body" method="POST" action="" enctype="multipart/form-data">
                <div class="row gx-5">
                    <div class="col">
                        <label>Semester</label>
                        <p><?php echo $smt ?></p>
                    </div>

                </div>
                <!-- Input Matkul dan Kelas -->
                <div class="row mt-4 g-0">
                    <table>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th>Kelas</th>
                        </tr>
                        <tbody id="tambahIRS">
                            <td><select class='form-select' id='mata_kuliah' name='mata_kuliah[]' aria-label='Default select example'>
                                    <option selected>Pilih Mata Kuliah</option>
                                    <?php $result = $db->query('select * from tb_matkul');
                                    while ($mk = $result->fetch_object()) : ?>
                                        <option value='<?php echo $mk->Kode_Matkul ?>'><?php echo $mk->Nama_Matkul . ' (' . $mk->SKS . ' SKS)' ?></option>
                                    <?php endwhile ?>
                                </select></td>
                            <td><select class='form-select' name='kelas[]' aria-label='Default select example'>
                                    <option value=''>Pilih Kelas</option>
                                    <option value='A'>A</option>
                                    <option value='B'>B</option>
                                    <option value='C'>C</option>
                                </select>
                            </td>
                        </tbody>
                    </table>
                </div>

                <div class="text-center">
                    <!-- button to add new row mata kuliah -->
                    <button type="button" class="btn btn-outline-dark fw-bold mt-4 rounded-circle" onclick="addEntryIRS()">+</button>
                </div>

                <h4 class="fw-bold">Upload IRS</h4>
                <div class="form-group d-flex flex-column mb-2">
                    <label for="uploadIrs">Upload File</label>
                    <input type="file" class="form-control-file" name="uploadIrs" id="uploadIrs">
                </div>
                <div class="d-flex mb-3">
                    <button type="submit" name="submit" class=" btn btn-success mt-3">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function addEntryIRS() {
        var html = "<tr>";
        html = html + "<td><select class='form-select' id='mata_kuliah' name='mata_kuliah[]' aria-label='Default select example'>";
        html = html + "<option value=''>Pilih Mata Kuliah</option>";
        <?php $result = $db->query('select * from tb_matkul');
        while ($mk = $result->fetch_object()) : ?>
            html = html + "<option value='<?php echo $mk->Kode_Matkul ?>'><?php echo $mk->Nama_Matkul . ' (' . $mk->SKS . ' SKS)' ?></option>";
        <?php endwhile ?>
        html = html + "</select></td>";

        html = html + "<td><select class='form-select' name='kelas[]' aria-label='Default select example'>";
        html = html + "<option value=''>Pilih Kelas</option>";
        html = html + "<option value='A'>A</option>";
        html = html + "<option value='B'>B</option>";
        html = html + "<option value='C'>C</option>";
        html = html + "</select></td>";
        html += "<tr>"
        document.getElementById("tambahIRS").insertRow().innerHTML += html;
    }
</script>
<?php require_once '../bootstrap/footer.html' ?>