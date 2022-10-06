<?php 
    require_once('../bootstrap/header.html');
    require_once('../lib/db_login.php'); 
?>
<div class="row g-0">
    <div class="col-2">
        <?php require_once '../dashboard/sidebarDept.php' ?>

    </div>
    <div class="col d-flex flex-column">
        <h3 class="">Daftar Dosen</h3>
        <div class="d-flex flex-row col-3">
            <input type="text" class="form-control" placeholder="Search" aria-label="Search"
                aria-describedby="button-addon2" class="f">
            <button class="btn btn-outline-secondary" type="button" id="button-addon2">Filter</button>
        </div>
        <div class="d-flex flex-row-reverse">
            <!-- print table from database siap1 -->
            <?php
                $query = "SELECT Nip, Nama FROM tb_dosen";
                $result = $db->query($query);
                if (!$result) {
                    die ("Could not query the database: <br>".$db->error."<br>Query: ".$query);
                }
                $i = 1;
                echo '<table class="table table-striped table-hover">';
                echo '<thead>';
                echo '<tr>';
                echo '<th scope="col">No</th>';
                echo '<th scope="col">NIP</th>';
                echo '<th scope="col">Nama</th>';
                echo '<th scope="col">Data Perwalian</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                while ($row = $result->fetch_object()) {
                    echo '<tr>';
                    echo '<th scope="row">'.$i.'</th>';
                    echo '<td>'.$row->Nip.'</td>';
                    echo '<td>'.$row->Nama.'</td>';
                    echo '<td><a href="detailMhs.php?nip='.$row->Nip.'" class="btn btn-primary">Detail</a></td>';
                    echo '</tr>';
                    $i++;
                }
                echo '</tbody>';
                echo '</table>';
                $result->free();
                $db->close();
            ?>
        </div>
    </div>
</div>