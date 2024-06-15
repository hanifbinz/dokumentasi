<style type="text/css">
  #tbl-lap thead tr th{
    text-align:center; vertical-align : middle;
  }
  .item {
    text-align:center; vertical-align : middle;
  }
</style>
<section class="col-lg-12 connectedSortable">

<div>
    <marquee behavior="scroll" direction="left" scrollamount="5">
        <b style="font-size: 2em; color: red;">PT BUKITMEGA MASABADI - CIKARANG "GROW UP TOGETHER"</b>
    </marquee>
</div>
  <!-- Calendar -->
  <div class="card bg-gradient-success">
    <div class="card-header border-0">

      <h3 class="card-title">
        <i class="far fa-calendar-alt"></i>
        Kalender
      </h3>
      <!-- tools card -->
      <div class="card-tools">

        <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
          <i class="fas fa-minus"></i>
        </button>
        <button type="button" class="btn btn-success btn-sm" data-card-widget="remove">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <!-- /. tools -->
    </div>
    <!-- /.card-header -->
    <div class="card-body pt-0">
      <!--The calendar -->
      <div id="calendar" style="width: 100%"></div>
    </div>
    <!-- /.card-body -->
	
  </div>
  <!-- /.card -->
</section>
<script type="text/javascript">
      $('#calendar').datetimepicker({
        format: 'L',
        inline: true
    })
</script>
