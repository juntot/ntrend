<template>
    <div>
        <div id="loader2" >
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12">
            <h4>MANAGE DEPARTMENT</h4>
        </div>
        <div class="col-lg-12 canvas-chart">        
            <div class="col-lg-6 col-md-6  with-margin-bottom nopadding">
                <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">ADD NEW</button>
            </div>
            <table id="managedept" class="mdl-data-table" style="width:100%"></table>

            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <!-- <ManageDepartment ></ManageDepartment> -->
                        </div>
                        <!-- <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div> -->
                    </div>

                </div>
            </div>
            
        </div>
    </div>
</template>

<script>

export default {
   
        
 
}
</script>
