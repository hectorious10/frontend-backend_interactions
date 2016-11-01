<div id="page-content-wrapper">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">Tarzian Moving Co. - Dashboard</h3>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <table id="clinfo" class="table table-hover table-striped" cellspacing="0" width="100%">
                </table>
            </div>
        </div>



</div>
</div><!-- /#wrapper -->

<script src="<?php echo base_url();?>ext/js/jquery.min.js"></script>
<script src="<?php echo base_url();?>ext/js/bootstrap.min.js"></script>
<script src="//cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" type="text/javascript"></script>


<script>
    $(document).ready(function() {
        var table = $('#clinfo').DataTable({
            "ajax": {"url": "<?php echo base_url();?>backoffice/entries",
                "dataSrc": ""},
            "columns": [
                { "data": "cid"  },
                { "data": "cfnm" },
                { "data": "clnm" },
                { "data": "cpnb" },
                { "data": "ceml" },
                { "data": "ccrtd"}
            ],
            "pageLength": 10,
            "columnDefs": [
                {
                    "targets": [ 0 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [6],
                    "data": null,
                    "defaultContent": "<button>View</button>"
                }
            ],
            "order": [[ 0, "desc" ]],
            "dom": 'i<"col-md-9"tpl>r<"col-md-3"f><"clear">',
            "buttons": ['Edit', 'View']
        });

        $('#clinfo tbody').on( 'click', 'tr', function () {
            var id = table.row( this ).cache();
            window.location.href = "<?php echo base_url('backoffice/editinv') ?>/"+id[0];

        } );
    });




    $("#menu-toggle").click(function(e) {e.preventDefault();$("#wrapper").toggleClass("toggled");});
</script>
