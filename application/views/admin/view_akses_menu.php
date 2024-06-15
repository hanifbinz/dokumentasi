<div class="card-body table-responsive p-0">
  <table class="table table-bordered table-hover" id="vakses"> 
    <thead>
      <tr class="bg-primary">
        <th>No</th>
        <th>Menu</th>
        <th>View</th>
        <th>Add</th>
        <th>Edit</th>
        <th>Delete</th>
        <th>Print</th>
        <th>Upload</th>
        <th>Download</th>
        <!-- <th>V Btn</th>
        <th>V Btn1</th> -->
      </tr>
    </thead>
    <tbody>
      <?php $i=1; foreach ($data_menu as $row) {

        ?>
        <tr>
          <td class="bg-success"><?=$i++;?></td>
          <td class="bg-success"><?=$row->nama_menu?></td>
          <td>
            <?php if ($row->view=="Y") {?>
              <div id="vcek<?=$i.$row->id?>" onClick="akses_menu('<?=$row->id?>','<?=$row->id_level?>','view','checked')">
                <i class="fas fa-check-circle text-success btn"></i>
              </div>
              
            <?php }else{?>
              <div id="vucek<?=$i.$row->id?>" onClick="akses_menu('<?=$row->id?>','<?=$row->id_level?>','view','unchecked')">
                <i class="fa fa-times-circle text-red btn"></i>
              </div>

            <?php } ?> 
          </td>
            <td>
           <?php if ($row->add=="Y") {?>
            <div  onClick="akses_menu('<?=$row->id?>','<?=$row->id_level?>','add','checked')">
              <i class="fas fa-check-circle text-success btn"></i>
            </div>
          <?php }else{ ?>
            <div  onClick="akses_menu('<?=$row->id?>','<?=$row->id_level?>','add','unchecked')">
              <i class="fa fa-times-circle text-red btn"></i>
            </div>
          <?php } ?>
        </td>
        <td>
         <?php if ($row->edit=="Y") {?>
          <div  onClick="akses_menu('<?=$row->id?>','<?=$row->id_level?>','edit','checked')">
            <i class="fas fa-check-circle text-success btn"></i>
          </div>
        <?php }else{ ?>
          <div  onClick="akses_menu('<?=$row->id?>','<?=$row->id_level?>','edit','unchecked')">
            <i class="fa fa-times-circle text-red btn"></i>
          </div>
        <?php } ?>
      </td>
      <td>
        <?php if ($row->delete=="Y") {?>
          <div  onClick="akses_menu('<?=$row->id?>','<?=$row->id_level?>','delete','checked')">
            <i class="fas fa-check-circle text-success btn"></i>
          </div>
        <?php }else{ ?>
          <div  onClick="akses_menu('<?=$row->id?>','<?=$row->id_level?>','delete','unchecked')">
            <i class="fa fa-times-circle text-red btn"></i>
          </div>
        <?php } ?>
      </td>
      <td>
        <?php if ($row->print=="Y") {?>
          <div  onClick="akses_menu('<?=$row->id?>','<?=$row->id_level?>','print','checked')">
            <i class="fas fa-check-circle text-success btn"></i>
          </div>
        <?php }else{?>
          <div  onClick="akses_menu('<?=$row->id?>','<?=$row->id_level?>','print','unchecked')">
            <i class="fa fa-times-circle text-red btn"></i>
          </div>
        <?php } ?>
      </td>
      <td>
        <?php if ($row->upload=="Y") {?>
          <div  onClick="akses_menu('<?=$row->id?>','<?=$row->id_level?>','upload','checked')">
            <i class="fas fa-check-circle text-success btn"></i>
          </div>
        <?php }else{ ?>
          <div  onClick="akses_menu('<?=$row->id?>','<?=$row->id_level?>','upload','unchecked')">
            <i class="fa fa-times-circle text-red btn"></i>
          </div>
        <?php } ?>
      </td>
      <td>
        <?php if ($row->download=="Y") {?>
          <div  onClick="akses_menu('<?=$row->id?>','<?=$row->id_level?>','download','checked')">
            <i class="fas fa-check-circle text-success btn"></i>
          </div>
        <?php }else{ ?>
          <div  onClick="akses_menu('<?=$row->id?>','<?=$row->id_level?>','download','unchecked')">
            <i class="fa fa-times-circle text-red btn"></i>
          </div>
        <?php } ?>
      </td>
    </tr>
    <?php 

    foreach ($data_submenu as $sub ) {
      if ($sub->id_menu==$row->id_menu) {
        ?>
        <tr>
          <td></td>
          <td><?= $sub->nama_submenu?></td>
          <td>
            <?php if ($sub->view=="Y") {?>
              <div  onClick="akses_submenu('<?=$sub->id?>','<?=$sub->id_level?>','view','checked')">
                <i class="fas fa-check-circle text-success btn"></i>
              </div>
            <?php }else{ ?>
              <div  onClick="akses_submenu('<?=$sub->id?>','<?=$sub->id_level?>','view','unchecked')">
                <i class="fa fa-times-circle text-red btn"></i>
              </div>
            <?php } ?>
          </td>
          <td>
           <?php if ($sub->add=="Y") {?>
            <div  onClick="akses_submenu('<?=$sub->id?>','<?=$sub->id_level?>','add','checked')">
              <i class="fas fa-check-circle text-success btn"></i>
            </div>
          <?php }else{ ?>
            <div  onClick="akses_submenu('<?=$sub->id?>','<?=$sub->id_level?>','add','unchecked')">
              <i class="fa fa-times-circle text-red btn"></i>
            </div>
          <?php } ?>
        </td>
        <td>
         <?php if ($sub->edit=="Y") {?>
          <div  onClick="akses_submenu('<?=$sub->id?>','<?=$sub->id_level?>','edit','checked')">
            <i class="fas fa-check-circle text-success btn"></i>
          </div>
        <?php }else{ ?>
          <div  onClick="akses_submenu('<?=$sub->id?>','<?=$sub->id_level?>','edit','unchecked')">
            <i class="fa fa-times-circle text-red btn"></i>
          </div>
        <?php } ?>
      </td>
      <td>
        <?php if ($sub->delete=="Y") {?>
          <div  onClick="akses_submenu('<?=$sub->id?>','<?=$sub->id_level?>','delete','checked')">
            <i class="fas fa-check-circle text-success btn"></i>
          </div>
        <?php }else{ ?>
          <div  onClick="akses_submenu('<?=$sub->id?>','<?=$sub->id_level?>','delete','unchecked')">
            <i class="fa fa-times-circle text-red btn"></i>
          </div>
        <?php } ?>
      </td>
      <td>
        <?php if ($sub->print=="Y") {?>
          <div  onClick="akses_submenu('<?=$sub->id?>','<?=$sub->id_level?>','print','checked')">
            <i class="fas fa-check-circle text-success btn"></i>
          </div>
        <?php }else{?>
          <div  onClick="akses_submenu('<?=$sub->id?>','<?=$sub->id_level?>','print','unchecked')">
            <i class="fa fa-times-circle text-red btn"></i>
          </div>
        <?php } ?>
      </td>
      <td>
        <?php if ($sub->upload=="Y") {?>
          <div  onClick="akses_submenu('<?=$sub->id?>','<?=$sub->id_level?>','upload','checked')">
            <i class="fas fa-check-circle text-success btn"></i>
          </div>
        <?php }else{ ?>
          <div  onClick="akses_submenu('<?=$sub->id?>','<?=$sub->id_level?>','upload','unchecked')">
            <i class="fa fa-times-circle text-red btn"></i>
          </div>
        <?php } ?>
      </td>
       <td>
        <?php if ($sub->download=="Y") {?>
          <div  onClick="akses_submenu('<?=$sub->id?>','<?=$sub->id_level?>','download','checked')">
            <i class="fas fa-check-circle text-success btn"></i>
          </div>
        <?php }else{ ?>
          <div  onClick="akses_submenu('<?=$sub->id?>','<?=$sub->id_level?>','download','unchecked')">
            <i class="fa fa-times-circle text-red btn"></i>
          </div>
        <?php } ?>
      </td>
    </tr>
    <?php 
  }
} ?>
<?php 
} ?>
</tbody>
</table>
</div>

<script type="text/javascript">

  function akses_menu(id, level, field, chek) {
    $.ajax({
      type : 'POST',
      url : '<?=base_url('userlevel/update_akses_menu') ?>',
      data : {chek:chek,id:id,field:field},
      success : function (data) {
        $.ajax({
          url : '<?php echo base_url('userlevel/view_akses_menu'); ?>',
          type : 'post',
          data : 'id='+level,
          success : function(respon){
            $("#md_def").html(respon);
          }
        })
      }
    });
  }

function akses_submenu(id, level, field, chek) {
    $.ajax({
      type : 'POST',
      url : '<?=base_url('userlevel/update_akses_submenu') ?>',
      data : {chek:chek,id:id,field:field},
      success : function (data) {
        $.ajax({
          url : '<?php echo base_url('userlevel/view_akses_menu'); ?>',
          type : 'post',
          data : 'id='+level,
          success : function(respon){
            $("#md_def").html(respon);
          }
        })
      }
    });
  }

 
</script>