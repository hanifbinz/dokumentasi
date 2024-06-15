<?php 
if ($act=='xls') {
    $filename = 'laporan_bongkar';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); 
    header('Cache-Control: max-age=0');
}

$date=explode(" - ", $tgl);
$p1=date("Y-m-d", strtotime($date[0]));
$p2=date("Y-m-d", strtotime($date[1]));
$periode = $p1.' S/D '. $p2;
 ?>
<div class="table-responsive">
    <?php if (!empty($act)): ?>
       <i class="fa fa-table"></i> Data Bongkar Periode <?php echo $periode ?></h3>
   <?php endif ?>
   
   <!-- Data Table -->
   <table   class="table table-bordered table-striped table-hover" id="tbl_tb" border="1" cellspacing="0" cellpadding="5" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Uploader</th>
            <th>Tanggal</th>
            <th>Nama Angkutan</th>
            <th>No Kontainer</th>
            <th>Nama Barang</th>
            <th>Jumlah Barang</th>
            <th>Kode Bongkar</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
         foreach ($lap->result() as $key => $data): ?>
            <tr>
                <td align="center" style="vertical-align: middle;"><?php echo $no++; ?></td>
                <td align="center" style="vertical-align: middle;"><?php echo $data->full_name; ?></td>
                <td align="center" style="vertical-align: middle;">
                    <?php
                            
                            $tanggal_format = date('d M y', strtotime($data->tanggal));
                            echo $tanggal_format;
                            ?>
                        </td>
                        <td align="center" style="vertical-align: middle;"><?php echo $data->nama_angkutan; ?></td>
                        <td align="center" style="vertical-align: middle;"><?php echo $data->no_kontainer; ?></td>
                        <td align="center" style="vertical-align: middle;"><?php echo $data->nama_barang; ?></td>
                        <td align="center" style="vertical-align: middle;"><?php echo $data->jumlah_barang; ?></td>
                        <td align="center" style="vertical-align: middle;"><?php echo $data->kode_bongkar; ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>